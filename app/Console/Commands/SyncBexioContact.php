<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\BexioEmployee;
use App\Services\BexioService;
use Illuminate\Console\Command;
use App\Services\ClockodoService;

class SyncBexioContact extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:bexio {syncNow?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Bexio Contacts';

    protected $bexioService;
    protected $clockodoService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(BexioService $bexioService, ClockodoService $clockodoService)
    {
        $this->bexioService = $bexioService;
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
        $syncNow = filter_var($this->argument('syncNow'), FILTER_VALIDATE_BOOLEAN);
        $configMinutes = config('global.BEXIO_SYNC_AFTER', 15);

        if ($configMinutes) {
            $lastSync = BexioEmployee::orderBy('updated_at', 'desc')->first();

            if ($lastSync instanceof BexioEmployee && $lastSync->updated_at) {
                $start = Carbon::parse($lastSync->updated_at);
                $end = Carbon::now();

                $lastUpdatedBefore = $end->diffInMinutes($start);

                if ($lastUpdatedBefore < $configMinutes) {
                    return $this->error(__(':minutes minutes left to sync contacts again.', ["minutes" => ($configMinutes - $lastUpdatedBefore)]));
                }
            }
        }

        return ! $this->bexioService->syncContacts($syncNow, null, $this->clockodoService) ?
            $this->error(__('Contacts not found.')) :
            $this->info(__('Contacts successfully saved.'));
    }
}
