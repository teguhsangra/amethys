@extends('layouts.app')

@section('title')
Rakomsis Virtual Office - Editor
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
                    Virtual Office Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Virtual Office
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <input type="hidden" name="booking_id" @if(!empty(\Request::get('booking_id'))) value="{{ \Request::get('booking_id') }}" @endif>
                    <input type="hidden" name="is_renewal" id="is_renewal" @if(!empty($is_renewal))value="{{ $is_renewal }}"@endif>
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
                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($booking))
                                    <input type="text" class="form-control" value="{{$booking->location->name}}" readonly>
                                    <input type="hidden" name="location_id" id="location_id" value="{{$booking->location_id}}">
                                @else
                                    <select class="selectpicker form-control" name="location_id" id="location_id" onchange="getData()" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
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
                    <div class="row" id="bank_account">
                        <label class="col-sm-2 col-form-label">Bank</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('bank_account_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" @if($booking->bank_account_id != null) value="{{ $booking->bank_account->bank_name.' : '.$booking->bank_account->account_no.' / '.$booking->bank_account->account_name }}" @endif readonly>
                                    <input type="hidden" name="bank_account_id" id="bank_account_id" value="{{$booking->bank_account_id}}">
                                @else
                                    <select class="selectpicker form-control" name="bank_account_id" id="bank_account_id" odata-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($bank_accounts as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($booking)){
                                                    if($booking->bank_account_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->bank_name.' : '.$detail->account_no.' / '.$detail->account_name }}</option>
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
                                                    if($inquiry->id == $detail->id){
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
                    <div class="row" id="customer_selector">
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
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$booking->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$booking->customer_id}}">
                                @else
                                <div class="input-group mb-3">
                                    <select class="selectpicker form-control col-md-10" onchange="getContact('{{ url('contact/get_by_customer') }}', this.value);" id="customer_id" name="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
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
                                                         <input type="text" class="form-control" name="nature_of_business" maxlength="255">
                                                        <span class="material-input"></span>
                                                        <span class="material-input"></span>
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
                                                        <label class="label-control">VAT Number (NPWP)</label>
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
                        <input type="hidden" id="customer_status" name="customer_status" value="E">
                    </div>
                    <div class="row" id="contact_status" @if(!empty($inquiry)) style="display:none;" @endif>
                        <label class="col-sm-2 col-form-label">Contact Status</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="contact_status" onclick="$('#contact_selector').hide();" value="same_with_customer" @if(empty($booking)) checked @endif> Same With Customer or use default contact
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="contact_status" onclick="$('#contact_selector').show();" value="no" @if(!empty($booking)) checked @endif> No
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="contact_selector" @if(empty($booking)) style="display:none;" @endif>
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
                    <div class="row" id="address_status_selector">
                        <label class="col-sm-2 col-form-label">Address Status</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('address_status') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="address_status" id="address_status" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                    <option value="">Please select one</option>
                                    <option value="location" @if(!empty($booking)) @if($booking->address_status == "location") selected @endif @endif>Location</option>
                                    <option value="customer" @if(!empty($booking)) @if($booking->address_status == "customer") selected @endif @else selected @endif >Customer</option>
                                </select>
                            </div>
                        </div>
                        <label class="error">{{ $errors->first('address_status') }}</label>
                    </div>

                    <input type="hidden" name="start_date_counted" id="start_date_counted" value="Y">

                    <div class="row" id="start_to_end" style="display:none;">
                        <label class="col-sm-2 col-form-label">Periode</label>
                        <div class="col-sm-10">
                            <input class="form-check-input" type="hidden" name="start_date_counted" value="Y" @if(!empty($inquiry)) @if($inquiry->start_date_counted == 'Y')  @endif @endif>
                            <div class="row" id="datepicker">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <input type="text" name="start_date" id="start_date" class="form-control datepicker text-center" placeholder="Start Date" @if(!empty($inquiry)) value="{{ date('m/d/Y', strtotime($inquiry->start_date)) }}" @endif @if(!empty($booking)) value="{{ date('m/d/Y', strtotime($booking->start_date)) }}" @endif @if(!empty(Request::get('is_renewal')))  value="{{ date('m/d/Y', strtotime($booking->end_date.'+'.'1'.'days')) }}" @endif>
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
                    <div class="row" id="product">
                        <label class="col-sm-2 col-form-label">Product</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="product_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addProduct()">
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <label class="col-sm-2 col-form-label">Dedicated Phones</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="dedicated_phone_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">

                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addDedicatedPhone()">
                                        <i class="material-icons">add</i>
                                    </a>
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
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
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
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Term of payment</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('term_of_payment') ? ' has-error' : '' }}">

                                <input type="number" name="term_of_payment" id="term_of_payment" class="form-control text-center" @if(!empty($inquiry)) value="{{ $inquiry->term_of_payment }}" @elseif(!empty($booking)) value="{{ $booking->term_of_payment }}" @else value="1" @endif>

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
                        <label class="col-sm-2 col-form-label">Free Booking In Term(s)</label>
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
                        @if(!empty($booking))
                            @php
                                foreach ($booking->complimentarys as $complimentary){
                                    if ($complimentary->id == $detail->id){
                                        $value = $complimentary->pivot->total_complimentary;
                                    }
                                }
                            @endphp
                        @endif
                        <div class="row">
                            <label class="col-sm-2 col-form-label">{{ $detail->name }}</label>
                            <div class="col-sm-10">
                                <div class="form-group bmd-form-group">
                                    <input type="hidden" class="form-control text-center" name="booking_complimentary_id[]" placeholder="{{ $detail->name }}" value="{{$detail->id}}" >
                                    <input type="number" class="form-control text-center" name="total_complimentary[]" placeholder="{{ $detail->name }}"  value="{{$value}}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." @if(!empty(Request::get('action_status'))) readonly  @endif>Please send us the company's tax identification number (NPWP) within 45 calendar days after payment is made for tax reporting purposes. If we do not receive it within the specified timeframe, Amethyst reserves the right to report the client's taxes using Amethyst's NPWP, and there will be no refunds in the future. Any consequences or losses incurred will be entirely the client's responsibility, and the client releases Amethyst from any liability, consequences or losses that may arise in the future.
                                </textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="dedicated_table" style="display:none">
                        <label class="col-sm-2 col-form-label">Dedicated Phone</label>
                        <div class="col-sm-10">
                            <table class="table table-bordered">
                                <tbody id="dedicated_phone_lists" style="display:none">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" name="product_id" id="product_id">
                        <input type="hidden" name="detail_price" id="detail_price">
                        <input type="hidden" name="price_type" id="price_type" @if(!empty($booking))value="{{$booking->price_type}}"@endif>
                        <input type="hidden" name="sub_total" id="sub_total">
                        <label class="col-sm-2 col-form-label">Detail Transaction</label>
                        <div class="col-sm-10">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th width="15%">#</th>
                                            <th width="25%">Description</th>
                                            <th width="20%">Length Of Term</th>
                                            <th width="20%">Quantity</th>
                                            <th width="20%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_transaction">

                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td colspan="4">
                                                Sub Total
                                            </td>
                                            <td id="view_sub_total" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Discount</td>
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
                                            <td colspan="4">Total Price</td>
                                            <td id="view_total_price" class="text-right"></td>
                                            <input type="hidden" id="total_price" name="total_price">
                                            <input type="hidden" id="total_service_charge" name="total_service_charge">
                                            <input type="hidden" id="total_tax_price" name="total_tax_price">
                                            <input type="hidden" id="round_price" name="round_price">
                                        </tr>
                                    </tbody>
                                    <tbody id="additional_charge" style="display:none">
                                    </tbody>
                                    <tfoot>
                                        <tr id="additional_charge_sum" style="display:none">
                                            <td colspan="4">Total Additional Charge</td>
                                            <td id="view_total_additional_charge" class="text-right"></td>
                                            <input type="hidden" id="total_additional_charge" name="total_additional_charge">
                                            <input type="hidden" id="total_tax_additional_charge" name="total_tax_additional_charge">
                                            <input type="hidden" id="total_service_charge_additional_charge" name="total_service_charge_additional_charge">
                                        </tr>
                                        <tr id="total_price_ac" style="display:none">
                                            <td colspan="4"><b>Total Price + Total Additional Charge</b></td>
                                            <td id="view_total_price_ac" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Service Charge Price</td>

                                            <td id="view_total_service_charge" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">VAT</td>
                                            <td>
                                                <div class="checkbox-radios">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="radio" name="tax_status" onclick="countAdditionalCharge();countPrice()" value="no_tax" @if(!empty($inquiry)) @if($inquiry->tax_status == "no_tax") checked @endif @elseif(!empty($booking)) @if($booking->tax_status == "no_tax") checked @endif @else checked @endif> No VAT
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
                                            <td colspan="4">Security Deposit</td>
                                            <td>
                                                <input type="text" id="format_security_deposit" class="form-control text-right" onchange="changeToCurrencyFormat('format_security_deposit','security_deposit');countPrice();" @if(empty($booking)) value="0" @else value="{{ number_format($booking->security_deposit, 0, ',', '.') }}" @endif>
                                                <input type="hidden" id="security_deposit" name="security_deposit" @if(empty($booking)) value="0" @else value="{{ $booking->security_deposit }}" @endif>
                                            </td>
                                        </tr>
                                        <tr style="display:none">
                                            <td colspan="4">Stamp Duty</td>
                                            <td>
                                                <input type="text" id="format_stamp_duty" class="form-control text-right" onchange="changeToCurrencyFormat('format_stamp_duty','stamp_duty');countPrice();" @if(empty($booking)) value="0" @else value="{{ number_format($booking->stamp_duty, 0, ',', '.') }}" @endif>
                                                <input type="hidden" id="stamp_duty" name="stamp_duty" @if(empty($booking)) value="0" @else value="{{ $booking->stamp_duty }}" @endif>
                                            </td>
                                        </tr>
                                        <tr style="display:none">
                                            <td colspan="4">Rounded Price</td>
                                            <td id="view_round_price" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Grand Total</td>
                                            <td id="view_grand_total" class="text-right"></td>
                                        </tr>
                                    </tfoot>
                                </table>
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
    var link_dedicated = "{{ url('dedicated_phone/get_by_id') }}";
    var type_of_transaction = 'product';
    var selected_product = new Array;
    var selected_other_product = new Array;
    var selected_dedicated_phone = new Array;
    var total_mod_rounding = {{ $total_mod_rounding }};
    var tax_percentage = {{ $tax_percentage }};
    var service_charge = {{ $service_charge }};
    var total_available_complimentary = 0;
    var complimentary_id = '';
    var new_item = new Array;

    @if(!empty($inquiry))
        getData();
        getContact('{{ url('contact/get_by_customer') }}', {{ $inquiry->customer_id }});
        addProduct({{ $inquiry->product_id }}, {{ $inquiry->quantity }});

        @foreach($inquiry->products as $no => $product)
            new_item = new Array;

            new_item['id'] = '{{ $product->id }}';
            new_item['name'] = '{{ $product->name }}';
            new_item['price_type'] = '{{ $product->price_type }}';
            new_item['price'] = '{{ $product->pivot->detail_price }}';
            new_item['qty'] = '{{ $product->pivot->quantity }}';
            new_item['is_editable_price'] = '{{ $product->is_editable_price }}';
            new_item['has_service_charge'] = '{{ $product->has_service_charge }}';
            new_item['start_date'] = '{{ $product->pivot->start_date }}';
            new_item['end_date'] = '{{ $product->pivot->end_date }}';
            new_item['start_time'] = '{{ $product->pivot->start_time }}';
            new_item['end_time'] = '{{ $product->pivot->end_time }}';
            new_item['length_of_term'] = '{{ $product->pivot->length_of_term }}';

            selected_other_product.push(new_item);
        @endforeach

        setAdditionalCharge();

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
        getData();
        getContact('{{ url('contact/get_by_customer') }}', {{ $booking->customer_id }});
        addProduct({{ $booking->product_id }}, {{ $booking->quantity }});
        @foreach($booking->products as $no => $product)
            new_item = new Array;
            new_item['id'] = '{{ $product->id }}';
            new_item['name'] = '{{ $product->name }}';
            new_item['price_type'] = '{{ $product->price_type }}';
            new_item['price'] = '{{ $product->pivot->detail_price }}';
            new_item['qty'] = '{{ $product->pivot->quantity }}';
            new_item['is_editable_price'] = '{{ $product->is_editable_price }}';
            new_item['has_service_charge'] = '{{ $product->has_service_charge }}';
            new_item['start_date'] = '{{ $product->pivot->start_date }}';
            new_item['end_date'] = '{{ $product->pivot->end_date }}';
            new_item['start_time'] = '{{ $product->pivot->start_time }}';
            new_item['end_time'] = '{{ $product->pivot->end_time }}';
            new_item['length_of_term'] = '{{ $product->pivot->length_of_term }}';
            selected_other_product.push(new_item);
        @endforeach

        setAdditionalCharge();

        @foreach($booking->dedicated_phones as $no => $dedicated_phone)
            var dedicated_phone_id = '{{ $dedicated_phone->id }}';
            var url_dedicated_phone = link_dedicated+"/"+dedicated_phone_id;
            $.get(url_dedicated_phone, function (data){
                item_dedicated = new Array;
                item_dedicated['id'] = '{{ $dedicated_phone->id }}';
                item_dedicated['number'] = '{{ $dedicated_phone->number }}';

                selected_dedicated_phone.push(item_dedicated);
                @if(sizeof($booking->dedicated_phones) == $no +1)
                    setDetailDedicatedPhone();
                @endif
            });
        @endforeach
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
            getDedicated()
        });
    });

    function selectInquiry(link, inquiry_id){
        var url = link+"/create?inquiry_id="+inquiry_id;

        window.location = url;
    }

    function getData(){
        var booking_id = null;
        var location_id = document.getElementById("location_id").value;

        if(location_id != ''){
            var link_product = "{{ url('product/get_by_location_id') }}";

            var url_product = link_product+"/"+location_id+'?main_status=Y';

            var product_list = '';

            $.get(url_product, function (data){
                product_list += '<option value="">--- Select Your Product ---</option>';
                for(var i=0; i < data.length; i++){
                    var selected = '';

                    product_list += '<option value="'+data[i]['id']+'" '+selected+'>'+data[i]['name']+'</option>';
                }
                document.getElementById("product_list").innerHTML = product_list;

                $('#product_list').selectpicker('refresh');
            });
        }
        getDedicated();
    }

    function getDedicated(){
        var location_id = document.getElementById("location_id").value;
        var booking_id = "";

        @if(!empty($booking))
            booking_id = '{{ $booking->id }}';
        @endif

        if(location_id != ''){
            var link = "{{ url('dedicated_phone/get_by_location_id') }}";

            var url = link+"/"+location_id+"?booking_id="+booking_id;

            var dedicated_phone_list = '';

            $.get(url, function (data){
                dedicated_phone_list += '<option value="">--- Select Phone Number ---</option>';
                for(var i=0; i < data.length; i++){
                    var selected = '';

                    dedicated_phone_list += '<option value="'+data[i]['id']+'" '+selected+'>'+data[i]['number']+'</option>';
                }
                document.getElementById("dedicated_phone_list").innerHTML = dedicated_phone_list;

                $('#dedicated_phone_list').selectpicker('refresh');
            });
        }

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
        var customer_id = document.getElementById("customer_id").value;
        var bank_account_id = document.getElementById("bank_account_id").value;
        var customer_status = document.getElementById("customer_status").value;
        var contact_status = $("input[name=contact_status]:checked").val();
        var contact_id = null;
        var contact_new_status = document.getElementById("contact_new_status").value;
        var product_id = document.getElementById("product_id").value;
        var price_type = document.getElementById("price_type").value;
        var start_date = document.getElementById("start_date").value;
        var length_of_term = document.getElementById("length_of_term").value;
        var term_of_payment = document.getElementById("term_of_payment").value;
        var end_date = document.getElementById("end_date").value;
        var start_time = document.getElementById("start_time").value;
        var end_time = document.getElementById("end_time").value;
        var signed_date = document.getElementById("signed_date").value;

        if(isEmpty(document.getElementById("contact_id"))){
            contact_id = document.getElementById("contact_id");
        }

        if(employee_id == ""){ // Cel Sales
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select employee</b> </span>'+
                            '</div>';
        }

        if(location_id == ""){ // Cek Location
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select location</b> </span>'+
                            '</div>';
        }

        if(bank_account_id == ""){ // Cek Bank
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select bank</b> </span>'+
                            '</div>';
        }

        if(term_of_payment == ""){ // Cek Term of Payment
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to input term of payment</b> </span>'+
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

        // Start : Type Transaction
        if(product_id == ""){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select product</b> </span>'+
                            '</div>';
        }

        if(bank_account_id == ""){ // Cek Bank
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select bank</b> </span>'+
                            '</div>';
        }
        // End : Type Transaction

        if(start_date == "" || length_of_term == "" || end_date == ""){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to put start date, length of term and end date</b> </span>'+
                            '</div>';
        }

        if(price_type == "hourly" || price_type == "halfday"){
            if(start_time == "" || end_time == ""){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to put start time and end time</b> </span>'+
                                '</div>';
            }
        }

        if(signed_date == ""){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to put signed date</b> </span>'+
                            '</div>';
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
        return true;
    }

    function addProduct(id = null, quantity = 1){
        var new_item = new Array;
        var product_id = document.getElementById("product_list").value;

        var link = "{{ url('product/get_by_id') }}";
        var availability = true;
        var price_type = '';

        if(id != null){
            product_id = id;
        }

        if(product_id != ""){
            var url = link+"/"+product_id;
            var product_price = 0;

            for(var i=0; i < selected_product.length; i++){
                if(selected_product[i].id == product_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                selected_product = new Array;
                $.get(url, function (data){
                    name = data['name'];
                    price_type = data['price_type'];
                    product_price = data['price'];

                    new_item['id'] = data['id'];
                    new_item['name'] = data['name'];
                    new_item['product_price'] = product_price;
                    new_item['price_type'] = data['price_type'];
                    new_item['quantity_status'] = data['quantity_status'];
                    new_item['has_service_charge']= data['has_service_charge'];
                    new_item['quantity'] = quantity;

                    selected_product.push(new_item);

                    document.getElementById("product_id").value = product_id;
                    document.getElementById("price_type").value = data['price_type'];
                    document.getElementById("detail_price").value = product_price;
                    setupPeriode(data['price_type']);

                    setDetailTransaction();
                    if(id == null) alert("New Product Added");
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }
    }

    function removeProduct(index){
        selected_product.splice(index, 1);
        setDetailTransaction();
    }

    function setDetailTransaction(){
        var detail_price = 0;
        var quantity = 1;
        var detail_transaction = '';
        var length_of_term = parseFloat(document.getElementById("length_of_term").value);
        var free_term_booking = parseFloat(document.getElementById("free_term_booking").value);
        var free_term_booking_view = '';

        if(free_term_booking > length_of_term){
            free_term_booking = 0;
            document.getElementById("free_term_booking").value = 0;
        }

        if(free_term_booking != ""){
            free_term_booking_view = ' - ('+free_term_booking+') ';
        }

        for(var i=0; i < selected_product.length; i++){
            var product_id = selected_product[i]['id'];
            var name = selected_product[i]['name'];
            var product_price = selected_product[i]['product_price'];
            var price_type = selected_product[i]['price_type'];
            var quantity_status = selected_product[i]['quantity_status'];
            var quantity = selected_product[i]['quantity'];

            detail_transaction += '<tr>';

            detail_transaction += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeProduct('+i+')"><i class="material-icons">remove</i></a></td>';
            detail_transaction += '<td class="text-center">'+name+'</td>';

            detail_transaction += '<td id="length_of_term_transaction" class="text-center">'+length_of_term+free_term_booking_view+'</td>';

            if(quantity_status == 'Y'){
                detail_transaction += '<td class="text-center"><input type="number" class="form-control text-center" name="quantity" id="quantity" min="1" value="'+quantity+'" onchange="countPrice()"></td>';
            }else{
                detail_transaction += '<td class="text-center"><input type="number" class="form-control text-center" name="quantity" id="quantity" min="1" value="'+quantity+'" readonly></td>';
            }

            detail_transaction += '<td id="sub_total_'+i+'" class="text-right">'+numberWithCommas(product_price * (length_of_term - free_term_booking) * quantity)+'</td>';

            detail_transaction += '</tr>';

            detail_price = detail_price + product_price;
        }

        document.getElementById("detail_price").value = parseFloat(detail_price);

        document.getElementById("detail_transaction").innerHTML = detail_transaction;

        countPrice();
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
        var nature_of_business = $("input[name='nature_of_business']").val();
        var contact_name = $("input[name='contact_name']").val();

        if(contact_name == '' && nature_of_business == ''){
            alert('Contact name and Nature Of Business is required');
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

    function setupPeriode(price_type, others=''){
        if(price_type == "hourly"){
            $('#start_to_end'+others).show();
            $('#datepicker'+others).show();
            document.getElementById("start_date"+others).readOnly = false;
            document.getElementById("length_of_term"+others).readOnly = false;
            document.getElementById("end_date"+others).readOnly = false;

            $('#timepicker'+others).show();
            document.getElementById("start_time"+others).readOnly = false;
            document.getElementById("end_time"+others).readOnly = false;

        }else if(price_type == "daily"){
            $('#start_to_end'+others).show();
            $('#datepicker'+others).show();
            document.getElementById("start_date"+others).readOnly = false;
            document.getElementById("length_of_term"+others).readOnly = false;
            document.getElementById("end_date"+others).readOnly = false;

            $('#timepicker'+others).hide();

        }else if(price_type == "monthly"){
            $('#start_to_end'+others).show();
            $('#datepicker'+others).show();
            document.getElementById("start_date"+others).readOnly = false;
            document.getElementById("length_of_term"+others).readOnly = false;
            document.getElementById("end_date"+others).readOnly = false;

            $('#timepicker'+others).hide();

        }else if(price_type == "yearly"){
            $('#start_to_end'+others).show();
            $('#datepicker'+others).show();
            document.getElementById("start_date"+others).readOnly = false;
            document.getElementById("length_of_term"+others).readOnly = false;
            document.getElementById("end_date"+others).readOnly = false;

            $('#timepicker'+others).hide();
        }else if(price_type == "halfday"){
            $('#start_to_end'+others).show();
            $('#datepicker'+others).show();
            document.getElementById("start_date"+others).readOnly = false;
            document.getElementById("length_of_term"+others).readOnly = true;
            document.getElementById("end_date"+others).readOnly = true;

            $('#timepicker'+others).show();
            document.getElementById("start_time"+others).readOnly = false;
            document.getElementById("end_time"+others).readOnly = true;

        }
    }

    function onPeriodeChanged(driven_by, others='', date_format=''){
        var start_date_counted = document.getElementById("start_date_counted").value;
        var price_type = document.getElementById("price_type"+others).value;
        var start_date = document.getElementById("start_date"+others).value;
        var length_of_term = document.getElementById("length_of_term"+others).value;
        var end_date = document.getElementById("end_date"+others).value;
        var start_time = document.getElementById("start_time"+others).value;
        var end_time = document.getElementById("end_time"+others).value;
        var link = "{{ url('setup_periode') }}";
        var url = link+"?driven_by="+driven_by+"&price_type="+price_type+"&start_date="+start_date+"&length_of_term="+length_of_term+"&end_date="+end_date+"&start_time="+start_time+"&end_time="+end_time+"&start_date_counted="+start_date_counted+"&date_format="+date_format;

        $.get(url, function (data){
            if(data['message'] == 'complete'){
                document.getElementById("start_date"+others).value = data['start_date'];
                document.getElementById("length_of_term"+others).value = data['length_of_term'];
                document.getElementById("end_date"+others).value = data['end_date'];
                document.getElementById("start_time"+others).value = data['start_time'];
                document.getElementById("end_time"+others).value = data['end_time'];
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
        var total_price = document.getElementById("total_price").value;
        var discount_price = 0;
        if(total_price > 0){
            if(usable_discount == 'percentage'){
                discount_price = total_price * (discount_value/100);
            }else if(usable_discount == 'price'){
                discount_price = document.getElementById("discount_price").value;
            }
            document.getElementById("format_discount_price").value = numberWithCommas(discount_price);
            document.getElementById("discount_price").value = discount_price;
            countPrice();
        }
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
                    new_item['price_type'] = data['price_type'];
                    new_item['price'] = data['price'];
                    new_item['qty'] = 1;
                    new_item['is_editable_price'] = data['is_editable_price'];
                    new_item['has_service_charge']= data['has_service_charge'];
                    new_item['quantity_status'] = data['quantity_status'];
                    new_item['start_date'] = "{{ date('Y-m-d') }}";
                    new_item['end_date'] = "";
                    new_item['start_time'] = "";
                    new_item['end_time'] = "";
                    new_item['length_of_term'] = "1";

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

    function setAdditionalCharge(){
        var additional_charge = '';
        if(selected_other_product.length > 0){
            additional_charge += '<tr>';
                additional_charge += '<td colspan="5"><b>Additional Charge</b></td>';
            additional_charge += '</tr>';
            additional_charge += '<tr>';
                additional_charge += '<td class="text-center">#</td>';
                additional_charge += '<td class="text-center" >Item Name</td>';
                additional_charge += '<td class="text-center">Detail Price</td>';
                additional_charge += '<td class="text-center">Qty</td>';
                additional_charge += '<td class="text-center">Total</td>';
            additional_charge += '</tr>';
            for(var i=0; i < selected_other_product.length; i++){
                var product_id = selected_other_product[i].id;
                var product_name = selected_other_product[i].name;
                var price_type = selected_other_product[i].price_type;
                var product_price = selected_other_product[i].price;
                var quantity = selected_other_product[i].qty;
                var start_date = selected_other_product[i].start_date;
                var end_date = selected_other_product[i].end_date;
                var start_time = selected_other_product[i].start_time;
                var end_time = selected_other_product[i].end_time;
                var length_of_term = selected_other_product[i].length_of_term;

                additional_charge += '<tr>';
                additional_charge += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeAdditionalCharge('+i+')"><i class="material-icons">remove</i></a></td>';
                additional_charge += '<td>';
                    additional_charge += '<div class="row">';
                        additional_charge += '<div class="col-sm-12">';
                            additional_charge += '<div class="form-group text-center">';
                                additional_charge += '<input type="hidden" name="other_product_id[]" value="'+product_id+'">'+product_name;
                            additional_charge += '</div>';
                        additional_charge += '</div>';
                    additional_charge += '</div>';
                    additional_charge += '<div id="start_to_end_'+i+'" style="display:none;">';
                        additional_charge += '<div class="row" id="datepicker_'+i+'">';
                            additional_charge += '<div class="col-sm-12">';
                                additional_charge += '<div class="form-group">';
                                    additional_charge += '<input type="text" name="ac_price_type[]" id="price_type_'+i+'" value="'+price_type+'" class="form-control text-center" readonly>';
                                additional_charge += '</div>';
                            additional_charge += '</div>';
                            additional_charge += '<div class="col-sm-12">';
                                additional_charge += '<div class="form-group">';
                                    additional_charge += '<input type="date" name="ac_start_date[]" id="start_date_'+i+'" value="'+start_date+'" class="form-control text-center" placeholder="Start Date" onchange="onPeriodeChanged('+"'start_date'"+', '+"'_"+i+"'"+', '+"'Y-m-d'"+')">';
                                additional_charge += '</div>';
                            additional_charge += '</div>';
                            additional_charge += '<div class="col-sm-12">';
                                additional_charge += '<div class="form-group">';
                                    additional_charge += '<input type="number" name="ac_length_of_term[]" id="length_of_term_'+i+'" min="1" value="'+length_of_term+'" class="form-control text-center"  placeholder="Length Of Term" onchange="onPeriodeChanged('+"'length_of_term'"+', '+"'_"+i+"'"+', '+"'Y-m-d'"+');countAdditionalCharge();">';
                                additional_charge += '</div>';
                            additional_charge += '</div>';
                            additional_charge += '<div class="col-sm-12">';
                                additional_charge += '<div class="form-group">';
                                    additional_charge += '<input type="date" name="ac_end_date[]" id="end_date_'+i+'" value="'+end_date+'" class="form-control text-center" placeholder="End Date" onchange="onPeriodeChanged('+"'end_date'"+', '+"'_"+i+"'"+', '+"'Y-m-d'"+')">';
                                additional_charge += '</div>';
                            additional_charge += '</div>';
                        additional_charge += '</div>';
                        additional_charge += '<div class="row" id="timepicker_'+i+'">';
                            additional_charge += '<div class="col-sm-12">';
                                additional_charge += '<div class="form-group">';
                                    additional_charge += '<input type="time" name="ac_start_time[]" id="start_time_'+i+'" value="'+start_time+'" class="form-control timepicker text-center" placeholder="Start Time" onchange="onPeriodeChanged('+"'start_time'"+', '+"'_"+i+"'"+')">';
                                additional_charge += '</div>';
                            additional_charge += '</div>';
                            additional_charge += '<div class="col-sm-12">';
                                additional_charge += '<div class="form-group">';
                                    additional_charge += '<input type="time" name="ac_end_time[]" id="end_time_'+i+'" value="'+end_time+'" class="form-control timepicker text-center" placeholder="End Time" onchange="onPeriodeChanged('+"'end_time'"+', '+"'_"+i+"'"+')">';
                                additional_charge += '</div>';
                            additional_charge += '</div>';
                        additional_charge += '</div>';
                    additional_charge += '</div>';
                additional_charge += '</td>';

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
            }
        }else{
            $("#additional_charge").hide();
            $("#additional_charge_sum").hide();
            $("#total_price_ac").hide();
            document.getElementById("additional_charge").innerHTML = "";
            document.getElementById("total_additional_charge").value = 0;
        }

        document.getElementById("additional_charge").innerHTML = additional_charge;
        countAdditionalCharge();
    }

    function countAdditionalCharge(){
        var total_additional_charge = 0;
        document.getElementById("total_additional_charge").value = 0;

        // Start : For Additional Charge
        for(var i=0; i < selected_other_product.length; i++){
            var product_id = selected_other_product[i].id;
            var ac_detail_price = parseFloat(document.getElementById("ac_detail_price_"+product_id).value);
            var ac_quantity = parseFloat(document.getElementById("ac_quantity_"+product_id).value);
            var ac_length_of_term = parseFloat(document.getElementById("length_of_term_"+i).value);
            var ac_sub_total = ac_detail_price * ac_quantity * ac_length_of_term;
            var price_type = selected_other_product[i].price_type;

            total_additional_charge = parseFloat(total_additional_charge) + parseFloat(ac_sub_total);

            selected_other_product[i].price = ac_detail_price;
            selected_other_product[i].qty = ac_quantity;
            selected_other_product[i].length_of_term = ac_length_of_term;

            setupPeriode(price_type, '_'+i);
            onPeriodeChanged('start_date', '_'+i, 'Y-m-d');

            document.getElementById("ac_sub_total_"+product_id).innerHTML = numberWithCommas(ac_sub_total);
        }
        document.getElementById("total_additional_charge").value = total_additional_charge;
        countPrice();
        // End : For Additional Charge
    }

    function addDedicatedPhone(id = null){
        var new_item = new Array;
        var dedicated_phone_id = document.getElementById("dedicated_phone_list").value;
        var link = "{{ url('dedicated_phone/get_by_id') }}";
        var availability = true;

        if(id != null){
            dedicated_phone_id = id;
        }

        if(dedicated_phone_id != ""){
            var url = link+"/"+dedicated_phone_id;

            for(var i=0; i < selected_dedicated_phone.length; i++){
                if(selected_dedicated_phone[i].id == dedicated_phone_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                $.get(url, function (data){

                    new_item['id'] = data['id'];
                    new_item['number'] = data['number'];

                    selected_dedicated_phone.push(new_item);

                    setDetailDedicatedPhone();
                    if(id == null) alert("New Dedicated Phone Added");
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }

    }

    function removeDedicatedPhone(index){
        selected_dedicated_phone.splice(index, 1);
        setDetailDedicatedPhone();
    }

    function setDetailDedicatedPhone(){

        var dedicated_phone = '';
        if(selected_dedicated_phone.length > 0){
            dedicated_phone += '<tr>';
                dedicated_phone += '<td colspan="6"><b>Dedicated Phone</b></td>';
            dedicated_phone += '</tr>';
            dedicated_phone += '<tr>';
                dedicated_phone += '<td class="text-center">#</td>';
                dedicated_phone += '<td class="text-center" colspan="5">Number</td>';
            dedicated_phone += '</tr>';
            for(var i=0; i < selected_dedicated_phone.length; i++){

                var dedicated_phone_id = selected_dedicated_phone[i].id;
                var dedicated_phone_number = selected_dedicated_phone[i].number;

                dedicated_phone += '<tr>';
                dedicated_phone += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeDedicatedPhone('+i+')"><i class="material-icons">remove</i></a></td>';
                dedicated_phone += '<td colspan="5" class="text-center"><input type="hidden" name="dedicated_phone_id[]" value="'+dedicated_phone_id+'">'+dedicated_phone_number+'</td>';


                if(i == selected_dedicated_phone.length - 1){
                    dedicated_phone += '';
                }else{
                    dedicated_phone += '</tr>';
                }

                $("#dedicated_table").show();
                $("#dedicated_phone_lists").show();
            }
        }else{
            $("#dedicated_table").hide();
            $("#dedicated_phone_lists").hide();

        }
        document.getElementById("dedicated_phone_lists").innerHTML = dedicated_phone;
    }

    function countPrice(){
        var free_term_booking = document.getElementById("free_term_booking").value;
        var price_type = document.getElementById("price_type").value;
        var product_price = 0;
        var length_of_term = 1;
        var quantity = 1;
        var detail_price = parseFloat(document.getElementById("detail_price").value);
        var discount_price = parseFloat(document.getElementById("discount_price").value);
        var security_deposit = parseFloat(document.getElementById("security_deposit").value);
        var stamp_duty = parseFloat(document.getElementById("stamp_duty").value);

        var total_price = 0;
        var total_tax_price = 0;
        var total_service_charge = 0;

        var total_additional_charge = 0;
        var total_tax_additional_charge = 0;
        var total_service_charge_additional_charge = 0;

        var tax_status = $("input[name=tax_status]:checked").val();
        var sub_total = 0;
        var round_price = 0;
        var grand_total = 0;

        // Start : For Additional Charge
        for(var i=0; i < selected_other_product.length; i++){
            var product_id = selected_other_product[i].id;
            var service_charge_status = selected_other_product[i].has_service_charge;
            var detail_service_charges = 0;
            var detail_tax_price = 0;

            var ac_detail_price = parseFloat(document.getElementById("ac_detail_price_"+product_id).value);
            var ac_quantity = parseFloat(document.getElementById("ac_quantity_"+product_id).value);
            var ac_length_of_term = parseFloat(document.getElementById("length_of_term_"+i).value);

            selected_other_product[i].price = ac_detail_price;
            selected_other_product[i].qty = ac_quantity;

            ac_sub_total = ac_detail_price * ac_quantity * ac_length_of_term;

            // Start : For Service Charge & VAT
            if(service_charge_status == null){
                detail_service_charges = 0;
            }else{
                if(service_charge_status == "Y"){
                    detail_service_charges = ac_sub_total * parseFloat(service_charge);
                }else{
                    detail_service_charges = 0;
                }
            }
            if(tax_status == 'no_tax'){
                detail_tax_price = 0;
            }else if(tax_status == 'exclude'){
                detail_tax_price = parseFloat(parseFloat(ac_sub_total) + parseFloat(detail_service_charges)) * parseFloat(tax_percentage);
            }else if(tax_status == 'include'){
                temp_1 = ac_sub_total;
                ac_sub_total = parseFloat(ac_sub_total) / (1 + parseFloat(tax_percentage));
                detail_tax_price = parseFloat(temp_1) - parseFloat(ac_sub_total);

                if(detail_service_charges > 0){
                    temp_2 = ac_sub_total;
                    ac_sub_total = parseFloat(ac_sub_total) / (1 + parseFloat(service_charge));
                    detail_service_charges = parseFloat(temp_2) - parseFloat(ac_sub_total);
                }
            }

            document.getElementById("ac_sub_total_"+product_id).innerHTML = numberWithCommas(Math.round(ac_sub_total));

            total_additional_charge = parseFloat(total_additional_charge) + parseFloat(ac_sub_total);
            total_service_charge_additional_charge = parseFloat(total_service_charge_additional_charge) + parseFloat(detail_service_charges);
            total_tax_additional_charge = parseFloat(total_tax_additional_charge) + parseFloat(detail_tax_price);
        }

        for(var i=0; i < selected_product.length; i++){
            var service_charge_status = selected_product[i].has_service_charge;

            product_price = $("#detail_price").val();
            length_of_term = parseFloat($("#length_of_term").val());
            quantity = parseFloat($("#quantity").val());

            sub_total = sub_total +  (product_price * quantity * (length_of_term - free_term_booking));

            document.getElementById("sub_total_"+i).innerHTML = numberWithCommas(product_price * quantity * length_of_term);

        }

        total_price = sub_total - discount_price;

        // Start : For Service Charge & VAT
        if(service_charge_status == null){
            total_service_charge = 0;
        }else{
            if(service_charge_status == "Y"){
                total_service_charge = total_price * parseFloat(service_charge);
            }else{
                total_service_charge = 0;
            }
        }
        if(tax_status == 'no_tax'){
            total_tax_price = 0;
        }else if(tax_status == 'exclude'){
            total_tax_price = parseFloat(parseFloat(total_price) + parseFloat(total_service_charge)) * parseFloat(tax_percentage);
        }else if(tax_status == 'include'){
            temp_1 = total_price;
            total_price = parseFloat(total_price) / (1 + parseFloat(tax_percentage));
            total_tax_price = parseFloat(temp_1) - parseFloat(total_price);

            if(service_charge_status == "Y"){
                temp_2 = total_price;
                total_price = parseFloat(total_price) / (1 + parseFloat(service_charge));
                total_service_charge = parseFloat(temp_2) - parseFloat(total_price);
            }
        }

        total_price = Math.round(total_price);
        total_service_charge = Math.round(total_service_charge);
        total_tax_price = Math.round(total_tax_price);

        total_additional_charge = Math.round(total_additional_charge);
        total_service_charge_additional_charge = Math.round(total_service_charge_additional_charge);
        total_tax_additional_charge = Math.round(total_tax_additional_charge);

        var grand_total_tax = parseFloat(total_tax_price) + parseFloat(total_tax_additional_charge);
        var view_total_price_ac = parseFloat(total_price) + parseFloat(total_additional_charge);
        var view_total_service_charge = parseFloat(total_service_charge) + parseFloat(total_service_charge_additional_charge);

        grand_total = parseFloat(total_price) + parseFloat(total_service_charge) + parseFloat(total_tax_price);
        grand_total = parseFloat(total_additional_charge) + parseFloat(total_service_charge_additional_charge) + parseFloat(total_tax_additional_charge) + parseFloat(grand_total);
        grand_total = parseFloat(grand_total) + parseFloat(security_deposit) + parseFloat(stamp_duty);

        var mod_result = parseFloat(grand_total) % parseFloat(total_mod_rounding);
        var middle_number = parseFloat(total_mod_rounding / 2);
        if(mod_result <= middle_number){
            round_price = parseFloat(mod_result);
            grand_total = parseFloat(grand_total) - parseFloat(round_price);
            round_price = parseFloat(round_price) * -1;
        }else{
            round_price = parseFloat(total_mod_rounding) - parseFloat(mod_result);
            grand_total = parseFloat(grand_total) + parseFloat(round_price);
        }

        document.getElementById("sub_total").value = sub_total;
        document.getElementById("total_price").value = total_price;
        document.getElementById("total_service_charge").value = total_service_charge;
        document.getElementById("total_tax_price").value = total_tax_price;
        document.getElementById("total_additional_charge").value = total_additional_charge;
        document.getElementById("total_service_charge_additional_charge").value = total_service_charge_additional_charge;
        document.getElementById("total_tax_additional_charge").value = total_tax_additional_charge;
        document.getElementById("round_price").value = round_price;
        document.getElementById("view_sub_total").innerHTML = numberWithCommas(parseInt(sub_total));
        document.getElementById("view_total_price").innerHTML = numberWithCommas(parseInt(total_price));
        document.getElementById("view_total_additional_charge").innerHTML = numberWithCommas(parseInt(total_additional_charge));
        document.getElementById("view_total_price_ac").innerHTML = numberWithCommas(parseInt(view_total_price_ac));
        document.getElementById("view_total_service_charge").innerHTML = numberWithCommas(parseInt(view_total_service_charge));
        document.getElementById("view_total_tax_price").innerHTML = numberWithCommas(parseInt(grand_total_tax));
        document.getElementById("view_round_price").innerHTML = numberWithCommas(parseInt(round_price));
        document.getElementById("view_grand_total").innerHTML = numberWithCommas(parseInt(grand_total));
    }
</script>
@endsection
