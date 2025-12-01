<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateViaToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('employee')->user()) {
            return $next($request);
        }

        $token = $request->route('tele_api_token', null);

        if ($token) {
            $user = User::where('tele_api_token', $token)->first();

            if ($user) {
                auth('employee')->setUser($user);

                return $next($request);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response(__('Unauthorized.'), 401);
        } else {
            return redirect(route('home'));
        }
    }
}
