@extends('layouts.app')

@section('title')
Rakomsis Prospect - Editor
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
                    Prospect Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Prospect
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }} 
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Sales</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$prospect->employee->name}}" readonly>
                                    <input type="hidden" name="employee_id" value="{{$prospect->employee_id}}">
                                @else
                                <select class="selectpicker form-control" name="employee_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">   
                                    <option disabled selected>Select Your Option</option>
                                    @foreach($employees as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($prospect)){
                                                if($prospect->employee_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('employee_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10">
                            <div class="form-group {{ $errors->has('customer_name') ? ' has-error' : '' }}" id="new_customer" style="display:none;">  
                                <div class="input-group mb-3">
                                    <input type="text" id="new_customer_name" class="form-control" style="height:42px !important; margin-top:5px !important" readonly>
                                    <div class="input-group-append">
                                        <a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#customerModel"><i class="material-icons">edit</i> Edit Customer</a>
                                    </div>
                                </div>
                                <label class="error">{{ $errors->first('customer_name') }}</label>
                            </div>
                            <div class="form-group bmd-form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}" id="exist_customer">
                                @if(!empty(Request::get('action_status')) || !empty($prospect))
                                    <input type="text" class="form-control" value="{{$prospect->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" value="{{$prospect->customer_id}}">
                                @else
                                <div class="input-group mb-3">
                                    <select class="selectpicker form-control col-md-10" onchange="getContact('{{ url('contact/get_by_customer') }}', this.value)" name="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">   
                                        <option disabled selected>Select Your Option</option>
                                        @foreach($customers as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#customerModel"><i class="material-icons">add</i> Create Customer</a>
                                    </div>
                                </div>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>

                            <div class="modal fade" id="customerModel" tabindex="-1" role="dialog" aria-labelledby="customerModelLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Create New Customer</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            <i class="material-icons">clear</i>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Nature Of Business</label>
                                                        <div class="form-group bmd-form-group">
                                                            <select class="selectpicker form-control" name="nature_of_business_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                                                <option disabled selected>Select Your Option</option>
                                                                @foreach($nature_of_businesses as $detail)
                                                                    <option value="{{ $detail->id }}" >{{ $detail->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Name</label>
                                                        <input type="text" class="form-control" name="customer_name">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Customer Type</label>
                                                        <div class="checkbox-radios">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input" type="radio" name="customer_type" value="COM" checked> Company
                                                                    <span class="circle">
                                                                        <span class="check"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input class="form-check-input" type="radio" name="customer_type" value="IND"> Individu
                                                                    <span class="circle">
                                                                        <span class="check"></span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <label class="error">{{ $errors->first('customer_type') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Email</label>
                                                        <input type="text" class="form-control" name="customer_email" id="customer_email">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Fax</label>
                                                        <input type="text" class="form-control" name="customer_fax" id="customer_fax">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Phone</label>
                                                        <input type="text" class="form-control" name="customer_phone" id="customer_phone">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Mobile Phone</label>
                                                        <input type="text" class="form-control" name="customer_mobile_phone" id="customer_mobile_phone">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Address</label>
                                                        <textarea name="customer_address" id="customer_address" class="form-control"></textarea>
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Country</label>
                                                        <input type="text" class="form-control" name="customer_country" id="customer_country">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">City</label>
                                                        <input type="text" class="form-control" name="customer_city" id="customer_city">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Zipcode</label>
                                                        <input type="text" class="form-control" name="customer_zipcode" id="customer_zipcode">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group bmd-form-group is-filled">
                                                        <label class="label-control">Tax Number (NPWP)</label>
                                                        <input type="text" class="form-control" name="customer_tax_number" id="customer_tax_number">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                            <button type="button" onclick="setCustomer()" class="btn btn-success btn-link pull-right">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="customer_status" @if(!empty($prospect)) value="{{ $prospect->customer_status }}" @else value="E" @endif>
                    </div>
                    <div class="row" @if(!empty($prospect)) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Contact Status</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="contact_status" onclick="$('#contact_selector').hide();" value="same_with_customer"> Same With Customer or use default contact
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="contact_status" onclick="$('#contact_selector').show();" value="no"> No
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="contact_selector" @if(empty($prospect)) style="display:none;" @endif>
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
                            @if(!empty(Request::get('action_status')) || !empty($prospect))
                                <input type="text" class="form-control" @if($prospect->contact_id != null) value="{{ $prospect->contact->name }}" @endif readonly>
                                <input type="hidden" name="contact_id" value="{{$prospect->contact_id}}">
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
                        </div>
                        <input type="hidden" name="contact_new_status">
                    </div>
                    <div class="row" @if(!empty(Request::get('action_status'))) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Source Status</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="source_status" onclick="$('#agent_selector').hide();$('#referral_selector').show();document.getElementById('agent_id').value = '';" value="referral" @if(!empty($prospect)) @if($prospect->referral_id != null) checked @endif @endif> Referral
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="source_status" onclick="$('#agent_selector').show();$('#referral_selector').hide();document.getElementById('referral_id').value = '';" value="agent" @if(!empty($prospect)) @if($prospect->agent_id != null) checked @endif @endif> Agent
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="referral_selector" @if(!empty($prospect)) @if($prospect->referral_id == null) style="display:none;" @endif @else style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Referral</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('referral_id') ? ' has-error' : '' }}"> 
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" @if($prospect->referral_id != null) value="{{$prospect->referral->name}}" @endif readonly>
                                    <input type="hidden" name="referral_id" value="{{$prospect->referral_id}}">
                                @else
                                    <select class="selectpicker form-control col-md-10" name="referral_id" id="referral_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">   
                                        <option disabled selected>Select Your Option</option>
                                        @foreach($referrals as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($prospect)){
                                                    if($prospect->referral_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('referral_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="agent_selector" @if(!empty($prospect)) @if($prospect->agent_id == null) style="display:none;" @endif @else style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Agent</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('agent_id') ? ' has-error' : '' }}"> 
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" @if($prospect->agent_id != null) value="{{$prospect->agent->name}}" @endif readonly>
                                    <input type="hidden" name="agent_id" value="{{$prospect->agent_id}}">
                                @else
                                    <select class="selectpicker form-control col-md-10" name="agent_id" id="agent_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">   
                                        <option disabled selected>Select Your Option</option>
                                        @foreach($agents as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($prospect)){
                                                    if($prospect->agent_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('agent_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Notes</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('notes') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="notes" @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($prospect)){{ $prospect->notes }}@endif</textarea>
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
        $("#continueTransactionModal").modal();
    }

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
    
    function setCustomer(){
        var customer_name = $("input[name='customer_name']").val();
        if(customer_name == ''){
            alert('Customer name is required');
        }else{
            $("input[name='customer_status']").val("N");
            $('#new_customer_name').val(customer_name);
            $('#new_customer').show();
            $('#exist_customer').hide();
            $('#customerModel').modal('hide');
        }
    }
    
    function setContact(){
        var contact_name = $("input[name='contact_name']").val();
        if(contact_name == ''){
            alert('Contact name is required');
        }else{
            $("input[name='contact_new_status']").val("N");
            $('#new_contact_name').val(contact_name);
            $('#new_contact').show();
            $('#contact_list').hide();
            $('#contactModel').modal('hide');
        }
    }

    function getContact(link, customer_id){
        var url = link+"/"+customer_id;
        
        var contact_list = "";

        $.get(url, function (data){
            contact_list += '<select class="form-control col-md-10 selectpicker" name="contact_id" id="contact_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">';

            for(var i=0; i < data.length; i++){
                contact_list += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
            }

            contact_list += '</select>';

            contact_list += '<div class="input-group-append">'+
                                '<a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#contactModel"><i class="material-icons">add</i> Create Contact</a>'+
                            '</div>';

            document.getElementById("contact_list").innerHTML = contact_list;
            
            $('#contact_id').selectpicker('refresh');
        });

        $("input[name='customer_status']").val("E");
        $("input[name='contact_new_status']").val("E");
    }
</script>
@endsection