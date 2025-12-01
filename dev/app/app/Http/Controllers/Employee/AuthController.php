<?php

namespace App\Http\Controllers\Employee;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Jobs\EndCall;
use App\Models\Configuration;
use App\Models\User;
use App\Models\UserHasOngoingCall;
use App\Services\ClockodoService;
use App\Services\WildixinService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    protected int $role = User::ROLE_TYPE_EMPLOYEE;

    public function login(): view | RedirectResponse
    {
        if (auth('employee')->user()) {
            return redirect()
                ->route('employee.dashboard');
        }

        return view('employee.auth.login', []);
    }

    public function wildixinLogin($userId, WildixinService $wildixinService, Request $request)
    {
        try {
            $userData = $wildixinService->getColleagueById($userId);
            $user = User::firstOrNew([
                'wildixin_id' => $userData['wildixin_id'],
            ]);

            if (! $user->password) {
                $user->password = Hash::make(Str::random(10));
            }

            $user->fill([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'username' => $userData['email'],
                'last_login' => date('Y-m-d H:i:s'),
                'role' => User::ROLE_TYPE_EMPLOYEE,
                'status' => 1,
                'approved' => User::APPROVED_STATUS_APPROVED,
                'wildixin_response' => $userData['wildixin_response'],
            ]);

            if (! ($user instanceof User && $user->save())) {
                return redirect()
                    ->route('home')
                    ->withErrors(__('Unable to setup employee.'));
            }

            $redirectOption = [];

            if (CommonHelper::isIframeRequest($request)) {
                $user = User::generateTeleApiToken($user->id);

                if (! ($user instanceof User && $user->tele_api_token)) {
                    return redirect()
                        ->route('home')
                        ->withErrors(__('Unable to log in.'));
                }

                $redirectOption['tele_api_token'] = $user->tele_api_token;
            } else {
                if (! Auth::guard('employee')->login($user)) {
                    return redirect()
                        ->route('home')
                        ->withErrors(__('Unable to log in.'));
                }
            }

            return redirect()
                ->route('employee.dashboard', $redirectOption)
                ->with('message', __('You are Logged in sucessfully.'));
        } catch (Exception $e) {
            return redirect()
                ->route('home')
                ->withErrors($e->getMessage());
        }

        //http://intreation.local/auth/wildixin/3139257
    }

    public function wildixinExtensionLogin(
        $extensionId,
        WildixinService $wildixinService,
        ClockodoService $clockodoService,
        Request $request
    ) {
        //http://intreation.local/auth/wildixin/extension/12
        try {
            $userData = $wildixinService->getColleagueByExtensionId($extensionId);
            $user = User::firstOrNew([
                'wildixin_id' => $userData['wildixin_id'],
            ]);

            if (! $user->password) {
                $user->password = Hash::make(Str::random(10));
            }

            $employees = $clockodoService->getUsers();
            $employees = $employees ? collect($employees)->pluck('email', 'id')->toArray() : [];
            $userClockodoId = 0;
            if ($clockoDoId = array_search($userData['email'], $employees)) {
                $userClockodoId = $clockoDoId;
            }

            $user->fill([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'username' => $userData['email'],
                'last_login' => date('Y-m-d H:i:s'),
                'extension_number' => $extensionId,
                'role' => User::ROLE_TYPE_EMPLOYEE,
                'status' => 1,
                'approved' => User::APPROVED_STATUS_APPROVED,
                'wildixin_response' => $userData['wildixin_response'],
                'clockodo_emp_id' => $userClockodoId
            ]);

            if (! ($user instanceof User && $user->save())) {
                return redirect()
                    ->route('home')
                    ->withErrors(__('Unable to setup employee.'));
            }

            $redirectOption = [];

            if (CommonHelper::isIframeRequest($request)) {
                $user = User::generateTeleApiToken($user->id);

                if (! ($user instanceof User && $user->tele_api_token)) {
                    return redirect()
                        ->route('home')
                        ->withErrors(__('Unable to log in.'));
                }

                $redirectOption['tele_api_token'] = $user->tele_api_token;
            } else {
                if (! Auth::guard('employee')->login($user)) {
                    return redirect()
                        ->route('home')
                        ->withErrors(__('Unable to log in.'));
                }
            }

            return redirect()
                ->route('employee.dashboard', $redirectOption)
                ->with('message', __('You are Logged in sucessfully.'));
        } catch (Exception $e) {
            return redirect()
                ->route('home')
                ->withErrors($e->getMessage());
        }

        //http://intreation.local/auth/wildixin/3139257
    }

    public function ongoingCallLogin(
        $extensionId,
        $phoneNumber,
        WildixinService $wildixinService,
        ClockodoService $clockodoService,
        Request $request
    ) {
        //http://intreation.local/auth/wildixin/extension/12
        try {
            $userData = $wildixinService->getColleagueByExtensionId($extensionId);
            $user = User::firstOrNew([
                'wildixin_id' => $userData['wildixin_id'],
            ]);

            if (! $user->password) {
                $user->password = Hash::make(Str::random(10));
            }

            $employees = $clockodoService->getUsers();
            $employees = $employees ? collect($employees)->pluck('email', 'id')->toArray() : [];
            $userClockodoId = 0;
            if ($clockoDoId = array_search($userData['email'], $employees)) {
                $userClockodoId = $clockoDoId;
            }

            $user->fill([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'username' => $userData['email'],
                'last_login' => date('Y-m-d H:i:s'),
                'extension_number' => $extensionId,
                'role' => User::ROLE_TYPE_EMPLOYEE,
                'status' => 1,
                'approved' => User::APPROVED_STATUS_APPROVED,
                'wildixin_response' => $userData['wildixin_response'],
                'clockodo_emp_id' => $userClockodoId
            ]);

            if (! ($user instanceof User && $user->save())) {
                return redirect()
                    ->route('home')
                    ->withErrors(__('Unable to setup employee.'));
            }

            $query = UserHasOngoingCall::where('user_id', $user->id);

            if ($query->count() > 0) {
                $query->update([
                    'status' => 1,
                ]);
            }

            $call = UserHasOngoingCall::create([
                'user_id' => $user->id,
                'extension_number' => $extensionId,
                'phone_number' => $phoneNumber,
                'status' => 0,
            ]);

            if (! $call instanceof UserHasOngoingCall) {
                return redirect()
                    ->route('home')
                    ->withErrors(__('Unable to loggedin.'));
            }

            // EndCall::dispatch($call->id)
            //     ->delay(
            //         now()->addMinutes(config('global.CALL_FORCEFUL_END_AFTER', Configuration::CALL_FORCEFUL_END_AFTER))
            //     );
            $redirectOption = [];

            if (CommonHelper::isIframeRequest($request)) {
                $user = User::generateTeleApiToken($user->id);

                if (! ($user instanceof User && $user->tele_api_token)) {
                    return redirect()
                        ->route('home')
                        ->withErrors(__('Unable to log in.'));
                }

                $redirectOption['tele_api_token'] = $user->tele_api_token;
            } else {
                if (! Auth::guard('employee')->login($user)) {
                    return redirect()
                        ->route('home')
                        ->withErrors(__('Unable to log in.'));
                }
            }

            return redirect()
                ->route('employee.dashboard', $redirectOption)
                ->with('message', __('You are Logged in sucessfully.'));
        } catch (Exception $e) {
            return redirect()
                ->route('home')
                ->withErrors($e->getMessage());
        }

        //http://intreation.local/auth/wildixin/3139257
    }

    public function logout(Request $request)
    {
        $emp = auth()->guard('employee')->user();
        $emp->tele_api_token = null;
        $emp->save();

        auth()->guard('employee')->logout();

        return redirect()
            ->route('home')
            ->with('message', __('You have logged out successfully.'));
    }
}
