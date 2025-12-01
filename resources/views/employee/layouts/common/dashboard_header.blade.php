<!-- ========== header start ========== -->
<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-6">
                <div class="header-left d-flex align-items-center">
                    <div class="menu-toggle-btn mr-15">
                        <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                            <i class="lni lni-chevron-left me-2"></i> {{__('Menu')}}
                        </button>
                    </div>
                    <!-- <div class="header-search d-none d-md-flex">
                        <form action="#">
                            <input type="text" placeholder="Search..." />
                            <button><i class="lni lni-search-alt"></i></button>
                        </form>
                    </div> -->
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-6">
                <div class="header-right">
                    <!-- profile start -->
                    <div class="profile-box ml-15">
                        <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-info">
                                <div class="info">
                                     <div class="image">
                                       <img class=""
                                       src="{{ auth('employee')->user()->profile_image ? route('employee.profile.image') : url('/backend-assets/images/user-default.png') }}" />
                                    </div> 
                                    <div>
                                        <h6 class="fw-500">{{ auth('employee')->user()->name }}</h6>
                                        <p>{{__('Employee')}}</p>
                                    </div>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                            <li>
                                <div class="author-info flex items-center !p-1">
                                    <!-- <div class="image">
                                       <img class=""
                                       src="{{ auth('employee')->user()->profile_image ? route('employee.profile.image') : url('/backend-assets/images/user-default.png') }}" />
                                    </div> -->
                                    <div class="content">
                                       <h4 class="text-sm">{{ auth('employee')->user()->name }}</h4>

                                       @if (auth('employee')->user()->email)
                                        <a class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white text-xs"
                                            href="#">{{ auth('employee')->user()->email }}</a>
                                       @endif
                                    </div>
                                </div>
                            </li>
                            <li>
                                @if(auth('employee')->user()->theme == 'darkTheme')
                                <a href="{{route('toggle.theme')}}">
                                    <i class="lni lni-sun"></i> {{__('Light Theme')}}
                                </a>
                                @else
                                <a href="{{route('toggle.theme')}}">
                                    <i class="lni lni-night"></i> {{__('Dark Theme')}}
                                </a>
                                @endif
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{route('employee.logout')}}"> <i class="lni lni-exit"></i> {{__('Sign Out')}} </a>
                            </li>
                        </ul>
                    </div>
                    <!-- profile end -->
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ========== header end ========== -->