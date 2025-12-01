<?php

namespace App\Jobs;

use App\Services\BexioService;
use App\Services\ClockodoService;
use App\Services\WildixinService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncBexioContacts implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected ?int $empId = null;

    public function __construct(int $empId)
    {
        $this->empId = $empId;
    }

    public function handle(
        BexioService $bexioService,
        WildixinService $wildixinService,
        ClockodoService $clockodoService
    ) {
        ini_set('max_execution_time', 0);

        if (! $return = $bexioService->syncContacts(true, $wildixinService, $clockodoService, $this->empId)) {
            Log::error('Contacts not found.');
        } else {
            Log::info('Contacts successfully saved.');
        }

        dump($return);

        return $return;
    }
}
