<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\BexioEmployee;
use App\Models\BexioEmployeeHasCompany;
use App\Models\UserHasOngoingCall;
use App\Services\SearchChService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request, SearchChService $searchChService): view | RedirectResponse
    {
        $user = auth('employee')->user();
        $foundChPhoneNumbers = [];
        $currentCallName = null;
        $lastCallName = null;

        $lastCall = UserHasOngoingCall::where([
            'extension_number' => $user->extension_number,
            'status' => 1
        ])
        ->orderBy('updated_at', 'DESC')
        ->first();

        $currentCall = UserHasOngoingCall::where([
            'extension_number' => $user->extension_number,
            'status' => 0
        ])
        ->orderBy('updated_at', 'DESC')
        ->first();

        $bexioEmp = $user->getBexioEmployeeRecord();
        $bexioUrl = ($bexioEmp instanceof BexioEmployee) ?
            'https://office.bexio.com/index.php/kontakt/show/id/' . $bexioEmp->emp_id :
            null;
        $clockoDoUserId = $user->clockoDoUserId();

        if ($currentCall instanceof UserHasOngoingCall) {
            $phoneNumber = $currentCall->phone_number;
            $currentCallName = '<a href="tel:' . $phoneNumber . '">' . $phoneNumber . '</a>';
            $bexioEmployee = BexioEmployee::where(function ($query) use ($phoneNumber) {
                $query->where('phone_number', $phoneNumber);
                $query->orWhere('mobile_number', $phoneNumber);
                $query->orWhere('fax_number', $phoneNumber);
            })
           // ->where([
           //     'contact_type' => BexioEmployee::CONTACT_TYPE_EMPLOYEE
           // ])
            ->first();

            if ($bexioEmployee instanceof BexioEmployee) {
                $currentCallName = $bexioEmployee->name . " ($currentCallName)";

                $companies = BexioEmployee::whereIn(
                    'emp_id',
                    BexioEmployeeHasCompany::where(
                        'bexio_employee_id',
                        $bexioEmployee->emp_id
                    )
                    ->select('bexio_company_id')
                )
                ->where('contact_type', BexioEmployee::CONTACT_TYPE_COMPANY);

                $currentCallName = ($companies->count() > 0) ?
                    $currentCallName . ' (' . __('Company') . ': ' . $companies->first()->name . ')' :
                    $currentCallName;

                $bexioUrl = "https://office.bexio.com/index.php/kontakt/show/id/{$bexioEmployee->emp_id}";
                $currentCallName .= ' <a target="_blank" href="' . $bexioUrl . '" class="btn btn-primary">' .
                    __('Bexio') . '</a>';

                if (config('global.CLOCKODO_API_KEY', '') && $clockoDoUserId) {
                    $currentCallName .= ' <a href="javascript:void(0);" data-logurl="' .
                    route('employee.call.log.global-popup', [
                        'ongoing_call_id' => $currentCall->id,
                    ]) . '" class="btn btn-primary log-time-popup">' .
                    __('Log Time') . '</a>';
                }
            } else {
                if ($phoneNumber && !isset($foundChPhoneNumbers[$phoneNumber])) {
                    $searchChData = $searchChService->getContacts($phoneNumber);

                    if ($searchChData) {
                        $foundChPhoneNumbers[$phoneNumber] = reset($searchChData);
                    } else {
                        $currentCallName .=  ' <a target="_blank" href="' .
                        route('employee.contact.add', [
                            'ph' => base64_encode(json_encode($phoneNumber))
                        ]) . '" class="btn btn-primary">' . __('Add To Bexio') . '</a>';
                    }
                }

                if ($phoneNumber && isset($foundChPhoneNumbers[$phoneNumber])) {
                    $currentCallName = $foundChPhoneNumbers[$phoneNumber]['name'] . " ($currentCallName)" .
                        ' <a target="_blank" href="' .
                            route('employee.contact.add', [
                                'ch' => base64_encode(json_encode($foundChPhoneNumbers[$phoneNumber]))
                            ]) . '" class="btn btn-primary">' . __('Add To Bexio') . '</a>';
                }
            }
        }

        if ($lastCall instanceof UserHasOngoingCall) {
            $phoneNumber = $lastCall->phone_number;
            $lastCallName = '<a href="tel:' . $phoneNumber . '">' . $phoneNumber . '</a>';
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
                $lastCallName = $bexioEmployee->name . " ($lastCallName)";
                $bexioUrl = "https://office.bexio.com/index.php/kontakt/show/id/{$bexioEmployee->emp_id}";
                $lastCallName .= ' <a target="_blank" href="' . $bexioUrl . '" class="btn btn-dark">' .
                    __('Bexio') . '</a>';
            } else {
                if ($phoneNumber && ! isset($foundChPhoneNumbers[$phoneNumber])) {
                    $searchChData = $searchChService->getContacts($phoneNumber);

                    if ($searchChData) {
                        $foundChPhoneNumbers[$phoneNumber] = reset($searchChData);
                    }
                }

                if ($phoneNumber && isset($foundChPhoneNumbers[$phoneNumber])) {
                    $lastCallName = $foundChPhoneNumbers[$phoneNumber]['name'] . " ($lastCallName)" .
                        ' <a target="_blank" href="' .
                            route('employee.contact.add', [
                                'ch' => base64_encode(json_encode($foundChPhoneNumbers[$phoneNumber]))
                            ]) . '" class="btn btn-primary">' . __('Add To Bexio') . '</a>';
                }
            }
        }

        return view('employee.dashboard.index', [
            'title' => __('Dashboard'),
            'user' => $user,
            'bexioUrl' => $bexioUrl,
            'clockoDoUserId' => $clockoDoUserId,
            'lastCall' => $lastCall,
            'currentCall' => $currentCall,
            'currentCallName' => $currentCallName,
            'lastCallName' => $lastCallName,
        ]);
    }
}
