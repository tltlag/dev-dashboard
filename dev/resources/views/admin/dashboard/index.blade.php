@extends('admin.layouts.main')

@section('title', __('Dashboard'))

@section('content')
<section class="section">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{__('Dashboard')}}</h2>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    {{__('Dashboard')}}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- ========== title-wrapper end ========== -->
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="icon-card mb-30">
                    <div class="icon purple">
                        <i class="lni lni-users"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">{{__('Total Employees')}}</h6>
                        <h3 class="text-bold mb-10">{{$totalEmployees}}</h3>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>
            <!-- End Col -->

            <div class="col-xl-3 col-lg-4 col-sm-6">
                <div class="icon-card mb-30">
                <div class="icon primary">
                        <i class="lni lni-phone"></i>
                    </div>
                    <div class="content">
                        <h6 class="mb-10">{{__('Total Calls')}}</h6>
                        <h3 class="text-bold mb-10">{{\App\Models\CallHistory::count()}}</h3>
                    </div>
                </div>
                <!-- End Icon Cart -->
            </div>

        </div>
    </div>
</section>
@endsection