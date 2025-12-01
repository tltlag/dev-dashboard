<?php

namespace App\Http\Middleware;

use App\Helpers\CommonHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AppendTokenToUrls
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
        if (CommonHelper::isIframeRequest($request)) {
            $token = auth('employee')->check() ? auth('employee')->user()->tele_api_token : null;

            if ($token) {
                URL::defaults(['tele_api_token' => $token]);
            }
        }

        return $next($request);
    }
}
