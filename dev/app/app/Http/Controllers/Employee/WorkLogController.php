<?php

namespace App\Http\Controllers\Employee;

use App\Helpers\CommonHelper;
use App\Services\ClockodoService;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkLogController extends EmployeeBaseController
{
    public function workLog(Request $request, ClockodoService $clockodoService)
    {
        $year = $request->get('year', Carbon::now()->year);

        // dd($clockodoService->getUsers());
        $user = auth('employee')->user();
        $clockodoId = $user->clockodo_emp_id;
        // $clockodoId = 350761;
        $error = null;

        if ($clockodoId) {
            try {
                $userReports = $clockodoService->getUserReports($clockodoId, [
                    'year' => $year,
                    'type' => 3,
                ]);

                // $entries = $clockodoService->getTimeLogs($clockodoId, "2024-09-15 00:00:00", "2024-09-15 23:59:59");
                // $worktimes = $clockodoService->getWorkTimeReports("2024-09-15", "2024-09-15", $clockodoId);
                // dump("Entries", $entries);
                // dump("Work Times", $worktimes);
                // dd("User Reports", $userReports['month_details'][8]['week_details'][2]['day_details'][6]);
            } catch (RequestException $e) {
                $userReports = [];
                $messageArray = json_decode($e->getResponse()->getBody()->getContents(), true);
                $error = $messageArray['error']['message'] ?? __('An error occurred while fetching the work logs');
            } catch (\Exception $e) {
                $error = $e->getMessage();
                $userReports = [];
            }
        } else {
            $error = __('An error occurred while fetching the work logs');
            $userReports = [];
        }

        return view('employee.work-logs.cal', [
            'user' => $user,
            'year' => $year,
            'clockodoId' => $clockodoId,
            'userReports' => $userReports,
            'error' => $error,
            'title' => __('Work Logs'),
        ]);
    }

    public function workLogEntries(Request $request, ClockodoService $clockodoService)
    {
        $date = $request->get('date', null);
        $error = __('An error occurred while fetching the work log entries.');

        $user = auth('employee')->user();
        $clockodoId = $user->clockodo_emp_id;
        // $clockodoId = 350761;
        $error = null;

        if (! $clockodoId) {
            return response()->json([
                'status' => false,
                'error' => $error,
            ]);
        }

        if (! $date) {
            return response()->json([
                'status' => false,
                'error' => __('Please select the date.'),
            ]);
        }

        $timeSince = Carbon::parse($date . ' 00:00:00')->format('Y-m-d H:i:s');
        $timeUntil = Carbon::parse($date . ' 23:59:59')->format('Y-m-d H:i:s');

        try {
            $options = [];
            $optionText = '';
            $entries = $clockodoService->getTimeLogs($clockodoId, $timeSince, $timeUntil);
            $customers = [];
            $services = [];
            $projects = [];

            foreach ($entries as & $entry) {
                if (isset($customers[$entry['customers_id']]) && $customers[$entry['customers_id']]) {
                    $customer = $customers[$entry['customers_id']];
                } else {
                    $customer = $clockodoService->getCustomer($entry['customers_id']);
                    $customers[$entry['customers_id']] = $customer;
                }

                if (isset($services[$entry['services_id']]) && $services[$entry['services_id']]) {
                    $service = $services[$entry['services_id']];
                } else {
                    $service = $clockodoService->getService($entry['services_id']);
                    $services[$entry['services_id']] = $service;
                }

                if (isset($projects[$entry['projects_id']]) && $projects[$entry['projects_id']]) {
                    $project = $projects[$entry['projects_id']];
                } elseif ($entry['projects_id']) {
                    $project = $clockodoService->getProject((int) $entry['projects_id']);
                    $projects[$entry['projects_id']] = $project;
                }

                $startTime = date('H:i:s', strtotime($entry['time_since']));
                $endTime = date('H:i:s', strtotime($entry['time_until']));

                $entryString = '';

                if (isset($customer) && $customer) {
                    $entryString .= '<strong>' . $customer['name'] . '</strong>';
                }

                if (isset($project) && $project) {
                    $entryString .= ' / ' . $project['name'];
                }

                if (isset($service) && $service) {
                    $entryString .= ' / ' . $service['name'];
                }

                $entryString = trim($entryString, ' /');

                $options[] = [
                    'csp_string' => $entryString,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'text' => $entry['text'] ?? '',
                    'diff' => CommonHelper::convertSecondsToHours($entry['duration']),
                    'billable' => $entry['billable'],
                    'hourly_rate' => $entry['hourly_rate'],
                ];

                $optionText .= '<div class="border mx-2 my-2 p-2 row entry-row">';
                $optionText .= '<div class="col-md-6">';
                $optionText .= '<strong>' . $entryString . '</strong><br>';
                $optionText .= '<span>' . ($entry['text'] ?? '') . '</span>';
                $optionText .= '</div>';

                if ($entry['billable'] && $entry['hourly_rate']) {
                    $calculatedRate = $entry['hourly_rate'] * ($entry['duration'] / 3600);
                    $optionText .= '<div class="col-md-3 text-center text-success">';
                    $optionText .= '<strong>€' . number_format($calculatedRate, 2) . '</strong><br>';
                    $optionText .= '<span>€' . $entry['hourly_rate'] . '/' . __('h') . '</span>';
                    $optionText .= '</div>';
                    $optionText .= '<div class="col-md-3 text-end">';
                } else {
                    $optionText .= '<div class="col-md-6 text-end">';
                }

                $optionText .= '<strong>' . CommonHelper::convertSecondsToHours($entry['duration']) . '</strong><br>';
                $optionText .= '<span class="text-danger fs-13"><i class="fas fa-clock"></i> ' . $startTime . ' - ' . $endTime . '</span>';
                $optionText .= '</div>';
                $optionText .= '</div>';
            }

            return response()->json([
                'status' => true,
                'entries' => $entries,
                'options' => $options,
                'optionText' => count($entries) === 0 ?
                    '<div class="alert alert-warning">' .
                    __('There are no entries') .
                    '</div>' : $optionText,
            ]);
        } catch (RequestException $e) {
            $messageArray = json_decode($e->getResponse()->getBody()->getContents(), true);
            $error = $messageArray['error']['message'] ?? $error;
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return response()->json([
            'status' => false,
            'error' => $error,
        ]);
    }
}
