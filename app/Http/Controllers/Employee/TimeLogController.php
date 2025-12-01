<?php

namespace App\Http\Controllers\Employee;

use App\Models\CallHistory;
use App\Models\TimeLog;
use App\Models\User;
use App\Services\ClockodoService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeLogController extends EmployeeBaseController
{
    public function getTimeLogs(Request $request)
    {
        $start = Carbon::parse($request->query('start'));
        $end = Carbon::parse($request->query('end'));

        $employee = auth('employee')->user();

        if (! $employee instanceof User) {
            return response()->json([]);
        }

        // $query = CallHistory::where('extension_id', '=', $employee->extension_number)->select('id');

        if ($start && $end) {
            // $timeLogs = TimeLog::whereIn('call_history_id', $query)
                // ->whereRaw("CONCAT(`date`, ' ', `start_time`) BETWEEN ? AND ?", [$start, $end])->get();
            $timeLogs = TimeLog::where('user_id', $employee->id)
                ->whereRaw("CONCAT(`date`, ' ', `start_time`) BETWEEN ? AND ?", [$start, $end])->get();
        } else {
            // $timeLogs = TimeLog::whereIn('call_history_id', $query)->get();
            $timeLogs = TimeLog::where('user_id', $employee->id)->get();
        }

        $events = [];
        foreach ($timeLogs as $log) {
            // dd($log->date . ' ' . $log->start_time);
            $events[] = [
                'title' => $log->client_name . ' - ' . $log->service_name,
                'start' => Carbon::parse($log->date . ' ' . $log->start_time),
                'end' => Carbon::parse($log->date . ' ' . $log->end_time),
                'description' => $log->service_description,
                'project' => $log->clockodo_project_name ? $log->clockodo_project_name : __('--NA--'),
                'delete_url' => route('employee.clockodo.log.delete', [$log]),
            ];
        }

        return response()->json($events);
    }

    public function deleteTimeLog(TimeLog $timeLog, ClockodoService $clockodoService)
    {
        if ($timeLog->user_id != auth('employee')->user()->id) {
            return redirect()->back()->with('global_error', __('Unable to delete record.'));
        }

        if (! $timeLog->clockodo_entry_id) {
            return redirect()->back()->with('global_error', __('Unable to delete record.'));
        }

        try {
            $clockodoService->deleteLogById($timeLog->clockodo_entry_id);
        } catch (Exception $e) {
            return redirect()->back()->with('global_error', __('Unable to delete record.'));
        }

        $timeLog->delete();

        return redirect()->back()->with('global_message', __('Record successfully deleted.'));
    }
}
