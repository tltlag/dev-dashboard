<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use App\Models\UserHasOngoingCall;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOldCallStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calls:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status of calls that are X minutes old based on config value';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the number of minutes from the config
        $minutes = config('global.CALL_FORCEFUL_END_AFTER', Configuration::CALL_FORCEFUL_END_AFTER);

        // Get the timestamp X minutes ago
        $timeLimit = Carbon::now()->subMinutes($minutes);

        // Directly update all calls that are older than X minutes and have status 'ongoing'
        $updatedCount = UserHasOngoingCall::where('status', 'ongoing')
            ->where('created_at', '<=', $timeLimit)
            ->update(['status' => 1]);

        // Output the number of updated records
        $this->info(__("Updated :count call(s) to status 1.", ['count' => $updatedCount]));

        return 0;
    }
}
