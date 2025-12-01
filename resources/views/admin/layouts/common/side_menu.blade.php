<!-- ======== sidebar-nav start =========== -->
<aside class="sidebar-nav-wrapper">
    <div class="navbar-logo">
        @if (config('global.SITE_LOGO'))
            <a href="{{route('employee.dashboard')}}">
                <img src="{{ route('images.show', basename(config('global.SITE_LOGO'))) }}" alt="{{ config('global.SITE_TITLE', '') }}" title="{{ config('global.SITE_TITLE', '') }}" class="w-100" />
            </a>
        @endif
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item @if(request()->is('admin')) active @endif">
                <a href="{{route('admin.dashboard')}}">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <hr />
            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item nav-item-has-children @if(request()->is('admin/user*')) active @endif">
                <a class="@if(! request()->is('admin/user*')) collapsed @endif" href="#collapse-users"
                    data-bs-toggle="collapse" aria-controls="#collapse-users" data-toggle="collapse">
                    <span class="icon">
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                        </svg>
                    </span>
                    <span>{{ __('Users') }}</span>
                </a>

                <ul id="collapse-users" class="collapse dropdown-nav @if(request()->is('admin/user*')) show @endif">
                    <li>
                        <a class="@if(request()->is('admin/user/employee*')) active @endif"
                           href="{{route('admin.user.employee.index')}}">{{ __('Employees') }}</a>
                    </li>
                </ul>
            </li>

            <!-- Divider -->
            <span class="divider">
                <hr />
            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item nav-item-has-children @if(request()->is('admin/configuration*')) active @endif">
                <a class="@if(! request()->is('admin/configuration*')) collapsed @endif"
                    href="#collapse-config" data-bs-toggle="collapse" aria-controls="#collapse-config"
                    data-toggle="collapse">
                    <span class="icon">
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
                        </svg>
                    </span>
                    <span>{{ __('Configuration') }}</span>
                </a>

                <ul id="collapse-config" class="collapse dropdown-nav @if(request()->is('admin/configuration*')) show @endif">
                    <!--h6 class="collapse-header">Login Screens:</h6-->
                    @foreach(\App\Models\Configuration::getGroupListOptions() as $key => $config)
                    <li>
                        <a class="@if(request()->is('admin/configuration/' . strtolower($key))) active @endif"
                            href="{{route('admin.configuration', [strtolower($key)])}}">{{ $config }}</a>
                    </li>
                    @endforeach
                    <!--div class="collapse-divider"></div-->
                </ul>
            </li>

            <!-- Divider -->
            <span class="divider">
                <hr />
            </span>

            <!-- Nav Item - Tables -->
            <li class="nav-item @if(request()->is('admin/translation*')) active @endif">
                <a class="@if(! request()->is('admin/translation*')) collapsed @endif"
                    href="{{route('admin.translations.index')}}">
                    <span class="icon">
                        <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path d="M11 1H3C1.9 1 1 1.9 1 3V15L4 12H9V11C9 8.8 10.79 7 13 7V3C13 1.9 12.1 1 11 1M11 4L9.5 4C9.16 5.19 8.54 6.3 7.68 7.26L7.66 7.28L8.92 8.53L8.55 9.54L7 8L4.5 10.5L3.81 9.77L6.34 7.28C5.72 6.59 5.22 5.82 4.86 5H5.85C6.16 5.6 6.54 6.17 7 6.68C7.72 5.88 8.24 4.97 8.57 4L3 4V3H6.5V2H7.5V3H11V4M21 9H13C11.9 9 11 9.9 11 11V18C11 19.1 11.9 20 13 20H20L23 23V11C23 9.9 22.1 9 21 9M19.63 19L18.78 16.75H15.22L14.38 19H12.88L16.25 10H17.75L21.13 19H19.63M17 12L18.22 15.25H15.79L17 12Z" />
                        </svg>
                    </span>
                    <span>{{ __('Translations') }}</span>
                </a>
            </li>

            <!-- Divider -->
            <span class="divider">
                <hr />
            </span>
        </ul>
    </nav>
</aside>
<div class="overlay"></div>
<!-- ======== sidebar-nav end =========== -->