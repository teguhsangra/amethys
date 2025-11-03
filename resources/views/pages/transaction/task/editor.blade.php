@extends('layouts.app')

@section('title')
Rakomsis Task - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">
                    Task Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Task
                    </a>
                </h4>
            </div>
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
            <div class="card-body">
                    @if($method != "PUT")
                    <div class="row" >
                        <label class="col-sm-2 col-form-label">Previous</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('previous_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="previous_id" id="previous_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                   >
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($previous as $detail)
                                            @php
                                            $selected = '';
                                            if(!empty($task)){
                                                if($task->previous_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }

                                            @endphp
                                            <option value="{{ $detail->id }}" {{$selected}}>{{ $detail->code }} - @if($detail->task_subject_id == null) {{ $detail->subject }} @else {{$detail->task_subject->name}} @endif </option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('previous_id') }}</label>
                            </div>
                        </div>
                    </div>
                    @endif
                    {{-- <div class="row" >
                        <label class="col-sm-2 col-form-label">Ticketing</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('ticketing_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="ticketing_id" id="ticketing_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                    >
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($ticketing as $detail)
                                            @php
                                            $selected = '';
                                            if(!empty($task)){
                                                if($task->ticketing_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }

                                            @endphp
                                            <option value="{{ $detail->id }}" {{$selected}}>{{ $detail->code }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('ticketing_id') }}</label>
                            </div>
                        </div>
                    </div> --}}

                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="location_id" id="location_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            @php
                                            $selected = '';
                                            if(!empty($task)){
                                                if($task->location_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }

                                            @endphp
                                            <option value="{{ $detail->id }}" {{$selected}}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('location_id') }}</label>
                            </div>
                        </div>
                    </div>
                    @if($method == "PUT")
                    <div class="row" id="employee" >
                        <label class="col-sm-2 col-form-label">Escalated</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('is_escalated') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="is_escalated" id="is_escalated"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                <label class="error">{{ $errors->first('is_escalated') }}</label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row" id="employee">
                        <label class="col-sm-2 col-form-label">Employee</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="employee_id" id="employee_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($employee as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($task)){
                                                    if($task->employee_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }

                                            @endphp
                                            <option value="{{ $detail->id }}" {{$selected}}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('employee_id') }}</label>
                            </div>
                        </div>
                    </div>



                    @if($method != "PUT")
                    <div class="row" id="template">
                        <label class="col-sm-2 col-form-label">Template Subject</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                                <select class="selectpicker form-control" name="template" id="templates"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                >
                                    <option value="" disabled selected>Select Your Option</option>
                                    <option value="Y">With Template Subject</option>
                                    <option value="N">Custom Subject</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif


                    <div class="row" id="template_subject" style="display:none;">
                        <label class="col-sm-2 col-form-label">Task Subject</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('task_subject_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="task_subject_id" id="task_subject_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                    >
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($task_subject as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($task)){
                                                    if($task->task_subject_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }

                                            @endphp
                                            <option value="{{ $detail->id }}" {{$selected}}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('task_subject_id') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="subjects" style="display:none;">
                        <label class="col-sm-2 col-form-label">Subject</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                                <input type="text" name="subject" class="form-control" @if(!empty($task)) value="{{$task->subject}}" @else value="{{old('subject')}}" @endif>
                                <label class="error">{{ $errors->first('subject') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" >
                        <label class="col-sm-2 col-form-label">Estimate Date</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('estimated_done_at') ? ' has-error' : '' }}">
                                <input type="text" name="estimated_done_at" id="estimated_done_at" class="form-control datepicker text-center" placeholder="Estimate Date" @if(!empty($task)) value="{{ date('m/d/Y', strtotime($task->estimated_done_at)) }}" @endif>
                                <label class="error">{{ $errors->first('estimated_done_at') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." >@if(!empty($task)) {!! $task->remarks !!} @else {!! old('remarks') !!} @endif</textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
                            </div>
                        </div>
                    </div>


            </div>
            <div class="card-footer">
                <div class="col-md-12 text-center">
                    <a href="{{ url('ticketing') }}" class="col-md-4  btn-lg btn btn-warning">Back</a>
                    <button type="submit" class="col-md-6 btn btn-lg btn-primary">Send</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
@if(!empty($task))
    @if($task->task_subject_id != null)
    $('#template_subject').show();
    @else
    $('#subjects').show();
    @endif

@endif

$(function() {
    $("#templates").change(function() {

        formSubject();
    });
});
function formSubject(){
    var type = document.getElementById("templates").value;

    if(type == ''){
        $('#template_subject').hide();
        $('#subjects').hide();
    }else if(type == "Y"){
        $('#template_subject').show();
        $('#subjects').hide();
    }else if(type == "N"){
        $('#template_subject').hide();
        $('#subjects').show();
    }else{
        $('#template_subject').hide();
        $('#subjects').hide();
    }
}
</script>
@endsection
