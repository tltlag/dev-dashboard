@extends('employee.layouts.dashboard')

@section('title', ($title ?? null))

@push('footer_scripts')
<script>
    $(document).ready(function() {
        let selectedContactIds = [];

        function updateSelectedContactsDisplay() {
            if (selectedContactIds.length > 0) {
                $('#ids').val(selectedContactIds.map(contact => contact.emp_id).join());
            } else {
                $('#ids').val('');
            }

            // selectedContactIds.forEach(contact => {
            //     // $('#selected-contacts-list').append('<li>' + contact.text + '</li>');
            // });
        }

        $('#keywords').select2({
            placeholder: '{{__("Search contacts")}}',
            ajax: {
                url: '{{ route("employee.contact.search", [$bexioEmployee]) }}',
                dataType: 'json',
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return {
                                id: item.id, // Make sure to use the correct ID property
                                text: item.name,
                                additionalData: item
                            };
                        })
                    };
                }
            }
        });

        $('#keywords').on('select2:select', function(e) {
            var data = e.params.data.additionalData;
            selectedContactIds.push(data);
            // console.log('Selected IDs:', selectedContactIds.map(contact => contact.id));
            updateSelectedContactsDisplay();
        });

        $('#keywords').on('select2:unselect', function(e) {
            var data = e.params.data.additionalData;
            selectedContactIds = selectedContactIds.filter(contact => contact.id !== data.id);
            // console.log('Selected IDs:', selectedContactIds.map(contact => contact.id));
            updateSelectedContactsDisplay();
        });
    });
</script>
@endpush

@section('content')


<!-- ========== table components start ========== -->
<section class="table-components">
    <div class="container-fluid">
        <!-- ========== title-wrapper start ========== -->
        <div class="title-wrapper pt-30">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="title">
                        <h2>{{$title}}</h2>
                    </div>
                </div>
                <!-- end col -->
                <div class="col-md-6">
                    <div class="breadcrumb-wrapper">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{route('employee.dashboard')}}">{{__('Dashboard')}}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{route('employee.contacts')}}">{{__('Contacts')}}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{$title}}
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

        <!-- ========== tables-wrapper start ========== -->
        <div class="tables-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-style mb-30">
                        <div class="row">
                            <form id="link-frm" method="post" action="{{route('employee.contact.link', [$bexioEmployee])}}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <select id="keywords" class="form-control" multiple="multiple"></select>
                                        <input type="hidden" id="ids" name="ids" value="" />
                                        @if ($errors->has('ids'))
                                            <div class="text-danger">{{ $errors->first('ids') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">{{ __('Assign') }}</button>
                                        <a href="{{route('employee.contacts')}}" class="btn btn-dark">{{ __('Back') }}</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-wrapper table-responsive mt-3">
                            <table class="table text-dark" id="list" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Mobile') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Fax') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($contacts->count() > 0)
                                    @foreach ($contacts as $contact)
                                    <tr>
                                        <td>{{$contact->name}}</td>
                                        <td>{{$contact->email ? $contact->email : __('--NA--')}}</td>
                                        <td>{{$contact->mobile_number ? $contact->mobile_number : __('--NA--')}}</td>
                                        <td>{{$contact->phone_number ? $contact->phone_number : __('--NA--')}}</td>
                                        <td>{{$contact->fax_number ? $contact->fax_number : __('--NA--')}}</td>
                                        <td><a href="{{route('employee.contact.link.remove', [$contact->emp_id, $bexioEmployee->id])}}" class="btn btn-danger" onclick="return confirm('{{__('Are you sure?')}}');">{{__('Remove')}}</a></td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6">
                                            <p class="text-center">{{__('Contacts not linked yet.')}}</p>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Mobile') }}</th>
                                        <th>{{ __('Phone') }}</th>
                                        <th>{{ __('Fax') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection