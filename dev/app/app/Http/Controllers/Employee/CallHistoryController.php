<?php

namespace App\Http\Controllers\Employee;

use App\Helpers\CommonHelper;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Services\BexioService;
use App\Services\ClockodoService;
use App\Services\WildixinService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Employee\EmployeeBaseController;
use App\Models\TimeLog;
use App\Models\BexioEmployee;
use App\Models\BexioEmployeeHasCompany;
use App\Models\CallHistory;
use App\Models\UserHasOngoingCall;
use App\Services\SearchChService;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CallHistoryController extends EmployeeBaseController
{
    public function index(WildixinService $wildixinService): view | RedirectResponse
    {
        $employee = auth('employee')->user();
        // if ($request->get('sync_call_history', 0)) {
        // $wildixinService->syncCallHistory($employee->id);
        $clockoDoUserId = $employee->clockoDoUserId();

            //return redirect()->route('employee.call.history')
            //->with('global_message', __('Call History Synced Successfully.'));
        // }

        $responseType = CallHistory::groupBy('disposition')->get()->pluck('disposition')->toArray();
        $callTypes = CallHistory::getCallTypes();

        return view(
            'employee.call.index',
            [
                'title' => __('Call History'),
                'responseType' => $responseType,
                'callTypes' => $callTypes,
                'clockoDoUserId' => $clockoDoUserId,
            ]
        );
    }

    public function list(Request $request, SearchChService $searchChService)
    {
        $employee = auth('employee')->user();
        $start = $request->get('start', 0);
        $length = $request->get('length', 20);
        $draw = $request->get('draw');
        $keywords = $request->get('keywords', null);
        $date = $request->get('date', null);
        $endDate = $request->get('end_date', null);
        $responseType = $request->get('response_type', null);
        $callType = $request->get('call_type', null);

        $model = CallHistory::where('user_id', $employee->id)
            ->orderBy('start', 'DESC');
        $totalRecords = $model->count();

        if ($keywords) {
            $model->where(function ($query) use ($keywords) {
                $query->where('from_name', 'like', "%" . $keywords . "%");
                $query->orWhere('to_name', 'like', "%$keywords%");
                $query->orWhere('to_number', 'like', "%$keywords%");
                $query->orWhere('from_number', 'like', "%$keywords%");
            });
        }

        if ($date && $endDate) {
            $model->whereBetween('start', [date('Y-m-d', strtotime($date)), date('Y-m-d', strtotime($endDate))]);
        } elseif ($date) {
            $model->whereDate('start', date('Y-m-d', strtotime($date)));
        } elseif ($endDate) {
            $model->whereDate('start', date('Y-m-d', strtotime($endDate)));
        }

        if ($responseType) {
            $model->where('disposition', 'like', $responseType);
        }

        if ($callType == CallHistory::CALL_TYPE_OUTGOING) {
            $model->where('from_number', 'like', $employee->extension_number);
        } elseif ($callType == CallHistory::CALL_TYPE_INCOMING) {
            $model->where('to_number', 'like', $employee->extension_number);
        }

        $displayRecords = $model->count();
        $model
            ->limit($length)
            ->offset($start);
        $history = $model->get();

        if ($history->count() <= 0) {
            return response()->json([
                'draw' => intval($draw),
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => null
            ], Response::HTTP_OK);
        }

        $calls = [];
        $clockoDoSetting = config('global.CLOCKODO_API_KEY', '');
        $clockoDoUserId = $employee->clockoDoUserId();
        $foundChPhoneNumbers = [];

        foreach ($history as $key => $record) {
            $timeLogs = $record->timeLogs()->count();
            $recordObj = $record;
            $record = $record->toArray();
            $record['disposition'] = $record['disposition'] ? __($record['disposition']) : '';
            $wildixinResponse = json_decode($record['wildixin_response'], true);
            $callType = ($record['from_number'] == $employee->extension_number) ? CallHistory::CALL_TYPE_OUTGOING : CallHistory::CALL_TYPE_INCOMING;
            $callTypeKey = ($callType == CallHistory::CALL_TYPE_OUTGOING) ? 'to_name' : 'from_name';
            $numberCallTypeKey = ($callType == CallHistory::CALL_TYPE_OUTGOING) ? 'to_number' : 'from_number';
            $phoneNumber = $record[$numberCallTypeKey];
            $bexioUrl = 'javascript:void(0);';
            $target = '';

            $companyName = '--';
            $bexioEmployee = BexioEmployee::where(function ($query) use ($phoneNumber) {
                $query->where('phone_number', $phoneNumber);
                $query->orWhere('mobile_number', $phoneNumber);
                $query->orWhere('fax_number', $phoneNumber);
            })
            ->where([
                'contact_type' => BexioEmployee::CONTACT_TYPE_EMPLOYEE
            ])
            ->first();

            if ($bexioEmployee instanceof BexioEmployee) {
                $record[$callTypeKey] = $bexioEmployee->name;
                $bexioUrl = "https://office.bexio.com/index.php/kontakt/show/id/{$bexioEmployee->emp_id}";
                $target = ' target="_blank"';
                $companies = BexioEmployee::whereIn(
                    'emp_id',
                    BexioEmployeeHasCompany::where(
                        'bexio_employee_id',
                        $bexioEmployee->emp_id
                    )
                    ->select('bexio_company_id')
                )
                ->where('contact_type', BexioEmployee::CONTACT_TYPE_COMPANY);

                $companyName = ($companies->count() == 1) ? $companies->first()->name : '--';
            } else {
                if ($phoneNumber && ! isset($foundChPhoneNumbers[$phoneNumber])) {
                    $searchChData = $searchChService->getContacts($phoneNumber);

                    if ($searchChData) {
                        $foundChPhoneNumbers[$phoneNumber] = reset($searchChData);
                    }
                }

                if ($phoneNumber && isset($foundChPhoneNumbers[$phoneNumber])) {
                    $companyName = $foundChPhoneNumbers[$phoneNumber]['name'] .
                        ' <a target="_blank" href="' .
                            route('employee.contact.add', [
                                'ch' => base64_encode(json_encode($foundChPhoneNumbers[$phoneNumber]))
                            ]) . '" class="btn btn-danger">' . __('Add To Bexio') . '</a>';
                }
            }

            // $record['new_' . $callTypeKey] .= ' (' . $companyName . ')';
            $wildixinResponse['company_name'] = $companyName;
            $record['company'] = '<p class="m-0">' . __('Employee') . ': ' . $record[$callTypeKey] . '</p><p class="m-0">' . __('Company') . ': ' . $companyName . '</p>';
            $record['call_id'] = $wildixinResponse['id'];
            $record['call_type'] = CallHistory::getCallTypeText($callType);
            // $record['call_details'] = sprintf(
            //     __('<strong>Start:</strong> %s%s<strong>End:</strong> %s%s<strong>Duration:</strong> %s (hh:mm)'),
            //     CommonHelper::date($record['start']) . ' ' . CommonHelper::time($record['start']),
            //     '<br/>',
            //     CommonHelper::date($record['end']) . ' ' . CommonHelper::time($record['end']),
            //     '<br/>',
            //     CommonHelper::convertSecondsToTime($wildixinResponse['duration'])
            // );
            $record['call_details_record']['start'] = '<p class="border-bottom m-2 pb-2"><strong>' . __('Start') . ':</strong> ' . CommonHelper::date($record['start']) . ' ' . CommonHelper::time($record['start']) . '</p>';
            $record['call_details_record']['end'] = '<p class="border-bottom m-2 pb-2"><strong>' . __('End') . ':</strong> ' .  CommonHelper::date($record['end']) . ' ' . CommonHelper::time($record['end']);
            $record['call_details_record']['duraion'] = '<p class="m-2 mb-0 pb-2"><strong>' . __('Duration') . ':</strong> ' .  CommonHelper::convertSecondsToTime($wildixinResponse['duration']) . ' (hh:mm)</p>';

            $record['call_details'] = implode('', $record['call_details_record']);

            $record['contact_details'] = sprintf(
                __('<strong>From:</strong> %s (%s)%s<strong>To:</strong> %s (%s)'),
                $record['from_name'],
                $record['from_number'],
                '<br/>',
                $record['to_name'],
                $record['to_number']
            );
            $record['call_action'] = sprintf(
                __('%s Call (%s)'),
                CallHistory::getCallTypeText($callType),
                $record['disposition']
            );

            $toNumberKey = $numberCallTypeKey == 'to_number' ? 'from' : 'to';
            $record[$toNumberKey . '_name'] = $record[$toNumberKey . '_name'] . ' (' . $record[$toNumberKey . '_number'] . ')';
            $record[$numberCallTypeKey] = '<a href="tel:' . $phoneNumber . '">' . $record[$numberCallTypeKey] . '</a>';
            $record['action']['callback'] = '<a href="tel:' . $phoneNumber . '" class="btn btn-primary">' . __('Callback') . '</a>';
            $record['action']['bexio'] = '<a href="' . $bexioUrl . '" class="btn btn-primary" title="' . __('Bexio') . '"' . $target . '>' . __('Bexio') . '</a>';
            /*$record['action']['log_time'] = ($clockoDoSetting && $clockoDoUserId ?
                '<a href="#" data-logurl="' .
                route(
                    'employee.call.log.popup',
                    [
                        $record['id'],
                        'data' => base64_encode(json_encode($wildixinResponse))
                    ]
                )
                . '" class="btn btn-danger log-time-popup col-12" title="' . __('Log Time') . '">' .
                __('Log Time') .
                '</a>

                <a href="' . route('employee.call.history.delete', [$recordObj]) . '" title="' .
                __('Delete Record') .
                '" onclick="return confirm(\'' . __('Are you sure?') . '\');" class="btn btn-danger col-12 mt-2">' .
                __('Delete Record') .
                '</a>' :
                ''
            );*/

            $record['action']['log_time'] = ($clockoDoSetting && $clockoDoUserId ?
                '<a href="#" data-logurl="' .
                route(
                    'employee.call.log.popup',
                    [
                        $record['id'],
                        'data' => base64_encode(json_encode($wildixinResponse))
                    ]
                )
                . '" class="btn btn-danger log-time-popup " title="' . __('Log Time') . '">' .
                __('Log Time') .
                '</a>' :
                ''
            );
            $record['action']['log_time_history'] = '<a href="' . route('employee.call.logs', [$record['id']]) .
                '" class="' . ($timeLogs ? 'btn-success ' : 'btn-warning ') . 'btn lth-btn" title="' . __('Log Time History') . '">' . __('Log Time History') . '</a>';
            $calls[$key] = $record;
        }

        return response()->json([
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $displayRecords,
            'aaData' => $calls,
        ], Response::HTTP_OK);
    }

    public function deleteCallHistory(CallHistory $callHistory, WildixinService $wildixinService)
    {
        if ($callHistory->user_id != auth('employee')->user()->id) {
            return redirect()->back()->with('global_error', __('Unable to delete record.'));
        }

        $wildixRecord = json_decode($callHistory->wildixin_response);

        if (! $wildixRecord) {
            return redirect()->back()->with('global_error', __('Unable to delete record.'));
        }

        try {
            $wildixinService->deleteCallHistoryById($wildixRecord->id);
        } catch (Exception $e) {
            // return redirect()->back()->with('global_error', __('Unable to delete record.'));
        }

        TimeLog::where('call_history_id', $callHistory->id)->delete();
        $callHistory->delete();

        return redirect()->back()->with('global_message', __('Record successfully deleted.'));
    }

    public function popup($id, ClockodoService $clockodoService, Request $request)
    {
        $services = $clockodoService->getServices();
        $customers = $clockodoService->getCustomers();

        $callHistory = $request->get('data', null);
        $callHistory = $callHistory ? json_decode(base64_decode($callHistory), true) : [];

        //get companies assigned to user
        $employee = auth('employee')->user();
        $callType = ($callHistory['from_number'] == $employee->extension_number) ? CallHistory::CALL_TYPE_OUTGOING : CallHistory::CALL_TYPE_INCOMING;
        $phoneNumber = ($callType == CallHistory::CALL_TYPE_OUTGOING) ? $callHistory['to_number'] : $callHistory['from_number'];

        $bexioEmployee = BexioEmployee::where(function ($query) use ($phoneNumber) {
            $query->where('phone_number', $phoneNumber);
            $query->orWhere('mobile_number', $phoneNumber);
            $query->orWhere('fax_number', $phoneNumber);
        })
            ->where([
                'contact_type' => BexioEmployee::CONTACT_TYPE_EMPLOYEE
            ])
            ->first();
        $companies = [];

        if ($bexioEmployee instanceof BexioEmployee) {
            $companies = BexioEmployee::whereIn(
                'emp_id',
                BexioEmployeeHasCompany::where(
                    'bexio_employee_id',
                    $bexioEmployee->emp_id
                )
                    ->select('bexio_company_id')
            )
                ->where('contact_type', BexioEmployee::CONTACT_TYPE_COMPANY)
                ->pluck('name')
                ->toArray();
        }

        $projects = [];

        if ($customers) {
            foreach ($customers as $customer) {
                if (isset($callHistory['company_name']) && $callHistory['company_name'] == $customer['name']) {
                    $projects = $clockodoService->getProjects($customer['id']);
                }
            }
        }

        return view(
            'employee.call.popup',
            [
                'services' => $services,
                'customers' => $customers,
                'callHistory' => $callHistory,
                'id' => $id,
                'companies' => $companies,
                'projects' => $projects,
            ]
        );
    }

    public function globalPopup(ClockodoService $clockodoService, Request $request)
    {
        $ongoingCallId = $request->get('ongoing_call_id', null);
        $ongoingCall = UserHasOngoingCall::find($ongoingCallId);
        $companies = [];
        $callDate = date('Y-m-d');
        $callStartedAtTime = date('H:i:s');
        $callEndedAtTime = null;
        $duration = null;
        $firstClockodoCompany = null;

        if ($ongoingCall instanceof UserHasOngoingCall) {
            $phoneNumber = $ongoingCall->phone_number;

            $callDate = $ongoingCall->created_at->format('Y-m-d');
            $callStartedAt = $ongoingCall->created_at;
            $callStartedAtTime = $callStartedAt->format('H:i:s');
            $callEndedAt = $ongoingCall->status == 1 ? $ongoingCall->updated_at : Carbon::now();
            $callEndedAtTime = $callEndedAt->format('H:i:s');
            $secondsDifference = $callStartedAt->diffInSeconds($callEndedAt);
            $duration = CommonHelper::convertSecondsToTime($secondsDifference);

            $bexioEmployee = BexioEmployee::where(function ($query) use ($phoneNumber) {
                $query->where('phone_number', $phoneNumber);
                $query->orWhere('mobile_number', $phoneNumber);
                $query->orWhere('fax_number', $phoneNumber);
            })
            ->first();

            if ($bexioEmployee instanceof BexioEmployee) {
                if ($bexioEmployee->contact_type == BexioEmployee::CONTACT_TYPE_COMPANY) {
                    $firstClockodoCompany = $bexioEmployee;
                    $companies = [
                        $bexioEmployee->pluck('name')->toArray(),
                    ];
                } else {
                    $companies = BexioEmployee::whereIn(
                        'emp_id',
                        BexioEmployeeHasCompany::where(
                            'bexio_employee_id',
                            $bexioEmployee->emp_id
                        )
                        ->select('bexio_company_id')
                    )
                    ->where('contact_type', BexioEmployee::CONTACT_TYPE_COMPANY)
                    ->pluck('name')
                    ->toArray();
                }
            }
        }

        $services = $clockodoService->getServices();
        $customers = $clockodoService->getCustomers();
        $employee = auth('employee')->user();
        $bexioModel = $employee->getBexioEmployeeRecord();

        if (!$firstClockodoCompany instanceof BexioEmployee && $bexioModel instanceof BexioEmployee) {
            $usercompanies = BexioEmployee::whereHas('companies')->with('companies', function ($query) {
                $query->whereNotNull('clockodo_emp_id');
            })->where('emp_id', $bexioModel->emp_id)->first();
            $firstClockodoCompany = $usercompanies instanceof BexioEmployee ? $usercompanies->companies->first() : null;
        }

        if (!$firstClockodoCompany instanceof BexioEmployee) {
            $firstClockodoCompany = new BexioEmployee();
        }

        if (! $companies) {
            $companies = BexioEmployee::where('contact_type', BexioEmployee::CONTACT_TYPE_COMPANY)
            ->pluck('name')
            ->toArray();
        }

        $projects = [];

        if ($customers) {
            foreach ($customers as $customer) {
                if (
                    $firstClockodoCompany instanceof BexioEmployee &&
                    $firstClockodoCompany->clockodo_emp_id == $customer['id']
                ) {
                    $projects = $clockodoService->getProjects($customer['id']);
                }
            }
        }

        return view(
            'employee.call.global-popup',
            [
                'services' => $services,
                'customers' => $customers,
                'companies' => $companies,
                'firstclockodocompany' => $firstClockodoCompany,
                'projects' => $projects,
                'callDate' => $callDate,
                'callStartedAt' => $callStartedAtTime,
                'callEndedAt' => $callEndedAtTime,
                'duration' => $duration,
            ]
        );
    }

    public function store($id, Request $request, ClockodoService $clockodoService)
    {
        $request->validate([
            'nt_client' => 'required',
            'nt_service' => 'required',
            'nt_date' => 'required',
            'nt_duration' => 'required',
            'nt_start_time' => 'required',
            'nt_end_time' => 'required',
            'nt_service_desc' => 'required',
        ], [
            'nt_client.required' => __('Please select client'),
            'nt_service.required' => __('Please select service'),
            'nt_date.required' => __('Please enter date'),
            'nt_duration.required' => __('Please enter duration'),
            'nt_start_time.required' => __('Please enter start time'),
            'nt_end_time.required' => __('Please enter end time'),
            'nt_service_desc.required' => __('Please enter description'),
        ]);

        list($hours, $minutes) = explode(':', $request->nt_duration);
        $durations = ($hours * 3600) + ($minutes * 60);
        $startTime =  \App\Helpers\CommonHelper::dateTime($request->nt_date . ' ' . $request->nt_start_time, true, "UTC");
        $endTime =  \App\Helpers\CommonHelper::dateTime($request->nt_date . ' ' . $request->nt_end_time, true, "UTC");

        $data = [
            'customers_id' => $request->nt_client,
            'services_id' => $request->nt_service,
            'duration' => $durations,
            'billable' => true,
            'time_since' => date(
                'Y-m-d\TH:i:s\Z',
                strtotime($startTime)
            ),
            'time_until' => date(
                'Y-m-d\TH:i:s\Z',
                strtotime($endTime)
            ),
            'text' => $request->nt_service_desc,
        ];

        $employee = auth('employee')->user();
        $bexioModel = $employee->getBexioEmployeeRecord();

        if ($bexioModel instanceof BexioEmployee && $bexioModel->contact_type == BexioEmployee::CONTACT_TYPE_EMPLOYEE && $bexioModel->clockodo_emp_id) {
            $data['users_id'] = $bexioModel->clockodo_emp_id;
        }

        $projectId = $request->nt_project_id ?? null;

        if ($projectId) {
            $data['projects_id'] = $projectId;
        }

        $result = $clockodoService->addTimeLog($data);
        $errors = $result['error'] ?? null;

        if (!$errors) {
            $timeLogData = [
                'user_id' => $employee->id,
                'clockodo_entry_id' =>  $result['entry']['id'] ?? null,
                'call_history_id' => $id,
                'date' => date('Y-m-d', strtotime($request->nt_date)),
                'duration' => $durations, // Duration in seconds
                'start_time' => date('H:i:s', strtotime($request->nt_start_time)),
                'end_time' => date('H:i:s', strtotime($request->nt_end_time)),
                'client_id' => $request->nt_client,
                'client_name' => $request->client_name,
                'clockodo_project_id' => $projectId,
                'clockodo_project_name' => $request->project_name ?? null,
                'service_id' => $request->nt_service,
                'service_name' => $request->service_name,
                'service_description' => $request->nt_service_desc,
            ];

            $result = TimeLog::create($timeLogData);
        }

        return response()->json($result);
    }

    public function globalLogTime(Request $request, ClockodoService $clockodoService)
    {
        $request->validate([
            'nt_client' => 'required',
            'nt_service' => 'required',
            'nt_date' => 'required',
            'nt_duration' => 'required',
            'nt_start_time' => 'required',
            'nt_end_time' => 'required',
            'nt_service_desc' => 'required',
        ], [
            'nt_client.required' => __('Please select client'),
            'nt_service.required' => __('Please select service'),
            'nt_date.required' => __('Please enter date'),
            'nt_duration.required' => __('Please enter duration'),
            'nt_start_time.required' => __('Please enter start time'),
            'nt_end_time.required' => __('Please enter end time'),
            'nt_service_desc.required' => __('Please enter description'),
        ]);

        list($hours, $minutes) = explode(':', $request->nt_duration);
        $durations = ($hours * 3600) + ($minutes * 60);

        $startTime =  \App\Helpers\CommonHelper::dateTime($request->nt_date . ' ' . $request->nt_start_time, true, "UTC");
        $endTime =  \App\Helpers\CommonHelper::dateTime($request->nt_date . ' ' . $request->nt_end_time, true, "UTC");

        $data = [
            'customers_id' => $request->nt_client,
            'services_id' => $request->nt_service,
            'duration' => $durations,
            'billable' => true,
            'time_since' => date(
                'Y-m-d\TH:i:s\Z',
                strtotime($startTime)
            ),
            'time_until' => date(
                'Y-m-d\TH:i:s\Z',
                strtotime($endTime)
            ),
            'text' => $request->nt_service_desc,
        ];

        $employee = auth('employee')->user();
        if (empty($employee->clockodo_emp_id)) {
            return ['error' => 'User not found on clockodo'];
        }
        $data['users_id'] = $employee->clockodo_emp_id;
        $projectId = $request->nt_project_id ?? null;

        if ($projectId) {
            $data['projects_id'] = $projectId;
        }

        $result = $clockodoService->addTimeLog($data);
        $errors = $result['error'] ?? null;

        if (!$errors) {
            $timeLogData = [
                'user_id' => $employee->id,
                'clockodo_entry_id' => $result['entry']['id'] ?? null,
                'call_history_id' => null,
                'date' => date('Y-m-d', strtotime($request->nt_date)),
                'duration' => $durations, // Duration in seconds
                'start_time' => date('H:i:s', strtotime($request->nt_start_time)),
                'end_time' => date('H:i:s', strtotime($request->nt_end_time)),
                'client_id' => $request->nt_client,
                'client_name' => $request->client_name,
                'clockodo_project_id' => $projectId,
                'clockodo_project_name' => $request->project_name ?? null,
                'service_id' => $request->nt_service,
                'service_name' => $request->service_name,
                'service_description' => $request->nt_service_desc,
            ];

            $result = TimeLog::create($timeLogData);
        }

        return response()->json($result);
    }

    public function bexioRedirect($phoneNumber, BexioService $bexioService)
    {
        $bexioData = $bexioService->getContactByPhoneNumber($phoneNumber);

        if (!isset($bexioData['id'])) {
            return redirect()
                ->route('employee.call.history')
                ->with('global_error', __('User not found.'));
        }

        $bexioUrl = 'https://office.bexio.com/index.php/kontakt/show/id/' . $bexioData['id'];

        return Redirect::to($bexioUrl);
    }

    public function logs(
        ClockodoService $clockodoService,
        Request $request,
        ?int $id = null
    ): view | RedirectResponse {
        $projects = TimeLog::where('user_id', auth('employee')->user()->id)
            ->whereNotNull('clockodo_project_id')
            ->whereNotNull('clockodo_project_name')
            ->where('clockodo_project_name', '!=', 'Choose...')
            ->groupBy('clockodo_project_id')
            ->get();

        $clients = TimeLog::where('user_id', auth('employee')->user()->id)
            ->whereNotNull('client_id')
            ->whereNotNull('client_name')
            ->groupBy('client_id')
            ->get();

        $services = TimeLog::where('user_id', auth('employee')->user()->id)
            ->whereNotNull('service_id')
            ->whereNotNull('service_name')
            ->groupBy('service_id')
            ->get();

        return view(
            'employee.call.logs',
            [
                'title' => __('Call Logs'),
                'id' => $id,
                'projects' => $projects,
                'clients' => $clients,
                'services' => $services,
            ]
        );
    }

    public function logList(Request $request, ?int $id = null)
    {
        $start = $request->get('start', 0);
        $length = $request->get('length', 20);
        $draw = $request->get('draw');

        $cond = [
            'user_id' => auth('employee')->user()->id,
        ];

        if ($id) {
            $cond['call_history_id'] = $id;
        }

        $model = TimeLog::where($cond)->orderBy('id', 'DESC');

        $model->when($request->filled('client_id'), function ($query) use ($request) {
            $query->where('client_id', $request->client_id);
        })
        ->when($request->filled('project_id'), function ($query) use ($request) {
            $query->where('clockodo_project_id', $request->project_id);
        })
        ->when($request->filled('service_id'), function ($query) use ($request) {
            $query->where('service_id', $request->service_id);
        })
        ->when($request->filled('duration'), function ($query) use ($request) {
            $query->where('duration', $request->duration);
        });

        if ($request->filled('start_datetime') && $request->filled('end_datetime')) {
            $startDateTime = Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s');
            $endDateTime = Carbon::parse($request->end_datetime)->format('Y-m-d H:i:s');

            $model->whereRaw("CONCAT(date, ' ', start_time) >= ?", [$startDateTime])
              ->whereRaw("CONCAT(date, ' ', end_time) <= ?", [$endDateTime]);
        } elseif ($request->filled('start_datetime')) {
            $startDateTime = Carbon::parse($request->start_datetime)->format('Y-m-d H:i:s');

            $model->whereRaw("CONCAT(date, ' ', start_time) >= ?", [$startDateTime]);
        } elseif ($request->filled('end_datetime')) {
            $endDateTime = Carbon::parse($request->end_datetime)->format('Y-m-d H:i:s');

            $model->whereRaw("CONCAT(date, ' ', end_time) <= ?", [$endDateTime]);
        }

        if ($request->filled('search')) {
            $model->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('client_name', 'like', '%' . $request->search . '%')
                        ->orWhere('clockodo_project_name', 'like', '%' . $request->search . '%')
                        ->orWhere('service_name', 'like', '%' . $request->search . '%')
                        ->orWhere('service_description', 'like', '%' . $request->search . '%');
                });
            });
        }

        $totalRecords = $model->count();
        $displayRecords = $model->count();

        $model->limit($length)
            ->offset($start);

        $history = $model->get();

        if ($history->count() <= 0) {
            return response()->json([
                'draw' => intval($draw),
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => null
            ], Response::HTTP_OK);
        }

        $calls = [];

        foreach ($history->toArray() as $key => $record) {
            $record['clockodo_project_name'] = $record['clockodo_project_name'] == 'Choose...' ? null : $record['clockodo_project_name'];
            $record['clockodo_project_name'] = $record['clockodo_project_name'] ? $record['clockodo_project_name'] : __('N/A');
            $record['date'] = CommonHelper::date($record['date']);
            $record['start_time'] = CommonHelper::time($record['start_time']);
            $record['end_time'] = CommonHelper::time($record['end_time']);
            $record['duration'] = CommonHelper::convertSecondsToTime($record['duration']) . ' ' . __('Hours');
            $record['action'] = '
                <!--<a href="#" data-url="' . route('employee.call.log.desc', [$record['id']]) .
                '" class="btn btn-primary   m-2 show-desc" title="' . __('View') . '">
                ' . __('View') . '
                </a>-->
                <a href="' . route('employee.clockodo.log.delete', [$record['id']]) .
                '" class="btn btn-danger  m-2" onclick="return confirm(\'' . __('Are you sure?') . '\');"
                title="' . __('Delete Record') . '">
                    <i class="fa fa-trash"></i>
                </a>';
            $calls[$key] = $record;
        }

        return response()->json([
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $displayRecords,
            'aaData' => $calls,
        ], Response::HTTP_OK);
    }

    public function logDesc($id)
    {
        $model = TimeLog::find($id);
        $desc = $model->service_description ?? '';

        return view('employee.call.log-desc', ['desc' => $desc]);
    }

    public function clockoDoProjects(Request $request, ClockodoService $clockodoService)
    {
        $clockoDoCustomerId = $request->input('customer_id', null);
        $html = '<option value="">' . __('Choose...') . '</option>';

        if (! $clockoDoCustomerId) {
            response()->json([
                'status' => 0,
                'html' => $html,
            ]);
        }

        $projects = $clockodoService->getProjects($clockoDoCustomerId);

        if ($projects) {
            foreach ($projects as $project) {
                $html .= '<option value="' . $project['id'] . '">' . $project['name'] . '</option>';
            }
        }

        return response()->json([
            'status' => 1,
            'html' => $html,
        ]);
    }
}
