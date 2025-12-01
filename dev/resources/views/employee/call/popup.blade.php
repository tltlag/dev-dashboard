<section class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card-style settings-card-2">
                <h5 class="modal-title mb-2" id="log-time-label">{{__('Log Time')}}</h5>
                <form class="nt-form" action="{{route('employee.call.log.store', [$id])}}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">{{__('Date')}}</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="nt_date" id="date" tabindex="1"
                                        value="{{ isset($callHistory['start']) ? date('Y-m-d', strtotime($callHistory['start'])) : '' }}"
                                        required />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="startTime" class="form-label">{{__('Start Time')}}</label>
                                <input type="text" class="form-control" name="nt_start_time" id="startTime" tabindex="3"
                                    value="{{ isset($callHistory['start']) ? date('H:i', strtotime($callHistory['start'])) : '' }}"
                                    placeholder="hh:mm" required>
                            </div>
                            <div class="mb-3">
                                <label for="client" class="form-label">{{__('Client')}}</label>
                                <select class="form-select" data-url="{{route('employee.clockodo.projects')}}" id="client"
                                    name="nt_client" tabindex="5" required>
                                    <option value="">{{__('Choose...')}}</option>
                                    @foreach($customers as $customer)
                                    <option value="{{$customer['id']}}"
                                        {{isset($callHistory['company_name']) && $callHistory['company_name'] == $customer['name'] ? ' selected' : ''}}>
                                        {{$customer['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="service" class="form-label">{{__('Service')}}</label>
                                <select class="form-select" id="service" name="nt_service" tabindex="7" required>
                                    <option value=''>{{__('Choose...')}}</option>
                                    @foreach($services as $service)
                                    <option value="{{$service['id']}}"
                                        {{strtolower($service['name']) == 'support' ? ' selected' : ''}}>{{$service['name']}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="duration" class="form-label">{{__('Duration')}}</label>
                                <input type="text" class="form-control" id="duration" name="nt_duration"
                                    value="{{ isset($callHistory['duration']) ? App\Helpers\CommonHelper::convertSecondsToTime($callHistory['duration']) : '' }}"
                                    tabindex="2" placeholder="hh:mm" required />
                            </div>
                            <div class="mb-3">
                                <label for="endTime" class="form-label">{{__('End Time')}}</label>
                                <input type="text" class="form-control" id="endTime" name="nt_end_time"
                                    value="{{ isset($callHistory['end']) ? date('H:i', strtotime($callHistory['end'])) : '' }}"
                                    placeholder="hh:mm" tabindex="4" required />
                            </div>
                            <div class="mb-3">
                                <label for="project-id" class="form-label">{{__('Projects')}}</label>
                                <select class="form-select" id="project-id" name="nt_project_id" tabindex="6">
                                    <option value=''>{{__('Choose...')}}</option>
                                    @foreach($projects as $project)
                                    <option value="{{$project['id']}}">{{$project['name']}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="serviceDescription" class="form-label">{{__('Service Description')}}</label>
                            <textarea class="form-control" id="serviceDescription" name="nt_service_desc" tabindex="8" rows="3"
                                required></textarea>
                        </div>
                    </div>
                    <br />
                    <input type="hidden" name="service_name" value="" />
                    <input type="hidden" name="client_name" value="" />
                    <input type="hidden" name="project_name" value="" />
                    <button type="button" class="btn btn-secondary pull-left" data-bs-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary " style="float:right">{{__('Submit')}}</button>
                </form>
            </div>
        </div>
    </div>
</section>