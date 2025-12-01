<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Employee\EmployeeBaseController;
use App\Jobs\SyncBexioContacts;
use App\Models\BexioEmployee;
use App\Services\BexioService;
use App\Services\ClockodoService;
use App\Services\WildixinService;
use Illuminate\Http\Request;

class SyncBexioController extends EmployeeBaseController
{
    public function contacts(
        BexioService $bexioService,
        WildixinService $wildixinService,
        ClockodoService $clockodoService
    ) {
        return ! $bexioService->syncContacts(true, $wildixinService, $clockodoService) ?
        redirect()->back()->with('global_error', __('Unable to sync.')) :
        redirect()->back()->with('global_message', __('Contacts and call history successfully saved.'));
    }

    // public function contacts()
    // {
    //     // Dispatch the job to handle the sync process
    //     SyncBexioContacts::dispatch(auth('employee')->user()->id);

    //     // Return a message to the user that the sync is queued
    //     return redirect()->back()->with('global_message', __('Contacts and call history will sync soon.'));
    // }

    public function companyPopup()
    {
        return view(
            'employee.bexio.company-form',
            []
        );
    }

    public function addCompany(Request $request, BexioService $bexioService, ClockodoService $clockodoService)
    {
        $request->validate([
            'nt_name_1' => 'required',
            'nt_name_2' => 'required',
        ], [
            'nt_name_1.required' => __('Please enter customer first name'),
            'nt_name_2.required' => __('Please enter customer last name'),
        ]);

        $firstName = $request->get('nt_name_1', null);
        $lastName = $request->get('nt_name_2', null);
        $phone = $request->get('nt_phone', null);
        $mobile = $request->get('nt_mobile', null);
        $fax = $request->get('nt_fax', null);

        if (! $phone && ! $mobile && ! $fax) {
            return response()->json([
                'error' => [
                    'message' => __('Please enter at least one customer number.'),
                ]
            ]);
        }

        $owner = $bexioService->getOwner();

        if (! $owner) {
            return response()->json([
                'error' => [
                    'message' => __('Unable to save customer.'),
                ]
            ]);
        }

        $data = [
            'contact_type_id' => BexioEmployee::CONTACT_TYPE_COMPANY,
            'name_1' => $firstName,
            'name_2' => $lastName,
            'phone_fixed' => $phone,
            'phone_mobile' => $mobile,
            'fax' => $fax,
            'user_id' => $owner['id'],
            'owner_id' => $owner['id'],
        ];

        $customer = $bexioService->saveCustomer($data);

        if (! $customer) {
            return response()->json([
                'error' => [
                    'message' => __('Unable to save customer.'),
                ]
            ]);
        }

        $bexioService->syncContacts(false, null, $clockodoService);

        return response()->json([
            'success' => [
                'message' => __('Customer has been successfully saved.'),
                'owner' => $owner,
                'customer' => $customer,
            ]
        ]);
    }
}
