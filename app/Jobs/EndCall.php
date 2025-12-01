<?php

namespace App\Jobs;

use App\Models\UserHasOngoingCall;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EndCall implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $callId;

    /**
     * Create a new job instance.
     *
     * @param int $callId
     * @return void
     */
    public function __construct($callId)
    {
        $this->callId = $callId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $call = UserHasOngoingCall::find($this->callId);

        if ($call instanceof UserHasOngoingCall && ! $call->status) {
            $call->update([
                'status' => 1,
            ]);
        }
    }
}
