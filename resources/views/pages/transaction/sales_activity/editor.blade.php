@extends('layouts.app')
@section('title')
Rakomsis Sales Activity - Editor
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
                    Sales Activity Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Sales Activity
                    </a>
                </h4>
            </div>
            <div class="card-body">
                @if(!empty($sales_activity))
                <div class="row">
                    <div class="col-md-12">
                        <h5>Prospect Info</h5>
                        <table class="table table-bordered table-hover table-success">
                            <tbody>
                                <tr>
                                    <td>Prospect</td>
                                    <td>@if(!empty($sales_activity->prospect_id)) {{$sales_activity->prospect->code}} @endif</td>
                                </tr>
                                <tr>
                                    <td>Notes</td>
                                    <td>@if(!empty($sales_activity) && !empty($sales_activity->prospect)) {!! $sales_activity->prospect->notes !!} @endif</td>
                                </tr>
                                <tr>
                                    <td>Created At</td>
                                    <td>@if(!empty($sales_activity) && !empty($sales_activity->prospect)) {{date("j F Y",strtotime($sales_activity->prospect->created_at)) }} @endif </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row" @if(!empty(Request::get('action_status'))) style="display:none" @endif>
                        <label class="col-sm-2 col-form-label">Source Form</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="source_status" value="prospect" onclick="setSourceStatus(this.value)" type="radio" @if(!empty($sales_activity)) @if($sales_activity->source_status == "prospect") checked @endif @endif> Prospect
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="source_status" value="previous_activity" onclick="setSourceStatus(this.value)" type="radio" @if(!empty($sales_activity)) @if($sales_activity->source_status == "previous_activity") checked @endif @endif> Previous Activity
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="source_status" value="existing_customer"  onclick="setSourceStatus(this.value)" type="radio" @if(!empty($sales_activity)) @if($sales_activity->source_status == "existing_customer") checked @endif @endif> Existing Customer
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-group bmd-form-group{{ $errors->has('source_status') ? ' has-error' : '' }}">
                                <label class="error">{{ $errors->first('source_status') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="prospect_selector" @if(empty($sales_activity)) style="display:none;" @else @if($sales_activity->source_status != "prospect") style="display:none;" @endif @endif>
                        <label class="col-sm-2 col-form-label">Prospect</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('prospect_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    @if($sales_activity->source_status == "prospect")
                                        <input type="text" class="form-control" value="{{$sales_activity->prospect->code}} : {{ $sales_activity->prospect->customer->name }}" readonly>
                                    @endif
                                    <input type="hidden" name="prospect_id" id="prospect_id" value="{{$sales_activity->prospect_id}}">
                                @else
                                <select class="selectpicker form-control" name="prospect_id" id="prospect_id" onchange="getContact('{{ url('contact/get_by_prospect') }}', this.value)" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" selected>Select Your Option</option>
                                    @foreach($prospects as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($sales_activity)){
                                                if($sales_activity->prospect_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->code }} - {{$detail->customer->name}}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('prospect_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="previous_selector" @if(empty($sales_activity)) style="display:none;" @else @if($sales_activity->source_status != "previous_activity") style="display:none;" @endif @endif>
                        <label class="col-sm-2 col-form-label">Previous Activity</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('previous_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    @if($sales_activity->source_status == "previous_activity")
                                        <input type="text" class="form-control" value="{{$sales_activity->previous->code}}" readonly>
                                    @endif
                                    <input type="hidden" name="previous_id" id="previous_id" value="{{$sales_activity->previous_id}}">
                                @else
                                    <select class="selectpicker form-control" name="previous_id" id="previous_id" onchange="getContact('{{ url('contact/get_by_sales_activity') }}', this.value)" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" selected>Select Your Option</option>
                                        @foreach($sales_activites as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($sales_activity)){
                                                    if($sales_activity->previous_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->code }} - {{$detail->customer->name}}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('previous_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="customer_selector" @if(empty($sales_activity)) style="display:none;" @else @if($sales_activity->source_status != "existing_customer") style="display:none;" @endif @endif>
                        <label class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}" id="exist_customer">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$sales_activity->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$sales_activity->customer_id}}">
                                @else
                                <div class="input-group mb-3">
                                    <select class="selectpicker form-control col-md-12" onchange="getContact('{{ url('contact/get_by_customer') }}', this.value);" id="customer_id" name="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($customers as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($sales_activity)){
                                                    if($sales_activity->customer_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="contact_selector">
                        <label class="col-sm-2 col-form-label">Select Contact</label>
                        <div class="col-sm-10">
                            <div class="form-group" id="new_contact" style="display:none;">
                                <div class="input-group mb-3">
                                    <input type="text" id="new_contact_name" class="form-control" style="height:42px !important; margin-top:5px !important" readonly>
                                    <div class="input-group-append">
                                        <a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#contactModel"><i class="material-icons">edit</i> Edit Contact</a>
                                    </div>
                                </div>
                            </div>
                            @if(!empty(Request::get('action_status')))
                                <input type="text" class="form-control" @if($sales_activity->contact_id != null) value="{{ $sales_activity->contact->name }}" @endif readonly>
                                <input type="hidden" name="contact_id" id="contact_id" value="{{$sales_activity->contact_id}}">
                            @else
                                <div class="input-group mb-3" id="contact_list">
                                    <a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#contactModel"><i class="material-icons">add</i> Create Contact</a>
                                </div>
                                <label class="error">{{ $errors->first('contact_id') }}</label>

                                <div class="modal fade" id="contactModel" tabindex="-1" role="dialog" aria-labelledby="contactModelLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Create New Contact</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                <i class="material-icons">clear</i>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <select class="form-control" name="contact_honorific">
                                                            <option value="Mr">Mr</option>
                                                            <option value="Mrs">Mrs</option>
                                                            <option value="Ms">Ms</option>
                                                            <option value="Miss">Miss</option>
                                                        </select>
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Name</label>
                                                        <input type="text" class="form-control" name="contact_name">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Email</label>
                                                        <input type="text" class="form-control" name="contact_email">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">ID Number (KTP / Passport)</label>
                                                        <input type="text" class="form-control" name="contact_id_number">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Phone</label>
                                                        <input type="text" class="form-control" name="contact_phone">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Mobile Phone</label>
                                                        <input type="text" class="form-control" name="contact_mobile_phone">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Birth Date</label>
                                                        <input type="text" class="form-control datepicker" name="contact_birth_date">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Position</label>
                                                        <input type="text" class="form-control" name="contact_positon">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Department</label>
                                                        <input type="text" class="form-control" name="contact_department">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                <button type="button" onclick="setContact()" class="btn btn-success btn-link pull-right">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <input type="hidden" name="contact_new_status" id="contact_new_status" value="E">
                        </div>
                    </div>
                    <div class="row" @if(!empty(Request::get('action_status'))) style="display:none" @endif>
                        <label class="col-sm-2 col-form-label">Sales Activity Type</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="type" type="radio" value='visit' onclick="setType(this.value)" @if(!empty($sales_activity)) @if($sales_activity->type == "visit") checked @endif @endif> Visit
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="type" type="radio" value='call' onclick="setType(this.value)" @if(!empty($sales_activity)) @if($sales_activity->type == "call") checked @endif @endif> Call
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            {{-- <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" name="type" type="radio" value='offering' onclick="setType(this.value)" @if(!empty($sales_activity)) @if($sales_activity->type == "offering") checked @endif @endif> Offering
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div> --}}
                            <div class="form-group bmd-form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label class="error">{{ $errors->first('type') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="location_input_form" @if(empty($sales_activity)) style="display:none;" @else @if($sales_activity->location == null) style="display:none;" @endif @endif>
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                                <input type="text" class="form-control" name="location" id="location" @if(!empty($sales_activity)) value="{{ $sales_activity->location }}" @endif @if(!empty(Request::get('action_status'))) readonly @endif>
                                <label class="error">{{ $errors->first('location') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="marketing_material_selector" @if(empty($sales_activity)) style="display:none;" @else @if($sales_activity->type != "offering") style="display:none;" @endif @endif>
                        <label class="col-sm-2 col-form-label">Marketing Material</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('marketing_material_id') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="marketing_material_id[]" id="marketing_material_id" multiple data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled>Select Your Option</option>
                                    @foreach($marketing_materials as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($sales_activity)){
                                                foreach($sales_activity->marketing_material as $marketing_material){
                                                    if($marketing_material->id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name}}</option>
                                    @endforeach
                                </select>
                                <label class="error">{{ $errors->first('marketing_material_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Notes</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="notes" @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($sales_activity)){{ $sales_activity->notes }}@endif</textarea>
                                <label class="error">{{ $errors->first('notes') }}</label>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <div class="card-footer">
                @if(!empty(Request::get('action_status')))
                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->update == 1 && $a_g_and_module->isExec == 1)
                        <a onclick="continueTransaction('complete')" class="col-md-12 btn-lg btn btn-success">Complete</a>
                    @endif
                @else
                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1)
                    <a onclick="continueTransaction('open')" class="col-md-3 col-sm-offset-3 btn-lg btn btn-info">Save To Draft</a>
                    @endif

                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->isExec == 1)
                    <a onclick="continueTransaction('posted')" class="col-md-4 col-sm-offset-1 btn-lg btn btn-default">Posting</a>
                    @endif
                @endif

                <div class="modal fade modal-mini modal-primary" id="continueTransactionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-small">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                            </div>
                            <div class="modal-body text-center">
                                <p id="modal_label">Are you sure you want to do continue ?</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                                <button type="button" class="btn btn-success btn-link" onclick="submitForm('{{ $form_id }}')">Yes
                                    <div class="ripple-container"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    @if(!empty($sales_activity))
        getContact('{{ url('contact/get_by_customer') }}', {{ $sales_activity->customer_id }});
    @endif

    function continueTransaction(status_name){
        if(status_name == "open"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to save to draft this form, and you can edit this form further. <br> Are you sure want to continue ?";
        }else if(status_name == "posted"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to posting this form, and you can't edit this form anymore. <br> Are you sure want to continue ?";
        }else if(status_name == "complete"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to complete this form. <br> Are you sure want to continue ?";
        }else{

        }

        var contact_id = null;

        var error_list = "";
        var source_status = $("input[name=source_status]:checked").val();
        var prospect_id = document.getElementById("prospect_id").value;
        var previous_id = document.getElementById("previous_id").value;
        var customer_id = document.getElementById("customer_id").value;
        var type = $("input[name=type]:checked").val();
        var location = document.getElementById("location").value;
        var new_contact_name = document.getElementById("new_contact_name").value;
        var contact_new_status = document.getElementById("contact_new_status").value;

        if(source_status == "" || source_status == null){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select source from</b> </span>'+
                            '</div>';
        }else{
            if(source_status == "prospect"){
                if(prospect_id == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select prospect</b> </span>'+
                                    '</div>';
                }
            }else if(source_status == "previous_activity"){
                if(previous_id == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select previous activity</b> </span>'+
                                    '</div>';
                }
            }else if(source_status == "existing_customer"){
                if(customer_id == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select customer</b> </span>'+
                                    '</div>';
                }
            }else{
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! source from option not found</b> </span>'+
                                '</div>';
            }
        }

        if(type == "" || type == null){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select type</b> </span>'+
                            '</div>';
        }else{
            if(type == "visit"){
                if(location == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to input location</b> </span>'+
                                    '</div>';
                }
            }else if(type == "offering"){

            }
        }

        if(!isEmpty(document.getElementById("contact_id"))){
            contact_id = document.getElementById("contact_id").value;
            if(contact_new_status == "E"){
                if(contact_id == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select contact</b> </span>'+
                                    '</div>';
                }
            }else{
                if(new_contact_name == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to input new contact</b> </span>'+
                                    '</div>';
                }
            }
        }else{
            if(new_contact_name == ""){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to input new contact</b> </span>'+
                                '</div>';
            }
        }

        if(error_list != ""){
            document.getElementById("error_list").innerHTML = error_list;

            $('#continueTransactionModal').modal('hide');
            $("#errorModal").modal();

        }else{
            $("#continueTransactionModal").modal();
        }
    }

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }

        if (typeof maybeObject != "undefined") {
            return false;
        }

        return true;
    }

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }

    function setSourceStatus(source_status){
        if(source_status == "prospect"){
            $('#prospect_selector').show();
            $('#previous_selector').hide();
            $('#customer_selector').hide();
            document.getElementById('previous_id').value='';
            document.getElementById('customer_id').value='';
        }else if(source_status == "previous_activity"){
            $('#prospect_selector').hide();
            $('#previous_selector').show();
            $('#customer_selector').hide();
            document.getElementById('prospect_id').value='';
            document.getElementById('customer_id').value='';
        }else if(source_status == "existing_customer"){
            $('#prospect_selector').hide();
            $('#previous_selector').hide();
            $('#customer_selector').show();
            document.getElementById('prospect_id').value='';
            document.getElementById('previous_id').value='';
        }else{
            $('#prospect_selector').hide();
            $('#previous_selector').hide();
            $('#customer_selector').hide();
            document.getElementById('prospect_id').value='';
            document.getElementById('previous_id').value='';
            document.getElementById('previous_id').value='';
        }
    }

    function setType(type){
        var marketing_material_id = document.getElementById("marketing_material_id");

        if(type == "visit"){
            $('#location_input_form').show();
            $('#marketing_material_selector').hide();
            marketing_material_id.selectedIndex = -1;
        }else if(type == "call"){
            $('#location_input_form').hide();
            $('#marketing_material_selector').hide();
            document.getElementById('location').value = '';
            marketing_material_id.selectedIndex = -1;
        }else if(type == "offering"){
            $('#location_input_form').hide();
            $('#marketing_material_selector').show();
            document.getElementById('location').value = '';
        }
    }

    function getContact(link, customer_id){
        var url = link+"/"+customer_id;

        var contact_list = "";
        var selected = "";

        $.get(url, function (data){
            contact_list += '<select class="form-control col-md-10 selectpicker" name="contact_id" id="contact_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">';
            contact_list += '<option value="">--- Select your contact ---</option>'

            for(var i=0; i < data.length; i++){
                selected = '';
                @if(!empty($sales_activity))
                    if(data[i]['id'] == '{{ $sales_activity->contact_id }}'){
                        selected = 'selected';
                    }
                @endif

                contact_list += '<option value="'+data[i]['id']+'" '+selected+'>'+data[i]['name']+'</option>';
            }

            contact_list += '</select>';

            contact_list += '<div class="input-group-append">'+
                                '<a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#contactModel"><i class="material-icons">add</i> Create Contact</a>'+
                            '</div>';

            document.getElementById("contact_list").innerHTML = contact_list;

            $('#contact_id').selectpicker('refresh');
        });

        $("input[name='contact_new_status']").val("E");
    }

    function setContact(){
        var contact_name = $("input[name='contact_name']").val();
        var contact_positon = $("input[name='contact_positon']").val();
        if(contact_name == ''){
            alert('Name is required');
        }else if(contact_positon == ''){
            alert('Position is required');
        }else{
            $("input[name='contact_new_status']").val("N");
            $('#new_contact_name').val(contact_name);
            $('#new_contact').show();
            $('#contact_list').hide();
            $('#contactModel').modal('hide');
        }
    }
</script>
@endsection
