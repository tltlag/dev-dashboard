<?php

namespace App\Console\Commands;

use App\Models\TimeLog;
use App\Models\User;
use App\Services\ClockodoService;
use Illuminate\Console\Command;

class SyncClockodoTimeLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:clockodo-time-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Clockodo Time Logs';
    protected $clockodoService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ClockodoService $clockodoService)
    {
        $this->clockodoService = $clockodoService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereNotNull('clockodo_emp_id')->cursor();
        $customers = [];
        $services = [];
        $projects = [];

        foreach ($users as $user) {
            if ($user->clockodo_emp_id <= 0) {
                continue;
            }

            $entries = $this->clockodoService->getTimeLogs($user->clockodo_emp_id);

            foreach ($entries as $entry) {
                $date = date('Y-m-d', strtotime($entry['time_since']));
                $startTime = date('H:i:s', strtotime($entry['time_since']));
                $endTime = date('H:i:s', strtotime($entry['time_until']));

                $model = TimeLog::where([
                    'user_id' => $user->id,
                    'date' => $date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ])->first();

                if (! $model instanceof TimeLog) {
                    $model = TimeLog::where([
                        'clockodo_entry_id' => $entry['id'],
                        'user_id' => $user->id,
                    ])->first();

                    if (! $model instanceof TimeLog) {
                        $model = new TimeLog();
                    }
                }

                if (isset($customers[$entry['customers_id']]) && $customers[$entry['customers_id']]) {
                    $customer = $customers[$entry['customers_id']];
                } else {
                    $customer = $this->clockodoService->getCustomer($entry['customers_id']);
                    $customers[$entry['customers_id']] = $customer;
                }

                if (isset($services[$entry['services_id']]) && $services[$entry['services_id']]) {
                    $service = $services[$entry['services_id']];
                } else {
                    $service = $this->clockodoService->getService($entry['services_id']);
                    $services[$entry['services_id']] = $service;
                }

                if (isset($projects[$entry['projects_id']]) && $projects[$entry['projects_id']]) {
                    $project = $projects[$entry['projects_id']];
                } elseif ($entry['projects_id']) {
                    $project = $this->clockodoService->getProject((int) $entry['projects_id']);
                    $projects[$entry['projects_id']] = $project;
                }

                $timeLogData = [
                    'clockodo_entry_id' => $entry['id'],
                    'user_id' => $user->id,
                    'date' => $date,
                    'duration' => $entry['duration'], // Duration in seconds
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'client_id' => $entry['customers_id'],
                    'client_name' => $customers[$entry['customers_id']]['name'] ?? '',
                    'clockodo_project_id' => $entry['projects_id'],
                    'clockodo_project_name' => $projects[$entry['projects_id']]['name'] ?? null,
                    'service_id' => $entry['services_id'],
                    'service_name' => $services[$entry['services_id']]['name'] ?? '',
                    'service_description' => $entry['text'],
                ];

                $model->fill($timeLogData);
                $model->save();
            }
        };

        return $this->info(__('Time Logs Successfully Saved.'));
    }
}
