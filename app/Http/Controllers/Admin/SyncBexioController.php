<?php

namespace App\Http\Controllers\Admin;

use App\Services\BexioService;
use App\Services\ClockodoService;
use App\Http\Controllers\Admin\AdminBaseController;

class SyncBexioController extends AdminBaseController
{
    public function contacts(BexioService $bexioService, ClockodoService $clockodoService)
    {
        return ! $bexioService->syncContacts(false, null, $clockodoService) ? redirect()->back()->with('global_error', __('Contacts not found.')) : 
        redirect()->back()->with('message', __('Contacts successfully saved.'));
    }
}
