<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\User\PasswordUpdatedEmail;
use App\Mail\User\ResetPasswordEmail;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (auth('admin')->user()) {
            return redirect()
                ->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function proceedWithLogin(Request $request): RedirectResponse
    {
        if (auth('admin')->user()) {
            return redirect()
                ->route('admin.dashboard');
        }

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->get('email'))->first();

        if (! $admin instanceof Admin) {
            return back()
                ->withInput()
                ->with('error', __('Whoops! invalid email and password.'));
        }

        if ($admin->status === Admin::STATUS_IN_ACTIVE) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    __('Your account is currently inactive. Please contact the super administrator for assistance.')
                );
        }

        if (
            auth()->guard('admin')->attempt([
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ])
        ) {
            return redirect()->route('admin.dashboard')
                ->with('message', __('You are Logged in sucessfully.'));
        }

        return back()->with('error', __('Whoops! invalid email and password.'));
    }

    public function forgotPassword(): view|RedirectResponse
    {
        if (auth('admin')->user()) {
            return redirect()
                ->route('admin.dashboard');
        }

        return view('admin.auth.forgot_password');
    }

    public function proceedWithForgotPassword(Request $request): RedirectResponse
    {
        if (auth('admin')->user()) {
            return redirect()
                ->route('admin.dashboard');
        }

        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->get('email'))->first();

        if (! $admin instanceof Admin) {
            return back()
                ->withInput()
                ->with('error', __('Whoops! your account not registered with us.'));
        }

        if ($admin->status === Admin::STATUS_IN_ACTIVE) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    __('Your account is currently inactive. Please contact the super administrator for assistance.')
                );
        }

        $mail = new ResetPasswordEmail($admin);
        $mail->subject(__('Reset Your Password'));

        Mail::to($admin->email)->send($mail);

        return back()->with(
            'message',
            __('A reset password link has been sent to your email address. Please check your inbox.')
        );
    }

    public function resetPassword($id, $hash, Request $request): view|RedirectResponse
    {
        if (auth('admin')->user()) {
            return redirect()
                ->route('admin.dashboard');
        }

        if (! URL::hasValidSignature($request)) {
            abort(404);
        }

        $admin = Admin::where('id', $id)->first();

        if (! $admin instanceof Admin) {
            abort(404);
        }

        return view('admin.auth.reset_password', [
            'hash' => $hash,
            'id' => $id,
        ]);
    }

    public function proceedWithResetPassword(Request $request): RedirectResponse
    {
        if (auth('admin')->user()) {
            return redirect()
                ->route('admin.dashboard');
        }

        $validated = $request->validate([
            'password' => 'required|confirmed|regex:/' . Admin::PASSWORD_REGEX . '/',
            'id' => 'required|exists:admins,id',
            'hash' => 'required',
        ], [
            'id.exists' => __('Unable to reset password.'),
            'id.required' => __('Unable to reset password.'),
            'hash.required' => __('Unable to reset password.'),
            'password.regex' => __(Admin::PASSWORD_HINT_MESSAGE),
        ]);

        $id = $request->get('id');
        $hash = $request->get('hash');
        $password = $request->get('password');

        $admin = Admin::where('id', $id)->first();

        if (! $admin instanceof Admin) {
            return back()
                ->with('error', __('Unable to reset password.'));
        }

        if (sha1($admin->email) !== $hash) {
            return back()
                ->with('error', __('Unable to reset password.'));
        }

        $password = Hash::make($password);
        $admin->password = $password;
        $admin->save();

        $mail = new PasswordUpdatedEmail($admin);
        $mail->subject(__('Your password has been successfully updated.'));

        Mail::to($admin->email)->send($mail);

        return redirect()
            ->route('admin.login')
            ->with('message', __('Your password has been successfully updated.'));
    }

    public function logout(Request $request)
    {
        auth()->guard('admin')->logout();

        return redirect()
            ->route('admin.login')
            ->with('message', __('You have logged out successfully.'));
    }
}
