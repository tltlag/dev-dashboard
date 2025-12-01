@if(session('global_message'))
    <div class="alert alert-success alert-dismissible fade show m-5" role="alert">
        {{ session('global_message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('global_error'))
    <div class="alert alert-danger alert-dismissible fade show m-5" role="alert">
        {{ session('global_error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('global_warning'))
    <div class="alert alert-warning alert-dismissible fade show m-5" role="alert">
        {{ session('global_warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('global_info'))
    <div class="alert alert-info alert-dismissible fade show m-5" role="alert">
        {{ session('global_info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif