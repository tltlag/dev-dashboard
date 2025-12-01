<?php

namespace App\Http\Controllers\Admin;

use App\Services\WildixinService;

class SyncWildixController extends AdminBaseController
{
    public function callHistory($empId, WildixinService $wildixinService)
    {
        $wildixinService->syncCallHistory($empId);

        return redirect()->back()->with('message', __('Call history successfully saved.'));
    }
}
