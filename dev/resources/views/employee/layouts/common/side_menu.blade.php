@php
global $loggedUser;
global $dashboardRouteGroup;

$loggedUser = null;
$dashboardRouteGroup = null;

if (auth('employee')->user()) {
$loggedUser = auth('employee')->user();
$dashboardRouteGroup = 'employee.';
}
@endphp

<!-- ======== sidebar-nav start =========== -->
<aside class="sidebar-nav-wrapper">
    <div class="navbar-logo">
        @if (config('global.SITE_LOGO'))
        <a href="{{route('employee.dashboard')}}">
            <img src="{{ route('images.show', basename(config('global.SITE_LOGO'))) }}"
                alt="{{ config('global.SITE_TITLE', '') }}" title="{{ config('global.SITE_TITLE', '') }}"
                class="w-100" />
        </a>
        @endif
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item @if(request()->is('employee/dashboard')) active @endif">
                <a href="{{route('employee.dashboard')}}">
                    <span class="icon">
                        <svg width="30" height="30" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.74999 18.3333C12.2376 18.3333 15.1364 15.8128 15.7244 12.4941C15.8448 11.8143 15.2737 11.25 14.5833 11.25H9.99999C9.30966 11.25 8.74999 10.6903 8.74999 10V5.41666C8.74999 4.7263 8.18563 4.15512 7.50586 4.27556C4.18711 4.86357 1.66666 7.76243 1.66666 11.25C1.66666 15.162 4.83797 18.3333 8.74999 18.3333Z" />
                            <path
                                d="M17.0833 10C17.7737 10 18.3432 9.43708 18.2408 8.75433C17.7005 5.14918 14.8508 2.29947 11.2457 1.75912C10.5629 1.6568 10 2.2263 10 2.91665V9.16666C10 9.62691 10.3731 10 10.8333 10H17.0833Z" />
                        </svg>
                    </span>
                    <span class="text">{{__('Dashboard')}}</span>
                </a>
            </li>

            <!-- Divider -->
            <span class="divider">

            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item @if(request()->is('employee/contact*')) active @endif">
                <a class="@if(! request()->is('employee/contact*')) collapsed @endif"
                    href="{{route('employee.contacts')}}">
                    <span class="icon">
                        <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M11 14H9C9 9.03 13.03 5 18 5V7C14.13 7 11 10.13 11 14M18 11V9C15.24 9 13 11.24 13 14H15C15 12.34 16.34 11 18 11M7 4C7 2.89 6.11 2 5 2S3 2.89 3 4 3.89 6 5 6 7 5.11 7 4M11.45 4.5H9.45C9.21 5.92 8 7 6.5 7H3.5C2.67 7 2 7.67 2 8.5V11H8V8.74C9.86 8.15 11.25 6.5 11.45 4.5M19 17C20.11 17 21 16.11 21 15S20.11 13 19 13 17 13.89 17 15 17.89 17 19 17M20.5 18H17.5C16 18 14.79 16.92 14.55 15.5H12.55C12.75 17.5 14.14 19.15 16 19.74V22H22V19.5C22 18.67 21.33 18 20.5 18Z" />
                        </svg>
                    </span>
                    <span>{{ __('Contacts') }}</span>
                </a>
            </li>

            <!-- Divider -->
            <span class="divider">

            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item @if(request()->is('employee/call/history*')) active @endif">
                <a class="@if(! request()->is('employee/call/history*')) collapsed @endif"
                    href="{{route('employee.call.history')}}">
                    <span class="icon">
                        <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M13.5,8H12V13L16.28,15.54L17,14.33L13.5,12.25V8M13,3A9,9 0 0,0 4,12H1L4.96,16.03L9,12H6A7,7 0 0,1 13,5A7,7 0 0,1 20,12A7,7 0 0,1 13,19C11.07,19 9.32,18.21 8.06,16.94L6.64,18.36C8.27,20 10.5,21 13,21A9,9 0 0,0 22,12A9,9 0 0,0 13,3" />
                        </svg>
                    </span>
                    <span>{{ __('Call History') }}</span>
                </a>
            </li>

            <!-- Divider -->
            <span class="divider">

            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item @if(request()->is('emploee/sync/bexio/contacts')) active @endif">
                <a class="@if(! request()->is('emploee/sync/bexio/contacts')) collapsed @endif"
                    href="{{route('employee.sync.bexio.contacts')}}">
                    <span class="icon">
                        <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M12,18A6,6 0 0,1 6,12C6,11 6.25,10.03 6.7,9.2L5.24,7.74C4.46,8.97 4,10.43 4,12A8,8 0 0,0 12,20V23L16,19L12,15M12,4V1L8,5L12,9V6A6,6 0 0,1 18,12C18,13 17.75,13.97 17.3,14.8L18.76,16.26C19.54,15.03 20,13.57 20,12A8,8 0 0,0 12,4Z" />
                        </svg>
                    </span>
                    <span>{{ __('Sync All') }}</span>
                </a>
            </li>
            <!-- Divider -->
            <span class="divider">

            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item @if(request()->is('employee/call/logs*')) active @endif">
                <a class="@if(! request()->is('employee/call/logs*')) collapsed @endif"
                    href="{{route('employee.call.logs')}}">
                    <span class="icon">
                        <svg width="30" height="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M20.5 3H3.5C2.67 3 2 3.67 2 4.5V19.5C2 20.33 2.67 21 3.5 21H20.5C21.33 21 22 20.33 22 19.5V4.5C22 3.67 21.33 3 20.5 3ZM20 19H4V5H20V19ZM6 7H18V9H6V7ZM6 11H18V13H6V11ZM6 15H14V17H6V15Z"/>
                        </svg>
                    </span>
                    <span>{{ __('Work Logs') }}</span>
                </a>
            </li>

            <!-- Divider -->
            <span class="divider">

            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item  ">
                <a href="{{route('employee.logout')}}">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"
                            fill="currentColor">
                            <path
                                d="M16.24 11H9.5a1 1 0 0 1 0-2h6.74l-1.55-1.54a1 1 0 0 1 1.41-1.42l3.27 3.27a1 1 0 0 1 0 1.42l-3.27 3.27a1 1 0 1 1-1.41-1.42L16.24 11z" />
                            <path
                                d="M12 2a1 1 0 0 0-1 1v10H6a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h5v6a1 1 0 0 0 2 0v-6h5a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1h-5V3a1 1 0 0 0-1-1z" />
                        </svg>
                    </span>
                    <span>{{__('Sign Out')}}</span>
                </a>
            </li>
            <!-- Divider -->
            <span class="divider">

            </span>
        </ul>
    </nav>
</aside>
<div class="overlay"></div>
<!-- ======== sidebar-nav end =========== -->