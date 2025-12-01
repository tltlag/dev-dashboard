<div class="col-md-12" id="cmp-wrap">
    <label for="companyDropdown" class="form-label">{{ __('Company') }}</label>
    <div class="input-group">
        <select id="companyDropdown" name="company_id" class="form-control select2">
            <option value="">{{__('Select Company')}}</option>
            @if (isset($bexioEmployeeCompany) && $bexioEmployeeCompany instanceof \App\Models\BexioEmployee)
                <option value="{{$bexioEmployeeCompany->id}}" selected>{{$bexioEmployeeCompany->name}}</option>
            @endif
        </select>
        <button type="button" class="btn btn-primary"
            onclick="addCompanyForm(this)"
            title="{{__('Add New Company')}}" style="height: 28px;padding: 0px 8px;"
            data-url="{{route('employee.contact.form.company.complete')}}">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>