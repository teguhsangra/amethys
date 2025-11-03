@extends('layouts.app')

@section('title')
Rakomsis Main Agreement - Editor
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
                    Main Agreement Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Main Agreement
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row" id="inquiry">
                        <label class="col-sm-2 col-form-label">Inquiry</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('inquiry_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($booking->inquiry_id))
                                    <input type="text" class="form-control" value="{{$booking->inquiry->code}}" readonly>
                                    <input type="hidden" name="inquiry_id" id="inquiry_id" value="{{$booking->inquiry_id}}">
                                @else
                                    <select class="selectpicker form-control" name="inquiry_id" id="inquiry_id" onchange="selectInquiry('{{ url($url) }}', this.value)" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($inquiries as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($booking)){
                                                    if($booking->inquiry_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                                if(!empty($inquiry)){
                                                    if($inquiry->id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }

                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->code }} - {{ $detail->customer->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('employee_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="location" @if(!empty($inquiry)) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($booking))
                                    <input type="text" class="form-control" value="{{$booking->location->name}}" readonly>
                                    <input type="hidden" name="location_id" id="location_id" value="{{$booking->location_id}}">
                                @else
                                    <select class="selectpicker form-control" name="location_id" id="location_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($inquiry)){
                                                    if($inquiry->location_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                                if(!empty($booking)){
                                                    if($booking->location_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('location_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Sales</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$booking->employee->name}}" readonly>
                                    <input type="hidden" name="employee_id" id="employee_id" value="{{$booking->employee_id}}">
                                @else
                                <select class="selectpicker form-control" name="employee_id" id="employee_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($employees as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($inquiry)){
                                                if($inquiry->employee_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                            if(!empty($booking)){
                                                if($booking->employee_id == $detail->id){
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
                    <div class="row" id="customer_selector" @if(!empty($inquiry)) style="display:none;" @endif>
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
                                @if(!empty(Request::get('action_status')) || !empty($booking))
                                    <input type="text" class="form-control" value="{{$booking->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$booking->customer_id}}">
                                @else
                                <div class="input-group mb-3">
                                    <select class="selectpicker form-control col-md-10" onchange="getContact('{{ url('contact/get_by_customer') }}', this.value)" id="customer_id" name="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($customers as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($booking)){
                                                    if($booking->customer_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                                if(!empty($inquiry)){
                                                    if($inquiry->customer_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->name }}</option>
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
                        <input type="hidden" id="customer_status" name="customer_status" @if(!empty($booking)) value="{{ $booking->customer_status }}" @else value="E" @endif>
                    </div>
                    <div class="row" id="contact_status" @if(!empty($inquiry)) style="display:none;" @endif @if(!empty($booking)) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Contact Status</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="contact_status" onclick="$('#contact_selector').hide();" value="same_with_customer" @if(!empty($booking)) checked @endif> Same With Customer or use default contact
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
                    <div class="row" id="contact_selector" @if(!empty($inquiry)) style="display:none;" @endif @if(empty($booking)) style="display:none;" @endif>
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
                            @if(!empty(Request::get('action_status')) || !empty($booking))
                                <input type="text" class="form-control" @if($booking->contact_id != null) value="{{ $booking->contact->name }}" @endif readonly>
                                <input type="hidden" name="contact_id" id="contact_id" value="{{$booking->contact_id}}">
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
                        <input type="hidden" name="contact_new_status" id="contact_new_status">
                    </div>
                    <div class="row" id="source_status" @if(!empty($inquiry)) style="display:none;" @endif @if(!empty($booking)) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Source Status</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="source_status" id="source_referral" onclick="$('#agent_selector').hide();$('#referral_selector').show();document.getElementById('agent_id').value = '';" value="referral"> Referral
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="source_status" id="source_agent" onclick="$('#agent_selector').show();$('#referral_selector').hide();document.getElementById('referral_id').value = '';" value="agent"> Agent
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="referral_selector" @if(!empty($inquiry)) style="display:none;" @endif @if(!empty($booking)) @if($booking->referral_id == null) style="display:none;" @endif @else style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Referral</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('referral_id') ? ' has-error' : '' }}">
                                @if(!empty($booking))
                                    <input type="text" class="form-control" @if($booking->referral_id != null) value="{{$booking->referral->name}}" @endif readonly>
                                    <input type="hidden" name="referral_id" id="referral_id" value="{{$booking->referral_id}}">
                                @else
                                    <select class="selectpicker form-control col-md-10" name="referral_id" id="referral_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($referrals as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($booking)){
                                                    if($booking->referral_id == $detail->id){
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
                    <div class="row" id="agent_selector" @if(!empty($inquiry)) style="display:none;" @endif @if(!empty($booking)) @if($booking->agent_id == null) style="display:none;" @endif @else style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Agent</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('agent_id') ? ' has-error' : '' }}">
                                @if(!empty($booking))
                                    <input type="text" class="form-control" @if($booking->agent_id != null) value="{{$booking->agent->name}}" @endif readonly>
                                    <input type="hidden" name="agent_id" id="agent_id" value="{{$booking->agent_id}}">
                                @else
                                    <select class="selectpicker form-control col-md-10" name="agent_id" id="agent_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($agents as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($booking)){
                                                    if($booking->agent_id == $detail->id){
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
                    <div class="row" id="type_selection" @if(!empty($inquiry)) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Type</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="type" onclick="changeType('package');resetPeriode();" value="package" @if(!empty($inquiry)) @if($inquiry->type == 'package') checked @endif @endif @if(!empty($booking)) @if($booking->type == 'package') checked @endif @endif> Package
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="type" onclick="changeType('product');resetPeriode();" value="product" @if(!empty($inquiry)) @if($inquiry->type == 'product') checked @endif @endif @if(!empty($booking)) @if($booking->type == 'product') checked @endif @endif> Product
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="type" onclick="changeType('room');resetPeriode();" id="type_room" value="room" @if(!empty($inquiry)) @if($inquiry->type == 'room') checked @endif @endif @if(!empty($booking)) @if($booking->type == 'room') checked @endif @endif> Room
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Dedicated Phones</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('dedicated_phone_id') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="dedicated_phone_id[]" id="dedicated_phone_id"  multiple="multiple" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    @foreach ($dedicated_phones as $detail)
                                         @php
                                            if(!empty($booking)){
                                                $selected = '';
                                                foreach($booking->dedicated_phones as $dedicated_phone){
                                                    if($dedicated_phone->id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->number }}</option>
                                    @endforeach
                                </select>
                                <label class="error">{{ $errors->first('dedicated_phone_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="package" style="display:none;">
                        <label class="col-sm-2 col-form-label">Package</label>
                        <div class="col-sm-10">
                            <select class="selectpicker form-control" id="package_list" name="package_id" onchange="setPackage(this.value);resetPeriode();" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                            </select>
                        </div>
                    </div>
                    <div class="row" id="room_category" style="display:none;">
                        <label class="col-sm-2 col-form-label">Room Category</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="room_category_id" id="room_category_id" onchange="showPriceType(this.value);resetPeriode();" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($room_categories as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($inquiry)){
                                                if($inquiry->room_category_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                            if(!empty($booking)){
                                                if($booking->room_category_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                <label class="error">{{ $errors->first('room_category_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="room" style="display:none;">
                        <label class="col-sm-2 col-form-label">Room</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="room_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addRoom()">
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="price_type_selection" style="display:none">
                        <label class="col-sm-2 col-form-label">Price Type</label>
                        <div class="col-sm-10 checkbox-radios" >
                            <div class="form-check" id="hourly" style="display:none;">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="price_type_selection" onclick="$('#price_type').val('hourly');resetPeriode();onPeriodeChanged('start_date');" value="hourly" @if(!empty($booking)) @if($booking->price_type == 'hourly') checked @endif @endif> Hourly
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check" id="daily" style="display:none;">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="price_type_selection" onclick="$('#price_type').val('daily');resetPeriode();onPeriodeChanged('start_date');" value="daily" @if(!empty($booking)) @if($booking->price_type == 'daily') checked @endif @endif> Daily
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check" id="monthly" style="display:none;">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="price_type_selection" onclick="$('#price_type').val('monthly');resetPeriode();onPeriodeChanged('start_date');" value="monthly" @if(!empty($booking)) @if($booking->price_type == 'monthly') checked @endif @else checked @endif> Monthly
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check" id="halfday" style="display:none;">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="price_type_selection" onclick="$('#price_type').val('halfday');resetPeriode();onPeriodeChanged('start_date');" value="halfday" @if(!empty($booking)) @if($booking->price_type == 'halfday') checked @endif @endif> Halfday
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="product" style="display:none;">
                        <label class="col-sm-2 col-form-label">Product</label>
                        <div class="col-sm-10">
                            <select class="selectpicker form-control" name="product_id" id="product_id" onchange="setProduct(this.value);resetPeriode();" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Select Your Option</option>
                                @foreach($products as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($booking)){
                                            if($booking->product_id == $detail->id){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row" id="start_to_end" style="display:none;">
                        <label class="col-sm-2 col-form-label">Periode</label>
                        <div class="col-sm-10">
                            <input class="form-check-input" type="hidden" name="start_date_counted" value="Y" @if(!empty($inquiry)) @if($inquiry->start_date_counted == 'Y')  @endif @endif>
                            <div class="row" id="datepicker">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" name="start_date" id="start_date" class="form-control datepicker text-center" placeholder="Start Date" @if(!empty($inquiry)) value="{{ date('m/d/Y', strtotime($inquiry->start_date)) }}" @elseif(!empty($booking)) value="{{ date('m/d/Y', strtotime($booking->start_date)) }}" @else value="{{ date('m/d/Y') }}" @endif>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="number" class="form-control text-center" name="length_of_term" id="length_of_term" min="1" placeholder="Length Of Term" onchange="onPeriodeChanged('length_of_term')" @if(!empty($inquiry)) value="{{ $inquiry->length_of_term }}" @elseif(!empty($booking)) value="{{ $booking->length_of_term }}" @else value="1" @endif>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" name="end_date" id="end_date" class="form-control datepicker text-center" placeholder="End Date" @if(!empty($inquiry)) value="{{ date('m/d/Y', strtotime($inquiry->end_date)) }}" @elseif(!empty($booking)) value="{{ date('m/d/Y', strtotime($booking->end_date)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="timepicker">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="start_time" id="start_time" class="form-control timepicker text-center" placeholder="Start Time" @if(!empty($inquiry)) value="{{ date('H:i', strtotime($inquiry->start_time)) }}" @elseif(!empty($booking)) value="{{ date('H:i', strtotime($booking->start_time)) }}" @else value="{{ date('H:i', strtotime($office_hour_start.':00:00')) }}" @endif>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" name="end_time" id="end_time" class="form-control timepicker text-center" placeholder="End Time" @if(!empty($inquiry)) value="{{ date('H:i', strtotime($inquiry->end_time)) }}" @elseif(!empty($booking)) value="{{ date('H:i', strtotime($booking->end_time)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <input type="text" name="signed_date" id="signed_date" class="form-control datepicker text-center" placeholder="Signed Date" @if(!empty($booking)) value="{{ date('m/d/Y', strtotime($booking->signed_date)) }}" @endif>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="other_product">
                        <label class="col-sm-2 col-form-label">Additional Charge</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="other_product_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="">-- Select Additional Charge --</option>
                                    @foreach($other_products as $detail)
                                        <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addAdditionalCharge()">
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="other_funiture">
                        <label class="col-sm-2 col-form-label">Furniture</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="furniture_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="">-- Select Furniture --</option>
                                    @foreach($furniture as $detail)
                                        <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addFurniture()">
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea id="mytextarea" class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($booking)){{ $booking->remarks }}@endif</textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Term of payment</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('term_of_payment') ? ' has-error' : '' }}">
                                <select  name="term_of_payment" id="term_of_payment"  data-size="5" data-style="select-with-transition" data-show-subtext="true"
                                    class="selectpicker form-control ">

                                    <option value="">Please select one</option>
                                    <option value="1" @if(!empty($booking)) @if($booking->term_of_payment == 1) selected @endif @endif
                                        @if(!empty($inquiry)) @if($inquiry->term_of_payment == 1) selected @endif @endif>Monthly</option>
                                    <option value="3" @if(!empty($booking)) @if($booking->term_of_payment == 3) selected @endif @endif
                                        @if(!empty($inquiry)) @if($inquiry->term_of_payment == 3) selected @endif @endif>Quarterly</option>
                                    <option value="6" @if(!empty($booking)) @if($booking->term_of_payment == 6) selected @endif @endif
                                         @if(!empty($inquiry)) @if($inquiry->term_of_payment == 6) selected @endif @endif>Semi-Anually</option>
                                    <option value="12" @if(!empty($booking)) @if($booking->term_of_payment == 12) selected @endif @endif
                                        @if(!empty($inquiry)) @if($inquiry->term_of_payment == 12) selected @endif @endif>Anually</option>
                                </select>

                                <label class="error">{{ $errors->first('term_of_payment') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Term Notice Period</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('term_notice_period') ? ' has-error' : '' }}">
                                <input type="number" class="form-control text-center" name="term_notice_period" id="term_notice_period"  min="0" placeholder="Free term of payment" @if(!empty($booking)) value="{{ $booking->term_notice_period }}" @elseif(!empty($inquiry)) value="{{ $inquiry->term_notice_period}}"  @else value="0" @endif>
                                <label class="error">{{ $errors->first('term_notice_period') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Free Booking In Month(s)</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('free_term_booking') ? ' has-error' : '' }}">
                                <input type="number" class="form-control text-center" name="free_term_booking" id="free_term_booking" onchange="setDetailTransaction()" min="0" placeholder="Free term of payment" @if(!empty($booking)) value="{{ $booking->free_term_booking }}" @elseif(!empty($inquiry)) value="{{ $inquiry->free_term_booking}}"  @else value="0" @endif>
                                <label class="error">{{ $errors->first('free_term_booking') }}</label>
                            </div>
                        </div>
                    </div>
                    @foreach($complimentaries as $no => $detail)
                        @php
                            $value = 0;
                        @endphp
                            @if(!empty($booking_complimentary))
                                @php
                                    foreach ($booking_complimentary as $item){
                                        if ($item->complimentary_id == $detail->id){
                                             $value =$item->total_complimentary;
                                        }
                                    }
                                @endphp
                            @endif
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ $detail->name }}</label>
                                <div class="col-sm-10">
                                    <div class="form-group bmd-form-group">
                                        <input type="hidden" class="form-control text-center" name="complimentary_id[]" placeholder="{{ $detail->name }}" value="{{$detail->id}}" >
                                        <input type="number" class="form-control text-center" name="total_complimentary[]" placeholder="{{ $detail->name }}"  value="{{$value}}">
                                    </div>
                                </div>
                            </div>
                    @endforeach
                    <div class="row" id="furniture_table" style="display:none">
                        <label class="col-sm-2 col-form-label">Detail Furniture</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <tbody id="furnitures" style="display:none">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="price_type" id="price_type" @if(!empty($inquiry)) value="{{ $inquiry->price_type }}" @endif @if(!empty($booking)) value="{{ $booking->price_type }}" @endif>
                        <input type="hidden" name="detail_price" id="detail_price">
                        <input type="hidden" name="sub_total" id="sub_total">
                        <label class="col-sm-2 col-form-label">Detail Transaction</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <thead class="text-primary text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                        <th>Detail Price</th>
                                        <th>Length Of Term</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="detail_transaction">

                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="5">Sub Total</td>
                                        <td id="view_sub_total" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Discount</td>
                                        <td>
                                            <div class="checkbox-radios">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="usable_discount" onclick="changeDiscountType('not_use')" value="not_use" @if(empty($booking)) checked @else @if($booking->usable_discount == "not_use") checked @endif @endif> Not Use
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="usable_discount" onclick="changeDiscountType('percentage')" value="percentage" @if(!empty($booking)) @if($booking->usable_discount == "percentage") checked @endif @endif> Precentage
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="usable_discount" onclick="changeDiscountType('price')" value="price" @if(!empty($booking)) @if($booking->usable_discount == "price") checked @endif @endif> Fix Price
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="input-group mb-3">
                                                <input type="number" name="discount_percentage" min="0" max="100" id="discount_percentage" @if(empty($booking)) value="0" @else value="{{ $booking->discount_percentage }}" @endif class="form-control text-center" placeholder="Percentage..." style="margin-top: 5px;height: 42px;" onchange="setDiscountValue('percentage',this.value)" readonly>
                                                <div class="input-group-append">
                                                    <a class="btn btn-default btn-round" style="color: #fff;">%</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" id="format_discount_price" name="format_discount_price" class="form-control text-right" onchange="changeToCurrencyFormat('format_discount_price','discount_price');setDiscountValue('price',this.value)" @if(empty($booking)) value="0" @else value="{{ number_format($booking->discount_price, 0, ',', '.') }}" @endif readonly>
                                            <input type="hidden" id="discount_price" name="discount_price" @if(empty($booking)) value="0" @else value="{{ $booking->discount_price }}" @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Total Price</td>
                                        <td id="view_total_price" class="text-right"></td>
                                        <input type="hidden" id="total_price" name="total_price">
                                        <input type="hidden" id="total_service_charge" name="total_service_charge">
                                        <input type="hidden" id="total_tax_price" name="total_tax_price">
                                    </tr>
                                </tbody>

                                <tbody id="additional_charge" style="display:none">
                                </tbody>
                                <tfoot>
                                    <tr id="additional_charge_sum" style="display:none">
                                        <td colspan="5">Total Additional Charge</td>
                                        <td id="view_total_additional_charge" class="text-right"></td>
                                        <input type="hidden" id="total_additional_charge" name="total_additional_charge" value="0">
                                        <input type="hidden" id="total_service_charge_additional_charge" name="total_service_charge_additional_charge">
                                        <input type="hidden" id="total_tax_additional_charge" name="total_tax_additional_charge">
                                    </tr>
                                    <tr id="total_price_ac" style="display:none">
                                        <td colspan="5"><b>Total Price + Total Additional Charge</b></td>
                                        <td id="view_total_price_ac" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">Service Charge Price</td>
                                        <td>
                                            <div class="checkbox-radios">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="service_charge_status" onclick="countAdditionalCharge();countPrice()" value="N" @if(!empty($inquiry)) @if($inquiry->service_charge_status == "N") checked @endif @elseif(!empty($booking)) @if($booking->service_charge_status == "N") checked @endif @else checked @endif> N
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="service_charge_status" onclick="countAdditionalCharge();countPrice();" value="Y" @if(!empty($inquiry)) @if($inquiry->service_charge_status == "Y") checked @endif @elseif(!empty($booking)) @if($booking->service_charge_status == "Y") checked @endif @endif> Y
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td id="view_total_service_charge" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">Tax Price</td>
                                        <td>
                                            <div class="checkbox-radios">
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="tax_status" onclick="countAdditionalCharge();countPrice()" value="no_tax" @if(!empty($inquiry)) @if($inquiry->tax_status == "no_tax") checked @endif @elseif(!empty($booking)) @if($booking->tax_status == "no_tax") checked @endif @else checked @endif> No Tax
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="tax_status" onclick="countAdditionalCharge();countPrice();" value="exclude" @if(!empty($inquiry)) @if($inquiry->tax_status == "exclude") checked @endif @elseif(!empty($booking)) @if($booking->tax_status == "exclude") checked @endif @endif> Exclude
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="tax_status" onclick="countAdditionalCharge();countPrice();" value="include" @if(!empty($inquiry)) @if($inquiry->tax_status == "include") checked @endif @elseif(!empty($booking)) @if($booking->tax_status == "include") checked @endif @endif> Include
                                                        <span class="circle">
                                                            <span class="check"></span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </td>
                                        <td id="view_total_tax_price" class="text-right"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Security Deposit</td>
                                        <td>
                                            <input type="text" id="format_security_deposit" class="form-control text-right" onchange="changeToCurrencyFormat('format_security_deposit','security_deposit');countPrice();" @if(empty($booking)) value="0" @else value="{{ number_format($booking->security_deposit, 0, ',', '.') }}" @endif>
                                            <input type="hidden" id="security_deposit" name="security_deposit" @if(empty($booking)) value="0" @else value="{{ $booking->security_deposit }}" @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Stamp Duty</td>
                                        <td>
                                            <input type="text" id="format_stamp_duty" class="form-control text-right" onchange="changeToCurrencyFormat('format_stamp_duty','stamp_duty');countPrice();" @if(empty($booking)) value="0" @else value="{{ number_format($booking->stamp_duty, 0, ',', '.') }}" @endif>
                                            <input type="hidden" id="stamp_duty" name="stamp_duty" @if(empty($booking)) value="0" @else value="{{ $booking->stamp_duty }}" @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5">Grand Total</td>
                                        <td id="view_grand_total" class="text-right"></td>
                                    </tr>
                                </tfoot>
                            </table>
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

                <div class="modal fade modal-danger" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                            </div>
                            <div class="modal-body" id="error_list">
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-default btn-link" data-dismiss="modal">Close</button>
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
    var link_additional_charge = "{{ url('product/get_by_id') }}";
    var link_furnitures = "{{ url('furniture/get_by_id') }}";
    var tax_percentage = {{ $tax_percentage }};
    var service_charge = {{ $service_charge }};
    var type_of_transaction = '';
    var selected_room = new Array;
    var selected_package = new Array;
    var selected_product = new Array;
    var selected_other_product = new Array;
    var selected_furniture  = new Array;
    var new_item = new Array;
    var item_furniture = new Array;
    var selected_dedicated = new Array;

    @if(!empty($inquiry))
        getData();
        getContact('{{ url('contact/get_by_customer') }}', {{ $inquiry->customer_id }});
        showPriceType({{$inquiry->room_category_id}});
        @if($inquiry->type == 'package')
            changeType('package');
            setPackage({{ $inquiry->package_id }});
        @elseif($inquiry->type == 'product')
            changeType('product');
            setProduct({{ $inquiry->product_id }});
        @elseif($inquiry->type == 'room')
            changeType('room');
            @foreach($inquiry->rooms as $room)
                addRoom({{ $room->id }});
            @endforeach
            setDetailTransaction();
        @endif

        @foreach($inquiry->products as $no => $product)
            var product_id = '{{ $product->id }}';
            var url_additional_charge = link_additional_charge+"/"+product_id;
            $.get(url_additional_charge, function (data){
                new_item = new Array;
                new_item['id'] = '{{ $product->id }}';
                new_item['name'] = '{{ $product->name }}';
                new_item['price'] = '{{ $product->pivot->detail_price }}';
                new_item['qty'] = '{{ $product->pivot->quantity }}';
                new_item['is_editable_price'] = data['is_editable_price'];
                new_item['quantity_status'] = data['quantity_status'];

                selected_other_product.push(new_item);
                @if(sizeof($inquiry->products) == $no +1)
                    setAdditionalCharge();
                @endif
            });
        @endforeach

        @if($inquiry->usable_discount == 'percentage')
            document.getElementById("discount_percentage").readOnly = false;
            document.getElementById("format_discount_price").readOnly = true;
        @endif
        @if($inquiry->usable_discount == 'price')
            document.getElementById("discount_percentage").readOnly = true;
            document.getElementById("format_discount_price").readOnly = false;
        @endif
    @endif

    @if(!empty($booking))
        $("#type_selection").hide();
        getData();
        setSelectedDedicated();
        @if($booking->type == 'package')
            changeType('package');
            setPackage({{ $booking->package_id }});
        @elseif($booking->type == 'product')
            changeType('product');
            setProduct({{ $booking->product_id }});
        @elseif($booking->type == 'room')
            changeType('room');
            @foreach($booking->rooms as $room)
                 addRoom({{ $room->id }});
            @endforeach
            setDetailTransaction();
        @endif

        @foreach($booking->products as $no => $product)
            var product_id = '{{ $product->id }}';
            var url_additional_charge = link_additional_charge+"/"+product_id;
            $.get(url_additional_charge, function (data){
                new_item = new Array;
                new_item['id'] = '{{ $product->id }}';
                new_item['name'] = '{{ $product->name }}';
                new_item['price'] = '{{ $product->pivot->detail_price }}';
                new_item['qty'] = '{{ $product->pivot->quantity }}';
                new_item['is_editable_price'] = data['is_editable_price'];
                new_item['quantity_status'] = data['quantity_status'];

                selected_other_product.push(new_item);
                @if(sizeof($booking->products) == $no +1)
                    setAdditionalCharge();
                @endif
            });
        @endforeach

        @foreach($booking->furniture as $no => $furniture)
            var furniture_id = '{{ $furniture->id }}';
            var url_additional_charge = link_furnitures+"/"+furniture_id;
            $.get(url_additional_charge, function (data){
                item_furniture = new Array;
                item_furniture['id'] = '{{ $furniture->id }}';
                item_furniture['name'] = '{{ $furniture->name }}';
                item_furniture['qty'] = '{{ $furniture->pivot->quantity }}';

                selected_furniture.push(item_furniture);
                @if(sizeof($booking->furniture) == $no +1)
                    setFurniture();
                @endif
            });
        @endforeach

        @if($booking->usable_discount == 'percentage')
            document.getElementById("discount_percentage").readOnly = false;
            document.getElementById("format_discount_price").readOnly = true;
        @endif
        @if($booking->usable_discount == 'price')
            document.getElementById("discount_percentage").readOnly = true;
            document.getElementById("format_discount_price").readOnly = false;
        @endif
    @endif

    $(document).on('dp.change', 'input[name=start_date]', function() {
        onPeriodeChanged('start_date');
    });
    $(document).on('dp.change', 'input[name=end_date]', function() {
        onPeriodeChanged('end_date');
    });
    $(document).on('dp.change', 'input[name=start_time]', function() {
        onPeriodeChanged('start_time');
    });
    $(document).on('dp.change', 'input[name=end_time]', function() {
        onPeriodeChanged('end_time');
    });

    $(function() {
        $("#location_id").change(function() {
            getData();
        });

        $("#room_category_id").change(function() {
            getData();
        });

        $("#dedicated_phone_id").change(function() {
            setSelectedDedicated();
        });

        $('input[type=radio][name=start_date_counted]').change(function() {
            onPeriodeChanged('start_date');
        });
    });

    function setSelectedDedicated(){
        selected_dedicated = new Array;
        $.each($("#dedicated_phone_id option:selected"), function(){
            selected_dedicated.push($(this).val());
        });
    }

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

        var error_list = "";
        var location_id = document.getElementById("location_id").value;
        var employee_id = document.getElementById("employee_id").value;
        var inquiry_id = document.getElementById("inquiry_id").value;
        var customer_id = document.getElementById("customer_id").value;
        var customer_status = document.getElementById("customer_status").value;
        var contact_status = $("input[name=contact_status]:checked").val();
        var contact_id = null;
        var contact_new_status = document.getElementById("contact_new_status").value;
        var package_id = document.getElementById("package_list").value;
        var product_id = document.getElementById("product_id").value;
        var price_type = document.getElementById("price_type").value;
        var start_date = document.getElementById("start_date").value;
        var length_of_term = document.getElementById("length_of_term").value;
        var end_date = document.getElementById("end_date").value;
        var signed_date = document.getElementById("signed_date").value;
        var start_time = document.getElementById("start_time").value;
        var end_time = document.getElementById("end_time").value;
        var type = $("input[name=type]:checked").val();
        var link_availability = "{{ url('check_availability') }}";
        var booking_id = '';
        var array_room_id = '';
        var array_dedicated_phone_id = '';
        var seleced_room_id = new Array;

        for(var i=0; i < selected_room.length; i++){
            seleced_room_id.push(selected_room[i].id);
        }

        if(selected_room.length > 0){
            array_room_id = encodeURIComponent(JSON.stringify(seleced_room_id));
        }

        if(selected_dedicated.length > 0){
            array_dedicated_phone_id = encodeURIComponent(JSON.stringify(selected_dedicated));
        }

        @if(!empty($booking))
            booking_id = '{{ $booking->id }}';
        @endif

        if(isEmpty(document.getElementById("contact_id"))){
            contact_id = document.getElementById("contact_id");
        }

        var url_availability = link_availability+"?inquiry_id="+inquiry_id+"&type="+type+"&package_id="+package_id+"&array_room_id="+array_room_id+"&start_date="+start_date+"&end_date="+end_date+"&start_time="+start_time+"&end_time="+end_time+"&booking_id="+booking_id+"&customer_id="+customer_id+"&array_dedicated_phone_id="+array_dedicated_phone_id;

        $.get(url_availability, function (data){
            if(employee_id == ""){ // Cel Salesman
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to select sales</b> </span>'+
                                '</div>';
            }

            if(inquiry_id == ""){ // Cek Use Inquiry or Not

                if(location_id == ""){ // Cek Location
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select location</b> </span>'+
                                    '</div>';
                }

                if(customer_status == "E"){
                    if(customer_id == ""){
                        error_list +=   '<div class="alert alert-warning">'+
                                            '<span><b> Sorry !!! You have to select customer</b> </span>'+
                                        '</div>';
                    }
                }

                if(contact_status == null){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select contact status</b> </span>'+
                                    '</div>';
                }else{
                    if(contact_status == "no"){
                        if(contact_new_status == ""){
                            error_list +=   '<div class="alert alert-warning">'+
                                                '<span><b> Sorry !!! You have to select existing contact or create new contact</b> </span>'+
                                            '</div>';
                        }
                    }
                }
            }

            // Start : Type Transaction
            if(type_of_transaction == ""){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to select type</b> </span>'+
                                '</div>';
            }else if(type_of_transaction == "package"){
                if(package_id == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select package</b> </span>'+
                                    '</div>';
                }
            }else if(type_of_transaction == "product"){
                if(product_id == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select product</b> </span>'+
                                    '</div>';
                }
            }else if(type_of_transaction == "room"){
                if(selected_room.length == 0){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select room</b> </span>'+
                                    '</div>';
                }
            }
            // End : Type Transaction

            if(start_date == "" || length_of_term == "" || end_date == "" || signed_date == ""){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to put start date, length of term, end date and signed date</b> </span>'+
                                '</div>';
            }

            if(price_type == "hourly"){
                if(start_time == "" || end_time == ""){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to put start time and end time</b> </span>'+
                                    '</div>';
                }
            }
            if(data['available'] == 'false'){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! '+data['error_message']+'</b> </span>'+
                                '</div>';
            }

            if(error_list != ""){
                document.getElementById("error_list").innerHTML = error_list;

                $('#continueTransactionModal').modal('hide');
                $("#errorModal").modal();

            }else{
                $("#continueTransactionModal").modal();
            }
        });

    }

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
    }

    function changeType(type){
        type_of_transaction = type;
        selected_package = new Array;
        selected_product = new Array;
        document.getElementById("detail_transaction").innerHTML = '';
        switch(type){
            case "package":
                $('#other_funiture').hide();$('#package').show();$('#product').hide();$('#room').hide();$('#room_category').hide();$('#price_type_selection').hide();
            break;
            case "product":
                $('#other_funiture').hide();$('#package').hide();$('#product').show();$('#room').hide();$('#room_category').hide();$('#price_type_selection').hide();
            break;
            case "room":
                $('#other_funiture').show();$('#package').hide();$('#product').hide();$('#room').show();$('#room_category').show();$('#price_type_selection').show();
            break;
        }
    }

    function showPriceType(room_categories){
        switch(room_categories){
            case "1":
                $('#hourly').hide();$('#daily').hide();$('#monthly').show();$('#halfday').hide();
            break;
            case "2":
                $('#hourly').show();$('#daily').show();$('#monthly').hide();$('#halfday').show();
            break;
            case "3":
                $('#hourly').show();$('#daily').show();$('#monthly').hide();$('#halfday').show();
            break;
            case "4":
                $('#hourly').show();$('#daily').show();$('#monthly').hide();$('#halfday').show();
            break;
        }

    }

    function getData(){
        var location_id = document.getElementById("location_id").value;
        var room_category_id = document.getElementById("room_category_id").value;

        var link_room = "{{ url('room/get_by_location_id') }}";
        var link_package = "{{ url('package/get_by_location_id') }}";

        var url_room = link_room+"/"+location_id+"?room_category_id="+room_category_id;
        var url_package = link_package+"/"+location_id;

        var room_list = '';
        var package_list = '';

        $.get(url_room, function (data){
            for(var i=0; i < data.length; i++){
                var selected_room = '';
                @if(!empty($inquiry))
                    @foreach($inquiry->rooms as $room)
                        if(data[i]['id'] == {{ $room->id }}){
                            selected_room = 'selected';
                        }
                    @endforeach
                @endif
                @if(!empty($booking))
                    @foreach($booking->rooms as $room)
                        if(data[i]['id'] == {{ $room->id }}){
                            selected_room = 'selected';
                        }
                    @endforeach
                @endif
                room_list += '<option value="'+data[i]['id']+'" '+selected_room+'>'+data[i]['room_number']+'</option>';
            }
            document.getElementById("room_list").innerHTML = room_list;

            $('#room_list').selectpicker('refresh');
        });

        $.get(url_package, function (data){
            package_list += '<option value="" disabled selected>Select Your Option</option>';
            for(var i=0; i < data.length; i++){
                var selected_package = '';
                @if(!empty($inquiry))
                    @if($inquiry->package_id != null)
                        if(data[i]['id'] == {{ $inquiry->package_id }}){
                            selected_package = 'selected';
                        }
                    @endif
                @endif
                @if(!empty($booking))
                    @if($booking->package_id != null)
                        if(data[i]['id'] == {{ $booking->package_id }}){
                            selected_package = 'selected';
                        }
                    @endif
                @endif

                package_list += '<option value="'+data[i]['id']+'" '+selected_package+'>'+data[i]['name']+'</option>';
            }
            document.getElementById("package_list").innerHTML = package_list;
            $('#package_list').selectpicker('refresh');
        });
    }

    function selectInquiry(link, inquiry_id){
        var url = link+"/create?inquiry_id="+inquiry_id;

        window.location = url;
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
            contact_list += '<select class="form-control col-md-10" name="contact_id" id="contact_id">';

            for(var i=0; i < data.length; i++){
                contact_list += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
            }

            contact_list += '</select>';

            contact_list += '<div class="input-group-append">'+
                                '<a class="btn btn-success btn-round" style="color: #fff;" data-toggle="modal" data-target="#contactModel"><i class="material-icons">add</i> Create Contact</a>'+
                            '</div>';

            document.getElementById("contact_list").innerHTML = contact_list;
        });

        $("input[name='customer_status']").val("E");
        $("input[name='contact_new_status']").val("E");
    }

    function setPackage(package_id){
        var link = "{{ url('package/get_by_id') }}";
        var url = link+"/"+package_id;

        selected_package = new Array;
        selected_product = new Array;

        $.get(url, function (data){
            selected_package = data;
            setDetailTransaction();
        });
    }

    function setProduct(product_id){
        var link = "{{ url('product/get_by_id') }}";
        var url = link+"/"+product_id;
        $.get(url, function (data){
            selected_product = data;
            setDetailTransaction();
        });
    }

    function addRoom(id = null){
        var new_item = new Array;
        var room_id = document.getElementById("room_list").value;
        var price_type = document.getElementById("price_type").value;
        var link = "{{ url('room/get_by_id') }}";
        var availability = true;

        if(price_type == ''){
            price_type = 'monthly';
            document.getElementById("price_type").value = price_type;
        }

        if(id != null){
            room_id = id;
        }

        if(room_id != ""){
            var url = link+"/"+room_id;
            var room_price = 0;

            for(var i=0; i < selected_room.length; i++){
                if(selected_room[i].id == room_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                $.get(url, function (data){
                    room_number = data['room_number'];

                    switch(price_type){
                        case 'halfday_price':
                            room_price = data['halfday_price'];
                        break;
                        case 'hourly':
                            room_price = data['hourly_price'];
                        break;
                        case 'daily':
                            room_price = data['daily_price'];
                        break;
                        case 'monthly':
                            room_price = data['monthly_price'];
                        break;
                    }

                    new_item['id'] = data['id'];
                    new_item['room_number'] = data['room_number'];
                    new_item['room_price'] = room_price;

                    selected_room.push(new_item);

                    setupPeriode(price_type);

                    setDetailTransaction();
                    if(id == null) alert("New Room Added");
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }
    }

    function removeRoom(index){
        selected_room.splice(index, 1);
        setDetailTransaction();
    }

    function addAdditionalCharge(){
        var new_item = new Array;

        var other_product_id = document.getElementById("other_product_list").value;
        var url_additional_charge = link_additional_charge+"/"+other_product_id;
        var availability = true;

        if(other_product_id != ""){
            for(var i=0; i < selected_other_product.length; i++){
                if(selected_other_product[i].id == other_product_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                $.get(url_additional_charge, function (data){
                    new_item['id'] = data['id'];
                    new_item['name'] = data['name'];
                    new_item['price'] = data['price'];
                    new_item['qty'] = 1;
                    new_item['is_editable_price'] = data['is_editable_price'];
                    new_item['quantity_status'] = data['quantity_status'];

                    selected_other_product.push(new_item);
                    alert("New Additional Charge Added");
                    setAdditionalCharge();
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }
    }

    function removeAdditionalCharge(index){
        selected_other_product.splice(index, 1);
        setAdditionalCharge();
    }

    function addFurniture(){
        var item_furniture = new Array;

        var furniture_id = document.getElementById("furniture_list").value;
        var url_furnitures = link_furnitures+"/"+furniture_id;
        var availability = true;

        if(furniture_id != ""){
            for(var i=0; i < selected_furniture.length; i++){
                if(selected_furniture[i].id == furniture_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                $.get(url_furnitures, function (data){
                    item_furniture['id'] = data['id'];
                    item_furniture['name'] = data['name'];
                    item_furniture['qty'] = 1;

                    selected_furniture.push(item_furniture);
                    alert("New Furniture Added");
                    setFurniture();
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }
    }

    function removeFurniture(index){
        selected_furniture.splice(index, 1);
        setFurniture();
    }

    function setFurniture(){
        var furniture = '';
        if(selected_furniture.length > 0){
            furniture += '<tr>';
                furniture += '<td colspan="6"><b>Furniture</b></td>';
            furniture += '</tr>';
            furniture += '<tr>';
                furniture += '<td class="text-center">#</td>';
                furniture += '<td class="text-center">Item Name</td>';
                furniture += '<td class="text-center" colspan="4">Qty</td>';
            furniture += '</tr>';

            for(var i=0; i < selected_furniture.length; i++){
                var furniture_id = selected_furniture[i].id;
                var furniture_name = selected_furniture[i].name;
                var furniture_quantity = selected_furniture[i].qty;

                furniture += '<tr>';
                furniture += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeFurniture('+i+')"><i class="material-icons">remove</i></a></td>';
                furniture += '<td><input type="hidden" name="furniture_id[]" value="'+furniture_id+'">'+furniture_name+'</td>';
                furniture += '<td class="text-center" colspan="4"><input type="number" class="form-control text-center" name="fu_quantity[]" id="fu_quantity'+furniture_id+'" min="1" value="'+furniture_quantity+'"></td>';

                if(i == selected_furniture.length - 1){
                    furniture += '';
                }else{
                    furniture += '</tr>';
                }

                $("#furniture_table").show();
                $("#furnitures").show();
            }
        }else{
            $("#furniture_table").hide();
            $("#furnitures").hide();
        }
        document.getElementById("furnitures").innerHTML = furniture;
    }

    function setDetailTransaction(){
        var price_type = document.getElementById("price_type").value;
        var free_term_booking = document.getElementById("free_term_booking").value;
        var length_of_term = document.getElementById("length_of_term").value;
        var link = "{{ url('room/get_by_id') }}";
        var detail_transaction = '';
        var quantity = 1;
        var detail_sub_total = 0;
        var detail_price = 0;
        var free_term_booking_view = '';

        if(price_type != 'halfday'){
            if(free_term_booking != ""){
                free_term_booking_view = ' - ('+free_term_booking+') ';
            }
        }

        @if(!empty($inquiry))
            quantity = '{{ $inquiry->quantity }}';
        @endif

        @if(!empty($booking))
            quantity = '{{ $booking->quantity }}';
        @endif

        if(selected_package.length != 0){
            detail_transaction += '<tr>';
                detail_transaction += '<td>'+selected_package['name']+'</td>';
                detail_transaction += '<td>'+numberWithCommas(selected_package['price'])+'</td>';

                if(length_of_term == 0){
                    detail_transaction += '<td id="length_of_term_transaction" class="text-center">'+selected_package['total_term']+'</td>';
                }else{
                    detail_transaction += '<td id="length_of_term_transaction" class="text-center">'+length_of_term+free_term_booking_view+'</td>';
                }

                if(selected_package['quantity_status'] == 'Y'){
                    detail_transaction += '<td><input type="number" name="quantity" class="form-control text-center" id="quantity" value="'+quantity+'" placeholder="Input quantity..." onchange="countPrice()"></td>';
                }else{
                    detail_transaction += '<td><input type="number" name="quantity" class="form-control text-center" id="quantity" value="'+quantity+'" readonly></td>';
                }

                if(selected_package['price_type'] == 'hourly'){
                    detail_sub_total = parseFloat(selected_package['price']) * parseFloat(quantity);
                }else{
                    detail_sub_total = parseFloat(selected_package['price']) * parseFloat(quantity) * length_of_term;
                }

                detail_transaction += '<td class="text-right">';
                    detail_transaction += numberWithCommas(detail_sub_total);
                detail_transaction += '</td>';
            detail_transaction += '</tr>';

            document.getElementById("detail_transaction").innerHTML = detail_transaction;

            document.getElementById("price_type").value = selected_package['price_type'];

            document.getElementById("detail_price").value = selected_package['price'];

            document.getElementById("sub_total").value = selected_package['price'];

            setupPeriode(selected_package['price_type'], selected_package['total_term']);

            countPrice();
        }else if(selected_product.length != 0){
            detail_transaction += '<tr>';
                detail_transaction += '<td colspan="2">'+selected_product['name']+'</td>';
                detail_transaction += '<td>'+numberWithCommas(selected_product['price'])+'</td>';
                if(length_of_term == 0){
                    detail_transaction += '<td id="length_of_term_transaction" class="text-center">1</td>';
                }else{
                    detail_transaction += '<td id="length_of_term_transaction" class="text-center">'+length_of_term+free_term_booking_view+'</td>';
                }

                if(selected_product['quantity_status'] == 'Y'){
                    detail_transaction += '<td><input type="number" name="quantity" class="form-control text-center" id="quantity" value="'+quantity+'" placeholder="Input quantity..." onchange="countPrice()"></td>';
                }else{
                    detail_transaction += '<td><input type="number" name="quantity" class="form-control text-center" id="quantity" value="'+quantity+'" readonly></td>';
                }

                detail_transaction += '<td class="text-right">';
                    detail_transaction += numberWithCommas(selected_product['price'] * (length_of_term - free_term_booking) * quantity);
                detail_transaction += '</td>';
            detail_transaction += '</tr>';

            document.getElementById("detail_transaction").innerHTML = detail_transaction;

            document.getElementById("price_type").value = selected_product['price_type'];

            document.getElementById("detail_price").value = selected_product['price'];

            document.getElementById("sub_total").value = selected_package['price'];

            setupPeriode(selected_product['price_type']);

            countPrice();
        }else if(selected_room.length != 0){
            for(var i=0; i < selected_room.length; i++){
                var room_id = selected_room[i]['id'];
                var room_number = selected_room[i]['room_number'];
                var room_price = selected_room[i]['room_price'];
                detail_transaction += '<tr>';
                detail_transaction += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeRoom('+i+')"><i class="material-icons">remove</i></a></td>';
                detail_transaction += '<td class="text-center">';
                    detail_transaction += '<input type="hidden" name="room_detail_price[]" id="room_detail_price_'+room_id+'" value="'+room_price+'">';
                    detail_transaction += '<input type="hidden" name="room_id[]" id="room_id_'+room_id+'" value="'+room_id+'">'+room_number;
                detail_transaction +='</td>';
                detail_transaction += '<td>'+numberWithCommas(room_price)+'</td>';

                detail_transaction += '<td id="length_of_term_transaction" class="text-center">'+length_of_term+free_term_booking_view+'</td>';

                detail_transaction += '<td><input type="text" name="quantity" id="quantity" class="form-control text-center" value="1" readonly ></td>';

                detail_transaction += '<td class="text-right">'+numberWithCommas(room_price * (length_of_term - free_term_booking))+'</td>';

                detail_transaction += '</tr>';

                detail_price = parseFloat(detail_price) + parseFloat(room_price);

                document.getElementById("detail_price").value = parseFloat(detail_price);
            }
            document.getElementById("detail_transaction").innerHTML = detail_transaction;
        }
        countPrice();
    }

    function setAdditionalCharge(){
        var additional_charge = '';
        if(selected_other_product.length > 0){

            for(var i=0; i < selected_other_product.length; i++){

                var product_id = selected_other_product[i].id;
                var product_name = selected_other_product[i].name;
                var product_price = selected_other_product[i].price;
                var quantity = selected_other_product[i].qty;

                additional_charge += '<tr>';
                additional_charge += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeAdditionalCharge('+i+')"><i class="material-icons">remove</i></a></td>';
                additional_charge += '<td colspan="2"><input type="hidden" name="other_product_id[]" value="'+product_id+'">'+product_name+'</td>';

                if(selected_other_product[i].is_editable_price == 'Y'){
                    additional_charge += '<td>';
                    additional_charge += '<input type="text" class="form-control text-center" id="format_ac_detail_price_'+product_id+'" value="'+numberWithCommas(product_price)+'" onchange="changeToCurrencyFormat('+"'format_ac_detail_price_"+product_id+"'"+', '+"'ac_detail_price_"+product_id+"'"+');countAdditionalCharge();">';
                    additional_charge += '<input type="hidden" name="ac_detail_price[]" id="ac_detail_price_'+product_id+'" value="'+product_price+'">';
                    additional_charge += '</td>';
                }else{
                    additional_charge += '<td>'
                    additional_charge += '<input type="text" class="form-control text-center" id="format_ac_detail_price_'+product_id+'" value="'+numberWithCommas(product_price)+'" readonly>';
                    additional_charge += '<input type="hidden" name="ac_detail_price[]" id="ac_detail_price_'+product_id+'" value="'+product_price+'">';
                    additional_charge += '</td>';
                }

                if(selected_other_product[i].quantity_status == 'Y'){
                    additional_charge += '<td class="text-center"><input type="number" class="form-control text-center" name="ac_quantity[]" id="ac_quantity_'+product_id+'" min="1" value="'+quantity+'" onchange="countAdditionalCharge()"></td>';
                }else{
                    additional_charge += '<td class="text-center"><input type="number" class="form-control text-center" name="ac_quantity[]" id="ac_quantity_'+product_id+'" min="1" value="'+quantity+'" readonly></td>';
                }

                additional_charge += '<td id="ac_sub_total_'+product_id+'" class="text-right">'+numberWithCommas(product_price)+'</td>';


                if(i == selected_other_product.length - 1){
                    additional_charge += '';
                }else{
                    additional_charge += '</tr>';
                }

                $("#additional_charge").show();
                $("#additional_charge_sum").show();
                $("#total_price_ac").show();

                document.getElementById("additional_charge").innerHTML = additional_charge;
            }
        }else{
            $("#additional_charge").hide();
            $("#additional_charge_sum").hide();
            $("#total_price_ac").hide();
            document.getElementById("additional_charge").innerHTML = "";
            document.getElementById("total_additional_charge").value = 0;
        }
        countAdditionalCharge();

    }

    function setupPeriode(price_type, total_term=0){
        if(price_type == "hourly"){
            $('#start_to_end').show();
            $('#datepicker').show();
            document.getElementById("start_date").readOnly = false;
            document.getElementById("length_of_term").readOnly = false;
            document.getElementById("end_date").readOnly = false;

            $('#timepicker').show();
            document.getElementById("start_time").readOnly = false;
            document.getElementById("end_time").readOnly = false;

        }else if(price_type == "daily"){
            $('#start_to_end').show();
            $('#datepicker').show();
            document.getElementById("start_date").readOnly = false;
            document.getElementById("length_of_term").readOnly = false;
            document.getElementById("end_date").readOnly = false;

            $('#timepicker').hide();

        }else if(price_type == "monthly"){
            $('#start_to_end').show();
            $('#datepicker').show();
            document.getElementById("start_date").readOnly = false;
            document.getElementById("length_of_term").readOnly = false;
            document.getElementById("end_date").readOnly = false;

            $('#timepicker').hide();

        }else if(price_type == "yearly"){
            $('#start_to_end').show();
            $('#datepicker').show();
            document.getElementById("start_date").readOnly = false;
            document.getElementById("length_of_term").readOnly = false;
            document.getElementById("end_date").readOnly = false;

            $('#timepicker').hide();
        }else if(price_type == "halfday"){
            $('#start_to_end').show();
            $('#datepicker').show();
            document.getElementById("start_date").readOnly = false;
            document.getElementById("length_of_term").readOnly = true;
            document.getElementById("end_date").readOnly = true;

            $('#timepicker').show();
            document.getElementById("start_time").readOnly = false;
            document.getElementById("end_time").readOnly = true;

        }

        if(total_term > 0){
            document.getElementById("length_of_term").value = total_term;
            document.getElementById("length_of_term_transaction").innerHTML = total_term;
        }
    }

    function onPeriodeChanged(driven_by){
        var start_date_counted = "Y";
        var price_type = document.getElementById("price_type").value;
        var start_date = document.getElementById("start_date").value;
        var length_of_term = document.getElementById("length_of_term").value;
        var end_date = document.getElementById("end_date").value;
        var start_time = document.getElementById("start_time").value;
        var end_time = document.getElementById("end_time").value;
        var link = "{{ url('setup_periode') }}";
        var url = link+"?driven_by="+driven_by+"&price_type="+price_type+"&start_date="+start_date+"&length_of_term="+length_of_term+"&end_date="+end_date+"&start_time="+start_time+"&end_time="+end_time+"&start_date_counted="+start_date_counted;

        $.get(url, function (data){
            if(data['message'] == 'complete'){
                document.getElementById("start_date").value = data['start_date'];
                document.getElementById("length_of_term").value = data['length_of_term'];
                document.getElementById("end_date").value = data['end_date'];
                document.getElementById("start_time").value = data['start_time'];
                document.getElementById("end_time").value = data['end_time'];
            }
            setDetailTransaction();
        });
    }

    function resetPeriode(){
        var price_type = document.getElementById("price_type").value;
        document.getElementById("start_date").value = "{{ date('m/d/Y') }}";
        document.getElementById("length_of_term").value = "1";
        document.getElementById("end_date").value = "";
        document.getElementById("start_time").value = "{{ date('H:i', strtotime($office_hour_start.':00:00')) }}";
        document.getElementById("end_time").value = "";

        selected_room = new Array;
        selected_package = new Array;
        selected_product = new Array;
        selected_other_product = new Array;


        setupPeriode(price_type);

        setDetailTransaction();
        setAdditionalCharge();
    }

    function changeDiscountType(usable_discount){
        document.getElementById("format_discount_price").value = 0;
        document.getElementById("discount_price").value = 0;
        document.getElementById("discount_percentage").value = 0;
        if(usable_discount == 'percentage'){
            document.getElementById("discount_percentage").readOnly = false;
            document.getElementById("format_discount_price").readOnly = true;
        }else if(usable_discount == 'price'){
            document.getElementById("discount_percentage").readOnly = true;
            document.getElementById("format_discount_price").readOnly = false;
        }else{
            document.getElementById("discount_percentage").readOnly = true;
            document.getElementById("format_discount_price").readOnly = true;
        }
        countPrice();
    }

    function setDiscountValue(usable_discount, discount_value){
        var sub_total = document.getElementById("sub_total").value;
        var discount_price = 0;
        if(sub_total > 0){
            if(usable_discount == 'percentage'){
                discount_price = sub_total * (discount_value/100);
            }else if(usable_discount == 'price'){
                discount_price = document.getElementById("discount_price").value;
            }
            document.getElementById("format_discount_price").value = numberWithCommas(discount_price);
            document.getElementById("discount_price").value = discount_price;
            countPrice();
        }
    }

    function countAdditionalCharge(){
        var total_additional_charge = 0;
        document.getElementById("total_additional_charge").value = 0;

        // Start : For Additional Charge
        for(var i=0; i < selected_other_product.length; i++){
            var product_id = selected_other_product[i].id;
            ac_detail_price = parseFloat(document.getElementById("ac_detail_price_"+product_id).value);
            ac_quantity = parseFloat(document.getElementById("ac_quantity_"+product_id).value);
            ac_sub_total = ac_detail_price * ac_quantity;
            total_additional_charge = parseFloat(total_additional_charge) + parseFloat(ac_sub_total);

            selected_other_product[i].price = ac_detail_price;
            selected_other_product[i].qty = ac_quantity;

            document.getElementById("ac_sub_total_"+product_id).innerHTML = numberWithCommas(ac_sub_total);
        }
        document.getElementById("total_additional_charge").value = total_additional_charge;
        countPrice();
        // End : For Additional Charge
    }

    function countPrice(){
        var free_term_booking = document.getElementById("free_term_booking").value;
        var price_type = document.getElementById("price_type").value;
        var length_of_term = parseFloat(document.getElementById("length_of_term").value);
        var quantity = 1;

        if(document.getElementById("quantity") != null){
            quantity = parseFloat(document.getElementById("quantity").value);
        }
        var detail_price = parseFloat(document.getElementById("detail_price").value);
        var discount_price = parseFloat(document.getElementById("discount_price").value);
        var security_deposit = parseFloat(document.getElementById("security_deposit").value);
        var stamp_duty = parseFloat(document.getElementById("stamp_duty").value);
        var total_service_charge_additional_charge = 0;


        document.getElementById("total_additional_charge").value = 0;
        var total_additional_charge = 0;
        // Start : For Additional Charge
        for(var i=0; i < selected_other_product.length; i++){
            var product_id = selected_other_product[i].id;
            ac_detail_price = parseFloat(document.getElementById("ac_detail_price_"+product_id).value);
            ac_quantity = parseFloat(document.getElementById("ac_quantity_"+product_id).value);
            ac_sub_total = ac_detail_price * ac_quantity;
            total_additional_charge = parseFloat(total_additional_charge) + parseFloat(ac_sub_total);
            selected_other_product[i].price = ac_detail_price;
            selected_other_product[i].qty = ac_quantity;

            document.getElementById("ac_sub_total_"+product_id).innerHTML = numberWithCommas(ac_sub_total);
        }
        document.getElementById("total_additional_charge").value = total_additional_charge;
        var total_tax_additional_charge = 0;
        var tax_status = $("input[name=tax_status]:checked").val();
        var service_charge_status = $("input[name=service_charge_status]:checked").val();
        var sub_total = 0;
        var grand_total = 0;

        if(type_of_transaction == "product"){
            sub_total = quantity * detail_price * (length_of_term - free_term_booking);
        }else if (type_of_transaction == "room"){
            for(var i=0; i < selected_room.length; i++){
                total_use = parseInt($("#total_use_free_daily_room_"+selected_room[i]['id']).val());
                room_price = $("#room_detail_price_"+selected_room[i]['id']).val();
                if(price_type == 'halfday'){
                    sub_total = sub_total + (room_price * quantity);
                }else{
                    sub_total = sub_total+  (room_price * quantity * (length_of_term - free_term_booking));
                }
            }
        }else if (type_of_transaction == "package"){

        }


        total_price = sub_total - discount_price;


        // Start : For Service Charge
        if(service_charge_status == null){
            total_service_charge = 0;
            total_service_charge_additional_charge = 0;
        }else{
            if(service_charge_status == "Y"){
                total_service_charge = parseFloat(total_price) * parseFloat(service_charge);
                total_service_charge_additional_charge = parseFloat(total_additional_charge) * parseFloat(service_charge);
            }else{
                total_service_charge = 0;
                total_service_charge_additional_charge = 0;
            }
        }
        // End : For Service Charge

        // Start : For Count Tax
        if(tax_status == null){
            total_tax_price = 0;
            total_tax_additional_charge = 0;
        }else{
            if(tax_status == 'no_tax'){
                total_tax_price = 0;
                total_tax_additional_charge = 0;
            }else if(tax_status == 'exclude'){
                total_tax_price = parseFloat(parseFloat(total_price) + parseFloat(total_service_charge)) * parseFloat(tax_percentage);
                total_tax_additional_charge = parseFloat(parseFloat(total_additional_charge) + parseFloat(total_service_charge_additional_charge)) * parseFloat(tax_percentage);
            }else if(tax_status == 'include'){
                var temp_1 = total_price;
                var temp_2 = total_additional_charge;
                if(service_charge_status == "Y"){
                    var temp_1_1 = parseFloat(total_price) / (1 + parseFloat(tax_percentage));
                    total_tax_price = parseFloat(temp_1) - parseFloat(temp_1_1);
                    total_price = parseFloat(temp_1_1) / (1 + parseFloat(service_charge));
                    total_service_charge = parseFloat(temp_1_1) - parseFloat(total_price);

                    var temp_2_1 = parseFloat(total_additional_charge) / (1 + parseFloat(tax_percentage));
                    total_tax_additional_charge = parseFloat(temp_2) - parseFloat(temp_2_1);
                    total_additional_charge = parseFloat(temp_2_1) / (1 + parseFloat(service_charge));
                    total_service_charge_additional_charge = parseFloat(temp_2_1) - parseFloat(total_additional_charge);
                }else{
                    total_service_charge = 0;
                    total_service_charge_additional_charge = 0;

                    total_price = parseFloat(total_price) / (1 + parseFloat(tax_percentage));
                    total_tax_price = parseFloat(temp_1) - parseFloat(total_price);

                    total_additional_charge = parseFloat(total_additional_charge) / (1 + parseFloat(tax_percentage));
                    total_tax_additional_charge = parseFloat(temp_2) - parseFloat(total_additional_charge);
                }
            }else{
                total_tax_price = 0;
                total_tax_additional_charge = 0;
            }
        }
        // End : For Count Tax

        total_price = Math.round(total_price);
        total_service_charge = Math.round(total_service_charge);
        total_tax_price = Math.round(total_tax_price);

        total_additional_charge = Math.round(total_additional_charge);
        total_service_charge_additional_charge = Math.round(total_service_charge_additional_charge);
        total_tax_additional_charge = Math.round(total_tax_additional_charge);

        var grand_total_tax = parseFloat(total_tax_price) + parseFloat(total_tax_additional_charge);
        var view_total_price_ac = parseFloat(total_price) + parseFloat(total_additional_charge);
        var view_total_service_charge = parseFloat(total_service_charge) + parseFloat(total_service_charge_additional_charge);

        grand_total = parseFloat(total_price) + parseFloat(total_service_charge) + parseFloat(total_tax_price) + parseFloat(security_deposit) + parseFloat(stamp_duty);
        grand_total = parseFloat(total_additional_charge) + parseFloat(total_service_charge_additional_charge) + parseFloat(total_tax_additional_charge) + parseFloat(grand_total);

        document.getElementById("sub_total").value = sub_total;
        document.getElementById("total_price").value = total_price;
        document.getElementById("total_service_charge").value = total_service_charge;
        document.getElementById("total_tax_price").value = total_tax_price;
        document.getElementById("total_additional_charge").value = total_additional_charge;
        document.getElementById("total_service_charge_additional_charge").value = total_service_charge_additional_charge;
        document.getElementById("total_tax_additional_charge").value = total_tax_additional_charge;
        document.getElementById("view_sub_total").innerHTML = numberWithCommas(parseInt(sub_total));
        document.getElementById("view_total_price").innerHTML = numberWithCommas(parseInt(total_price));
        document.getElementById("view_total_additional_charge").innerHTML = numberWithCommas(parseInt(total_additional_charge));
        document.getElementById("view_total_price_ac").innerHTML = numberWithCommas(parseInt(view_total_price_ac));
        document.getElementById("view_total_service_charge").innerHTML = numberWithCommas(parseInt(view_total_service_charge));
        document.getElementById("view_total_tax_price").innerHTML = numberWithCommas(parseInt(grand_total_tax));
        document.getElementById("view_grand_total").innerHTML = numberWithCommas(parseInt(grand_total));
    }
</script>
@endsection
