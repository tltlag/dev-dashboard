<div class="modal-header">
    <h5 class="modal-title" id="log-time-label">{{__('Create Customer')}}</h5>
</div>
<div class="modal-body">
    <div class="container mt-5">
        <form class="bc-form" action="{{route('employee.bexio.company.add')}}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_1" class="form-label">{{__('First Name')}}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nt_name_1" id="name_1" tabindex="1" require />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">{{__('Phone')}}</label>
                        <input type="text" class="form-control" name="nt_phone" id="phone" tabindex="3" require />
                    </div>
                    <div class="mb-3">
                        <label for="fax" class="form-label">{{__('Fax')}}</label>
                        <input type="text" class="form-control" id="fax" name="nt_fax" tabindex="5" require />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name_2" class="form-label">{{__('Last Name')}}</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nt_name_2" id="name_2" tabindex="2" require />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="mobile" class="form-label">{{__('Mobile')}}</label>
                        <input type="text" class="form-control" id="mobile" name="nt_mobile" tabindex="3" require />
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary pull-left" data-bs-dismiss="modal">{{__('Close')}}</button>
            <button type="submit" class="btn btn-primary " style="float:right">{{__('Submit')}}</button>
        </form>
    </div>
    <!-- Content will be loaded here -->
</div>