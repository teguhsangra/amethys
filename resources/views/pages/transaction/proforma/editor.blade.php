@extends('layouts.app')

@section('title')
Rakomsis Proforma - Editor
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
                    Proforma Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Proforma
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Has PO</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="has_po" onclick="$('#po_number').show();" value="Y" 
                                     @if(!empty($proforma)) @if($proforma->has_po == 'Y') checked @else @endif @endif
                                    > Yes
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="has_po" onclick="$('#po_number').hide();" value="N" 
                                    @if(!empty($proforma)) @if($proforma->has_po == 'N') checked @else @endif @else checked  @endif
                                    > No
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="po_number" style="display:none;">
                        <label class="col-sm-2 col-form-label">PO Number</label>
                    	<div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('po_number') ? ' has-error' : '' }}">
                                <input type="text" name="po_number" id="po_number" class="form-control"  @if(!empty($proforma)) value="{{ $proforma->po_number }}" @endif>
                                <label class="error">{{ $errors->first('po_number') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="company">
                        <label class="col-sm-2 col-form-label">Company</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$proforma->company->name}}" readonly>
                                    <input type="hidden" name="company_id" id="company_id" value="{{$proforma->company_id}}">
                                @else
                                <select class="selectpicker form-control" name="company_id" id="company_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($company as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($proforma)){
                                                if($proforma->company_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->code }} : {{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('company_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="bank_account">
                        <label class="col-sm-2 col-form-label">Bank Account</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('bank_account_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$proforma->bank_account->bank_name}} : {{$proforma->bank_account->account_no}} / {{$proforma->bank_account->account_name}}" readonly>
                                    <input type="hidden" name="bank_account_id" id="bank_account_id" value="{{$proforma->bank_account_id}}">
                                @else
                                <select class="selectpicker form-control" name="bank_account_id" id="bank_account_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($bank_accounts as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($proforma)){
                                                if($proforma->bank_account_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->bank_name }} : {{$detail->account_no}} / {{$detail->account_name}}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($proforma))
                                    <input type="text" class="form-control" value="{{$proforma->location->name}}" readonly>
                                    <input type="hidden" name="location_id" id="location_id" value="{{$proforma->location_id}}">
                                @else
                                    <select class="selectpicker form-control" name="location_id" id="location_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($proforma)){
                                                    if($proforma->location_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                                if(!empty(Request::get('location_id'))){
                                                    if(Request::get('location_id') == $detail->id){
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
                    <div class="row" id="customer">
                        <label class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($proforma))
                                    <input type="text" class="form-control" value="{{$proforma->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$proforma->customer_id}}">
                                @else
                                    <select class="selectpicker form-control" name="customer_id" id="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true" >
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($customers as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($proforma)){
                                                    if($proforma->customer_id == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                                if(!empty(Request::get('customer_id'))){
                                                    if(Request::get('customer_id') == $detail->id){
                                                        $selected = 'selected';
                                                    }
                                                }
                                            @endphp
                                            <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="customer_contact" style='display:none'>
                        <label class="col-sm-2 col-form-label">Customer Contact</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('contact_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($proforma))
                                    <input type="text" class="form-control" value="{{$proforma->contact->name}}" readonly>
                                    <input type="hidden" name="contact_id" id="contact_id" value="{{$proforma->contact_id}}">
                                @else
                                    <select class="selectpicker form-control" name="contact_id" id="contact_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true" >

                                    </select>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Detail Status</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('detail_status') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="detail_status" id="detail_status" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                    <option value="">Please select one</option>
                                    <option value="Y" @if(!empty($proforma)) @if($proforma->detail_status == "Y") selected @endif @endif>Show Detail</option>
                                    <option value="N" @if(!empty($proforma)) @if($proforma->detail_status == "N") selected @endif @endif>Not Show</option>
                                </select>
                            </div>
                        </div>
                        <label class="error">{{ $errors->first('detail_status') }}</label>
                    </div>
                    <div class="row" id="custom_status_selector">
                        <label class="col-sm-2 col-form-label">Custom Status</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('custom_status') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="custom_status" id="custom_status" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                    <option value="">Please select one</option>
                                    <option value="Y" @if(!empty($proforma)) @if($proforma->custom_status == "Y") selected @endif @endif @if(!empty(Request::get('type'))) @if(Request::get('type') == 'booking' || Request::get('order')) selected @endif @endif>Custom</option>
                                    <option value="N" @if(!empty($proforma)) @if($proforma->custom_status == "N") selected @endif @endif @if(!empty(Request::get('type'))) @if(Request::get('type') == 'booking_detail' || Request::get('order_detail')) selected @endif @endif>Not Custom</option>
                                </select>
                            </div>
                        </div>
                        <label class="error">{{ $errors->first('custom_status') }}</label>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Proforma Date</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('proforma_date') ? ' has-error' : '' }}">
                                <input type="text" name="proforma_date" id="proforma_date" class="form-control datepicker" @if(!empty($proforma)) value="{{ date('m/d/Y', strtotime($proforma->proforma_date)) }}" @else value="{{ date('m/d/Y') }}" @endif>
                                <label class="error">{{ $errors->first('proforma_date') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Due Date</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
                                <input type="text" name="due_date" id="due_date" class="form-control datepicker" @if(!empty($proforma)) value="{{ date('m/d/Y', strtotime($proforma->due_date)) }}" @endif>
                                <label class="error">{{ $errors->first('due_date') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="deposit">
                        <label class="col-sm-2 col-form-label">Deposit</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="deposit_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addDeposit()">
                                        <i class="material-icons">add</i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Has Deduction</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="has_deduction" id="has_deduction" onclick="$('#deduction_price').show();" value="Y" 
                                     @if(!empty($proforma)) @if($proforma->has_deduction == 'Y') checked @else @endif   @endif
                                    > Yes
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="has_deduction" id="has_deduction" onclick="$('#deduction_price').hide();" value="N" 
                                    @if(!empty($proforma)) @if($proforma->has_deduction == 'N') checked @else @endif @else checked  @endif
                                    > No
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="isCustom" style="display:none">
                        <label class="col-sm-2 col-form-label">Custom</label>
                        <div class="col-sm-5">
                            <div class="card card-info">
                                <div class="card-header card-header-info card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">library_books</i>
                                    </div>
                                    <h4 class="card-title">
                                        Proforma Custom Detail
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Source</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <select class="selectpicker form-control" name="selection" id="selection_from" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                                    <option value="">Please select one</option>
                                                    <option value="booking" @if(!empty($proforma)) @if(!empty($proforma->booking_id)) selected @endif @endif>Booking</option>
                                                    <option value="order" @if(!empty($proforma)) @if(!empty($proforma->order_id)) selected @endif @endif>Order</option>
                                                    <option value="inquiry" @if(!empty($proforma)) @if(!empty($proforma->inquiry_id)) selected @endif @endif>Inquiry</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="bookingForm" style="display:none;">
                                        <label class="col-sm-3 col-form-label">Booking</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <select class="selectpicker form-control" name="booking_id" id="booking_id" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                                    <option value="">Please select one</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="orderForm" style="display:none;">
                                        <label class="col-sm-3 col-form-label">Order</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <select class="selectpicker form-control" name="order_id" id="order_id" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                                    <option value="">Please select one</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="inquiryForm" style="display:none;">
                                        <label class="col-sm-3 col-form-label">Inquiry</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <select class="selectpicker form-control" name="inquiry_id" id="inquiry_id" data-size="5" data-style="select-with-transition" data-show-subtext="true">
                                                    <option value="">Please select one</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Total Unpaid</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control text-right" id="format_total_unpaid" readonly/>
                                                <input type="hidden" class="form-control" id="total_unpaid" value="0"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Custom Price</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control text-right" id="format_custom_price"
                                                    onchange="changeToCurrencyFormat('format_custom_price','custom_price');"
                                                        @if(!empty($proforma)) value="{{ number_format($proforma->total_price, 0, ',', '.') }}" @endif />
                                                <input type="hidden" class="form-control" name="custom_price" id="custom_price"
                                                    @if(!empty($proforma)) value="{{ $proforma->total_price }}" @else  value="0" @endif />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Total Service Charge</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control text-right" id="format_custom_service_charge"
                                                    onchange="changeToCurrencyFormat('format_custom_service_charge','custom_service_charge');"
                                                        @if(!empty($proforma)) value="{{ number_format($proforma->total_service_charge, 0, ',', '.') }}" @endif />
                                                <input type="hidden" class="form-control" name="custom_service_charge" id="custom_service_charge"
                                                    @if(!empty($proforma)) value="{{ $proforma->total_service_charge }}" @endif />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-3 col-form-label">Custom Tax Price</label>
                                        <div class="col-sm-9">
                                            <div class="form-group bmd-form-group">
                                                <input type="text" class="form-control text-right" id="format_custom_tax_price"
                                                    onchange="changeToCurrencyFormat('format_custom_tax_price','custom_tax_price');"
                                                        @if(!empty($proforma)) value="{{ number_format($proforma->total_tax_price, 0, ',', '.') }}" @endif />
                                                <input type="hidden" class="form-control" name="custom_tax_price" id="custom_tax_price" value="0"
                                                    @if(!empty($proforma)) value="{{ $proforma->total_tax_price }}" @endif/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="row">
                                <div class="card card-rose">
                                    <div class="card-header card-header-rose card-header-icon">
                                        <div class="card-icon">
                                            <i class="material-icons">library_books</i>
                                        </div>
                                        <h4 class="card-title">
                                            Created Proforma & Invoice
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <ul class="nav nav-pills nav-pills-warning" id="customTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="ma-proforma-tab" data-toggle="tab" href="#ma-proforma" role="tab" aria-controls="info" aria-selected="true">Proforma</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="ma-invoice-tab"  data-toggle="tab" href="#ma-invoice" role="tab" aria-controls="doc" aria-selected="false">Invoice</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content tab-space">
                                            <div class="tab-pane show active" id="ma-proforma" role="tabpanel" aria-labelledby="ma-proforma-tab">
                                                <table class="table table-bordered table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th>Proforma Code</th>
                                                            <th>Total Price</th>
                                                            <th>Total Tax</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="proforma_detail_by_reference"></tbody>
                                                </table>
                                            </div>
                                            <div class="tab-pane" id="ma-invoice" role="tabpanel" aria-labelledby="ma-invoice-tab">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Invoice Code</th>
                                                            <th>Total Price</th>
                                                            <th>Total Tax</th>
                                                            <th>Sub Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="invoice_detail_by_reference"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="isNotCustom" style="display:none">
                        <label class="col-sm-2 col-form-label">Not Custom</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills nav-pills-warning" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="ma-serviced_office-tab" data-toggle="tab" href="#ma-serviced_office" role="tab" aria-controls="info" aria-selected="true">Serviced Office</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="ma-virtual_office-tab" data-toggle="tab" href="#ma-virtual_office" role="tab" aria-controls="info" aria-selected="true">Virtual Office</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="ma-meeting-tab" data-toggle="tab" href="#ma-meeting" role="tab" aria-controls="info" aria-selected="true">Meeting Room</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="ma-coworking-tab" data-toggle="tab" href="#ma-coworking" role="tab" aria-controls="info" aria-selected="true">Workstation</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " id="ma-hotel-tab" data-toggle="tab" href="#ma-hotel" role="tab" aria-controls="info" aria-selected="true">Hotel</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="ma-regular_office-tab"  data-toggle="tab" href="#ma-regular_office" role="tab" aria-controls="doc" aria-selected="false">Regular Office</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="ma-order-tab"  data-toggle="tab" href="#ma-order" role="tab" aria-controls="doc" aria-selected="false">Order</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="ma-cancellation-tab" data-toggle="tab" href="#ma-cancellation" role="tab" aria-controls="doc" aria-selected="false">Cancellation</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content tab-space">
                                        <div class="tab-pane show active" id="ma-serviced_office" role="tabpanel" aria-labelledby="ma-serviced_office-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Serviced Office</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_serviced_office">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_serviced_office">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-virtual_office" role="tabpanel" aria-labelledby="ma-virtual_office-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Virtual Office</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_virtual_office">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_virtual_office">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-meeting" role="tabpanel" aria-labelledby="ma-meeting-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Meeting Room</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_meeting_room">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_meeting_room">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-coworking" role="tabpanel" aria-labelledby="ma-coworking-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Workstation</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_coworking">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_coworking">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-hotel" role="tabpanel" aria-labelledby="ma-hotel-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Hotel</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_hotel">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_hotel">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-regular_office" role="tabpanel" aria-labelledby="ma-regular_office-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Regular Office</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_regular_office">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_regular_office">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-order" role="tabpanel" aria-labelledby="ma-order-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Order</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_order_detail">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                                <th>Quantity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_order_detail">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="ma-cancellation" role="tabpanel" aria-labelledby="ma-cancellation-tab">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4>Cancellation</h4>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label">
                                                                            <input class="form-check-input" type="checkbox" id="select_all_cancellation">
                                                                            <span class="form-check-sign">
                                                                            <span class="check"></span>
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </th>
                                                                <th>Period</th>
                                                                <th>Description</th>
                                                                <th>Price</th>
                                                                <th>Service Charge</th>
                                                                <th>Tax Price</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail_cancellation">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="proforma_detail">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                        <div class="card">
                            <div class="card-header">
                                <h4>Proforma Detail</h4>
                            </div>
                            <div class="card-body">
                                <div class="material-datatables table-responsive">
                                    <table width="100%" class="table table-striped table-bordered table-hover">
                                        <thead class="text-primary text-center">
                                            <tr>
                                                <th></th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Service Charge</th>
                                                <th>Tax Price</th>
                                                <th>Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="row_proforma_details">

                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <th colspan="3">Deposit Code</th>
                                                <th colspan="2">Deposit Type</th>
                                                <th>Total Deposit</th>
                                            </tr>
                                        </tbody>
                                        <tbody id="row_deposit_details">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6">Total Price</td>
                                                <td>
                                                    <input type="text" class="form-control text-right" id="format_total_price" readonly="readonly" style="width:200px !important;"  />
                                                    <input type="hidden" class="form-control" id="total_price" name="total_price" readonly="readonly" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">Total Service Charge</td>
                                                <td>
                                                    <input type="text" class="form-control text-right" id="format_total_service_charge" readonly="readonly" style="width:200px !important;"  />
                                                    <input type="hidden" class="form-control" id="total_service_charge" name="total_service_charge" readonly="readonly" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">Tax Base for VAT</td>
                                                <td>
                                                    <input type="text" class="form-control text-right" id="format_price_on_tax"  style="width:200px !important;"  onchange="changeToCurrencyFormat('format_price_on_tax','total_price_on_tax');"/>
                                                    <input type="hidden" class="form-control" id="total_price_on_tax" name="total_price_on_tax" readonly="readonly" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">Total Tax Price</td>
                                                <td>
                                                    <input type="text" class="form-control text-right" id="format_total_tax_price" readonly="readonly" style="width:200px !important;"  />
                                                    <input type="hidden" class="form-control" id="total_tax_price" name="total_tax_price" readonly="readonly" />
                                                </td>
                                            </tr>
                                            <tr style="display:none">
                                                <td colspan="6">Stamp Duty (Materai)</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" id="format_stamp_duty" onchange="changeToCurrencyFormat('format_stamp_duty','stamp_duty');setDetailTransaction();" @if(!empty($proforma)) value="{{ number_format($proforma->stamp_duty, 0, ',' ,'.') }}" @else value="0" @endif />
                                                    <input type="hidden" class="form-control" name="stamp_duty" id="stamp_duty" @if(!empty($proforma)) value="{{ $proforma->stamp_duty }}" @else value="0" @endif />
                                                </td>
                                            </tr>
                                            <tr style="display:none">
                                                <td colspan="6">Rounded Price</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" id="view_round_price" readonly="readonly" style="width:200px !important;" />
                                                    <input type="hidden" id="round_price" name="round_price">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">Total Deposit</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" id="view_total_deposit" readonly="readonly" style="width:200px !important;" />
                                                    <input type="hidden" id="total_deposit" name="total_deposit" value="0">
                                                </td>
                                            </tr>
                                            <tr id="deduction_price" style="display:none;">
                                                <td colspan="6">Deduction Withholding Tax</td>
                                                <td class="text-right">
                                                    <input type="text" class="form-control text-right" id="format_deduction_price" onchange="changeToCurrencyFormat('format_deduction_price','deduction_price_text');setDetailTransaction();" @if(!empty($proforma)) value="{{ number_format($proforma->deduction_price, 0, ',' ,'.') }}" @else value="0" @endif />
                                                    <input type="hidden" class="form-control" name="deduction_price" id="deduction_price_text" @if(!empty($proforma)) value="{{ $proforma->deduction_price }}" @else value="0" @endif />
                                                </td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="6">Grand Total</td>
                                                <td>
                                                    <input type="text" class="form-control text-right" id="grand_total" readonly="readonly" style="width:200px !important;" />
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group bmd-form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                                        <textarea class="form-control" rows="5" name="desc" id="desc" placeholder="Description..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($proforma)){{ $proforma->desc }}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <label class="error">{{ $errors->first('desc') }}</label>
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
    var total_mod_rounding = {{ $total_mod_rounding }};
    var serviced_offices = new Array;
    var virtual_office = new Array;
    var meeting_room = new Array;
    var coworking = new Array;
    var hotel = new Array;
    var regular_offices = new Array;
    var order_details = new Array;
    var cancellations = new Array;
    var proforma_details = new Array;
    var deposits = new Array;

    @if(!empty($proforma))
        getProformaData();
        getDeposit();

        @if($proforma->custom_status == "Y")
            $("#isNotCustom").hide();
            $("#isCustom").show();
            $("#proforma_detail").hide();
        @else
            $("#isNotCustom").show();
            $("#isCustom").hide();
            $("#proforma_detail").show();
        @endif

        @if($proforma->booking_id != null)
            $("#bookingForm").show();
            $("#orderForm").hide();
            $("#inquiryForm").hide();
            getBooking();
        @elseif($proforma->order_id != null)
            $("#bookingForm").hide();
            $("#orderForm").show();
            $("#inquiryForm").hide();
            getOrder();
        @elseif($proforma->inquiry_id != null)
            $("#bookingForm").hide();
            $("#orderForm").hide();
            $("#inquiryForm").show();
            getInquiry();
        @else
            $("#bookingForm").hide();
            $("#orderForm").hide();
            $("#inquiryForm").hide();
        @endif
        
        @if($proforma->has_po == 'Y')
        	$("#po_number").show();
        @else
        	$("#po_number").hide();
        @endif
        
        @if($proforma->has_deduction == 'Y')
        	$("#deduction_price").show();
        @else
        	$("#deduction_price").hide();
        @endif

        @foreach($proforma->deposits as $deposit)
            addDeposit({{ $deposit->id }});
        @endforeach
    @endif

    @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
        getProformaData();
        getContact();
        getDeposit();
        @if(Request::get('type') == "booking" || Request::get('type') == "order")
            $("#isNotCustom").hide();
            $("#isCustom").show();
            $("#proforma_detail").hide();
        @else
            $("#isNotCustom").show();
            $("#isCustom").hide();
            $("#proforma_detail").show();
        @endif

        @if(Request::get('type') == "booking")
            $("#bookingForm").show();
            $("#orderForm").hide();
        @elseif(Request::get('type') == "order")
            $("#bookingForm").hide();
            $("#orderForm").show();
        @else
            $("#bookingForm").hide();
            $("#orderForm").hide();
        @endif
    @endif

    function isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
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
        var customer_id = document.getElementById("customer_id").value;
        var contact_id = "";
        var bank_account_id = document.getElementById("bank_account_id").value;
        var detail_status = document.getElementById("detail_status").value;
        var custom_status = document.getElementById("custom_status").value;
        var due_date = document.getElementById("due_date").value;
        var selection_from = document.getElementById("selection_from").value;
        var booking_id = document.getElementById("booking_id").value;
        var order_id = document.getElementById("order_id").value;
        var inquiry_id = document.getElementById("inquiry_id").value;
        var desc = document.getElementById("desc").value;
        var total_unpaid = document.getElementById("total_unpaid").value;
        var custom_price = parseFloat(document.getElementById("custom_price").value);
        var custom_service_charge = parseFloat(document.getElementById("custom_service_charge").value);
        var custom_tax_price = parseFloat(document.getElementById("custom_tax_price").value);

        if(!isEmpty(document.getElementById("contact_id"))){
            contact_id = document.getElementById("contact_id").value;
        }

        if(bank_account_id == ""){ // Cek Bank Account
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select bank account</b> </span>'+
                            '</div>';
        }

        if(customer_id == ""){ // Cek Customer
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select customer</b> </span>'+
                            '</div>';
        }

        // if(contact_id == ""){ // Cek Contact
        //     error_list +=   '<div class="alert alert-warning">'+
        //                         '<span><b> Sorry !!! You have to select contact</b> </span>'+
        //                     '</div>';
        // }
        // Special Request from Amethyst

        if(location_id == ""){ // Cek Location
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select location</b> </span>'+
                            '</div>';
        }

        if(detail_status == ""){ // Cek Customer
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select detail status</b> </span>'+
                            '</div>';
        }

        if(custom_status == ""){ // Cek Customer
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select custom status</b> </span>'+
                            '</div>';
        }else{
            if(custom_status == 'Y'){
                if(selection_from == ''){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select source for custom proforma</b> </span>'+
                                    '</div>';
                }else{
                    switch(selection_from){
                        case 'booking':
                            if(booking_id == ''){
                                error_list +=   '<div class="alert alert-warning">'+
                                                    '<span><b> Sorry !!! You have to select booking for this source custom proforma</b> </span>'+
                                                '</div>';
                            }
                        break;
                        case 'order':
                            if(order_id == ''){
                                error_list +=   '<div class="alert alert-warning">'+
                                                    '<span><b> Sorry !!! You have to select order for this source custom proforma</b> </span>'+
                                                '</div>';
                            }
                        break;
                        case 'inquiry':
                            if(inquiry_id == ''){
                                error_list +=   '<div class="alert alert-warning">'+
                                                    '<span><b> Sorry !!! You have to select inquiry for this source custom proforma</b> </span>'+
                                                '</div>';
                            }
                        break;
                    }
                    var total_custom_price = custom_price + custom_service_charge + custom_tax_price;
                    if(total_custom_price == 0){
                        error_list +=   '<div class="alert alert-warning">'+
                                            '<span><b> Sorry !!! You have to input custom price</b> </span>'+
                                        '</div>';
                    }else{
                        if(total_custom_price > total_unpaid){
                            error_list +=   '<div class="alert alert-warning">'+
                                                "<span><b> Sorry !!! You can't input custom price more than total unpaid</b> </span>"+
                                            '</div>';
                        }
                    }

                    if(desc == ''){
                        error_list +=   '<div class="alert alert-warning">'+
                                            '<span><b> Sorry !!! You have to input description for custom proforma</b> </span>'+
                                        '</div>';
                    }
                }
            }else{
                if(proforma_details.length == 0){
                    error_list +=   '<div class="alert alert-warning">'+
                                        '<span><b> Sorry !!! You have to select detail transaction for this proforma</b> </span>'+
                                    '</div>';
                }
            }
        }

        if(due_date == ""){ // Cek Due Date
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to input due date</b> </span>'+
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

    $(function() {
        $("#location_id").change(function() {
            getProformaData();
        });

        $("#customer_id").change(function() {
            getProformaData();
            getContact();
            getDeposit();
        });

        $("#custom_status").change(function() {
            var custom_status = document.getElementById("custom_status").value;
            if(custom_status == 'Y'){
                $("#isNotCustom").hide();
                $("#isCustom").show();
                $("#proforma_detail").hide();
            }else{
                $("#isNotCustom").show();
                $("#isCustom").hide();
                $("#proforma_detail").show();
            }
        });

        $("#selection_from").change(function() {
            var selection = $("#selection_from option:selected").val();
            if(selection == ''){
                $("#bookingForm").hide();
                $("#orderForm").hide();
                $("#inquiryForm").hide();
            }else if(selection == 'booking'){
                $("#bookingForm").show();
                $("#orderForm").hide();
                $("#inquiryForm").hide();
            }else if(selection == 'order'){
                $("#bookingForm").hide();
                $("#orderForm").show();
                $("#inquiryForm").hide();
            }else if(selection == 'inquiry'){
                $("#bookingForm").hide();
                $("#orderForm").hide();
                $("#inquiryForm").show();
            }else{
                $("#bookingForm").hide();
                $("#orderForm").hide();
                $("#inquiryForm").hide();
            }
        });

        $("#booking_id").change(function(){
            getBooking();
        });

        $("#order_id").change(function(){
            getOrder();
        });

        $("#inquiry_id").change(function(){
            getInquiry();
        });

        $("#select_all_serviced_office").change(function() {
            var check_status = document.getElementById("select_all_serviced_office").checked;
            for(var i = 0; i < serviced_offices.length; i++){
                document.getElementById("serviced_office_id_"+i).checked = check_status;
            }
            checkProformaData();
        });
        $('#select_all_virtual_office').change(function(){
            var check_status = document.getElementById("select_all_virtual_office").checked;
            for(var i = 0; i < virtual_office.length; i++){
                document.getElementById("virtual_office_id_"+i).checked = check_status;
            }
            checkProformaData();
        });
        $('#select_all_meeting_room').change(function(){
            var check_status = document.getElementById("select_all_meeting_room").checked;
            for(var i = 0; i < meeting_room.length; i++){
                document.getElementById("meeting_room_id_"+i).checked = check_status;
            }
            checkProformaData();
        });
        $('#select_all_coworking').change(function(){
            var check_status = document.getElementById("select_all_coworking").checked;
            for(var i = 0; i < coworking.length; i++){
                document.getElementById("coworking_id_"+i).checked = check_status;
            }
            checkProformaData();
        });
        $('#select_all_hotel').change(function(){
            var check_status = document.getElementById("select_all_hotel").checked;
            for(var i = 0; i < hotel.length; i++){
                document.getElementById("hotel_id_"+i).checked = check_status;
            }
            checkProformaData();
        });
        $("#select_all_regular_office").change(function() {
            var check_status = document.getElementById("select_all_regular_office").checked;
            for(var i = 0; i < regular_offices.length; i++){
                document.getElementById("regular_office_id_"+i).checked = check_status;
            }
            checkProformaData();
        });

        $("#select_all_order_detail").change(function() {
            var check_status = document.getElementById("select_all_order_detail").checked;
            for(var i = 0; i < order_details.length; i++){
                document.getElementById("order_detail_id_"+i).checked = check_status;
            }
            checkProformaData();
        });

        $("#select_all_cancellation").change(function() {
            var check_status = document.getElementById("select_all_cancellation").checked;
            for(var i = 0; i < order_details.length; i++){
                document.getElementById("order_detail_id_"+i).checked = check_status;
            }
            checkProformaData();
        });
    });

    function getContact(){
        var customer_id = document.getElementById("customer_id").value;

        if(customer_id != ''){
            var link_contact = "{{ url('contact/get_by_customer') }}";

            var url_contact = link_contact+"/"+customer_id;

            var contact_list = '';
            $.get(url_contact, function (data){
                contact_list += '<option value="">Select Contact</option>';
                for(var i=0; i < data.length; i++){
                    var selected = '';
                    @if(!empty($proforma))
                        if(data[i]['id'] == {{ $proforma->contact_id }}){
                            selected = 'selected';
                        }
                    @endif

                    contact_list += '<option value="'+data[i]['id']+'" '+selected+'>'+data[i]['name']+'</option>';
                }
                document.getElementById("contact_list").innerHTML = contact_list;

                $('#contact_list').selectpicker('refresh');


            });
            $('#customer_contact').show();
        }
    }

    function getProformaData(){
        var location_id = document.getElementById("location_id").value;
        var customer_id = document.getElementById("customer_id").value;
        var id = '';
        var link = "{{ url('getProformaData') }}";

        @if(!empty($proforma))
            id = '{{ $proforma->id }}';
        @endif

        if(location_id != '' && customer_id != ''){
            var url = link+"?location_id="+location_id+"&customer_id="+customer_id+"&id="+id;
            $.get(url, function (data){
                var booking_list = '<option value="">Please select one</option>';
                var order_list = '<option value="">Please select one</option>';
                var inquiry_list = '<option value="">Please select one</option>';
                serviced_offices = data.serviced_offices;
                virtual_office = data.virtual_office;
                meeting_room = data.meeting_rooms;
                coworking = data.coworking;
                hotel = data.hotel;
                regular_offices = data.regular_offices;
                order_details = data.order_details;
                cancellations = data.order_details;

                for(var i=0; i < data.bookings.length; i++){
                    var booking_selected = '';
                    @if(!empty($proforma))
                        if(data.bookings[i]['id'] == '{{ $proforma->booking_id }}'){
                            booking_selected = 'selected';
                        }
                    @endif
                    @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                        @if(Request::get('type') == "booking")
                            if(data.bookings[i]['id'] == "{{ Request::get('detail_id') }}"){
                                booking_selected = 'selected';
                            }
                        @endif
                    @endif
                    @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                        @if(Request::get('type') == "order")
                            if(data.orders[i]['id'] == "{{ Request::get('detail_id') }}"){
                                order_selected = 'selected';
                            }
                        @endif
                    @endif
                    booking_list += '<option value="'+data.bookings[i]['id']+'" '+booking_selected+'>'+data.bookings[i]['code']+'</option>';
                }
                document.getElementById("booking_id").innerHTML = booking_list;

                $('#booking_id').selectpicker('refresh');

                for(var i=0; i < data.orders.length; i++){
                    var order_selected = '';
                    @if(!empty($proforma))
                        if(data.orders[i]['id'] == '{{ $proforma->order_id }}'){
                            order_selected = 'selected';
                        }
                    @endif
                    order_list += '<option value="'+data.orders[i]['id']+'" '+order_selected+'>'+data.orders[i]['code']+'</option>';
                }
                document.getElementById("order_id").innerHTML = order_list;

                $('#order_id').selectpicker('refresh');

                for(var i=0; i < data.inquiries.length; i++){
                    var inquiry_selected = '';
                    @if(!empty($proforma))
                        if(data.inquiries[i]['id'] == '{{ $proforma->inquiry_id }}'){
                            inquiry_selected = 'selected';
                        }
                    @endif
                    inquiry_list += '<option value="'+data.inquiries[i]['id']+'" '+inquiry_selected+'>'+data.inquiries[i]['code']+'</option>';
                }
                document.getElementById("inquiry_id").innerHTML = inquiry_list;

                $('#inquiry_id').selectpicker('refresh');

                loadData();
            });
        }
    }

    function loadData(){
        var detail_serviced_office = '';
        var detail_virtual_office = '';
        var detail_meeting_room= '';
        var detail_coworking = '';
        var detail_hotel= '';
        var detail_regular_office = '';
        var detail_order_detail = '';
        var detail_cancellation = '';
        var detail_name = '';

        for(var i = 0; i < serviced_offices.length; i++){
            var checked = '';
            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_detail_id != null)
                        if(serviced_offices[i].id == '{{ $proforma_detail->booking_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "booking_detail")
                    if(serviced_offices[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(serviced_offices[i].quantity) * (parseFloat(serviced_offices[i].length_of_detail) - parseFloat(serviced_offices[i].detail_use_complimentary));

            detail_name = serviced_offices[i].room_number;

            detail_serviced_office += '<tr>';
                detail_serviced_office += '<td class="text-center">';
                    detail_serviced_office += '<div class="form-check">';
                        detail_serviced_office += '<label class="form-check-label">';
                            detail_serviced_office += '<input class="form-check-input" type="checkbox" value="'+serviced_offices[i].id+'" id="serviced_office_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_serviced_office += '<span class="form-check-sign">';
                                detail_serviced_office += '<span class="check"></span>';
                            detail_serviced_office += '</span>';
                        detail_serviced_office += '</label>';
                    detail_serviced_office += '</div>';
                detail_serviced_office += '</td>';
                detail_serviced_office += '<td class="text-left">';
                    detail_serviced_office += '<p>Date : '+serviced_offices[i].start_date+' to '+serviced_offices[i].end_date+'</p>';
                    detail_serviced_office += '<p>Time : '+serviced_offices[i].start_time+' to '+serviced_offices[i].end_time+'</p>';
                    detail_serviced_office += '<input type="hidden" value="'+serviced_offices[i].start_date+' to '+serviced_offices[i].end_date+'" id="serviced_office_date_'+i+'" >';
                    detail_serviced_office += '<input type="hidden" value="'+serviced_offices[i].start_time+' to '+serviced_offices[i].end_time+'" id="serviced_office_time_'+i+'" >';
                detail_serviced_office += '</td>';
                detail_serviced_office += '<td class="text-center">';
                    detail_serviced_office += '<p>'+detail_name+' : '+serviced_offices[i].code+'</p>';
                    detail_serviced_office += '<input type="hidden" value="'+detail_name+' : '+serviced_offices[i].code+'" id="serviced_office_desc_'+i+'" >';
                detail_serviced_office += '</td>';
                detail_serviced_office += '<td class="text-center">';
                    detail_serviced_office += numberWithCommas(serviced_offices[i].detail_price);
                    detail_serviced_office += '<input type="hidden" value="'+serviced_offices[i].detail_price+'" id="serviced_office_detail_price_'+i+'">';
                detail_serviced_office += '</td>';
                detail_serviced_office += '<td class="text-center">';
                    detail_serviced_office += numberWithCommas(serviced_offices[i].detail_service_charge);
                    detail_serviced_office += '<input type="hidden" value="'+serviced_offices[i].detail_service_charge+'" id="serviced_office_detail_service_charge_'+i+'">';
                detail_serviced_office += '</td>';
                detail_serviced_office += '<td class="text-center">';
                    detail_serviced_office += numberWithCommas(serviced_offices[i].detail_tax_price);
                    detail_serviced_office += '<input type="hidden" value="'+serviced_offices[i].detail_tax_price+'" id="serviced_office_detail_tax_price_'+i+'">';
                detail_serviced_office += '</td>';
                detail_serviced_office += '<td class="text-center">';
                    detail_serviced_office += numberWithCommas(quantity);
                    detail_serviced_office += '<input type="hidden" value="'+quantity+'" id="serviced_office_quantity_'+i+'">';
                detail_serviced_office += '</td>';
            detail_serviced_office += '</tr>';
        }

        for(var i = 0; i < virtual_office.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_detail_id != null)
                        if(virtual_office[i].id == '{{ $proforma_detail->booking_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "booking_detail")
                    if(virtual_office[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(virtual_office[i].quantity) * (parseFloat(virtual_office[i].length_of_detail) - parseFloat(virtual_office[i].detail_use_complimentary));

            detail_name = virtual_office[i].product_name;

            detail_virtual_office += '<tr>';
                detail_virtual_office += '<td class="text-center">';
                    detail_virtual_office += '<div class="form-check">';
                        detail_virtual_office += '<label class="form-check-label">';
                            detail_virtual_office += '<input class="form-check-input" type="checkbox" value="'+virtual_office[i].id+'" id="virtual_office_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_virtual_office += '<span class="form-check-sign">';
                                detail_virtual_office += '<span class="check"></span>';
                            detail_virtual_office += '</span>';
                        detail_virtual_office += '</label>';
                    detail_virtual_office += '</div>';
                detail_virtual_office += '</td>';
                detail_virtual_office += '<td class="text-left">';
                    detail_virtual_office += '<p>Date : '+virtual_office[i].start_date+' to '+virtual_office[i].end_date+'</p>';
                    detail_virtual_office += '<p>Time : '+virtual_office[i].start_time+' to '+virtual_office[i].end_time+'</p>';
                    detail_virtual_office += '<input type="hidden" value="'+virtual_office[i].start_date+' to '+virtual_office[i].end_date+'" id="virtual_office_date_'+i+'" >';
                    detail_virtual_office += '<input type="hidden" value="'+virtual_office[i].start_time+' to '+virtual_office[i].end_time+'" id="virtual_office_time_'+i+'" >';
                detail_virtual_office += '</td>';
                detail_virtual_office += '<td class="text-center">';
                    detail_virtual_office += '<p>'+detail_name+' : '+virtual_office[i].code+'</p>';
                    detail_virtual_office += '<input type="hidden" value="'+detail_name+' : '+virtual_office[i].code+'" id="virtual_office_desc_'+i+'" >';
                detail_virtual_office += '</td>';
                detail_virtual_office += '<td class="text-center">';
                    detail_virtual_office += numberWithCommas(virtual_office[i].detail_price);
                    detail_virtual_office += '<input type="hidden" value="'+virtual_office[i].detail_price+'" id="virtual_office_detail_price_'+i+'">';
                detail_virtual_office += '</td>';
                detail_virtual_office += '<td class="text-center">';
                    detail_virtual_office += numberWithCommas(virtual_office[i].detail_service_charge);
                    detail_virtual_office += '<input type="hidden" value="'+virtual_office[i].detail_service_charge+'" id="virtual_office_detail_service_charge_'+i+'">';
                detail_virtual_office += '</td>';
                detail_virtual_office += '<td class="text-center">';
                    detail_virtual_office += numberWithCommas(virtual_office[i].detail_tax_price);
                    detail_virtual_office += '<input type="hidden" value="'+virtual_office[i].detail_tax_price+'" id="virtual_office_detail_tax_price_'+i+'">';
                detail_virtual_office += '</td>';
                detail_virtual_office += '<td class="text-center">';
                    detail_virtual_office += numberWithCommas(quantity);
                    detail_virtual_office += '<input type="hidden" value="'+quantity+'" id="virtual_office_quantity_'+i+'">';
                detail_virtual_office += '</td>';
            detail_virtual_office += '</tr>';
        }

        for(var i = 0; i < meeting_room.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_detail_id != null)
                        if(meeting_room[i].id == '{{ $proforma_detail->booking_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "booking_detail")
                    if(meeting_room[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(meeting_room[i].quantity) * (parseFloat(meeting_room[i].length_of_detail) - parseFloat(meeting_room[i].detail_use_complimentary));

            detail_name = meeting_room[i].room_number;

            detail_meeting_room += '<tr>';
                detail_meeting_room += '<td class="text-center">';
                    detail_meeting_room += '<div class="form-check">';
                        detail_meeting_room += '<label class="form-check-label">';
                            detail_meeting_room += '<input class="form-check-input" type="checkbox" value="'+meeting_room[i].id+'" id="meeting_room_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_meeting_room += '<span class="form-check-sign">';
                                detail_meeting_room += '<span class="check"></span>';
                            detail_meeting_room += '</span>';
                        detail_meeting_room += '</label>';
                    detail_meeting_room += '</div>';
                detail_meeting_room += '</td>';
                detail_meeting_room += '<td class="text-left">';
                    detail_meeting_room += '<p>Date : '+meeting_room[i].start_date+' to '+meeting_room[i].end_date+'</p>';
                    detail_meeting_room += '<p>Time : '+meeting_room[i].start_time+' to '+meeting_room[i].end_time+'</p>';
                    detail_meeting_room += '<input type="hidden" value="'+meeting_room[i].start_date+' to '+meeting_room[i].end_date+'" id="meeting_room_date_'+i+'" >';
                    detail_meeting_room += '<input type="hidden" value="'+meeting_room[i].start_time+' to '+meeting_room[i].end_time+'" id="meeting_room_time_'+i+'" >';
                detail_meeting_room += '</td>';
                detail_meeting_room += '<td class="text-center">';
                    detail_meeting_room += '<p>'+detail_name+' : '+meeting_room[i].code+'</p>';
                    detail_meeting_room += '<input type="hidden" value="'+detail_name+' : '+meeting_room[i].code+'" id="meeting_room_desc_'+i+'" >';
                detail_meeting_room += '</td>';
                detail_meeting_room += '<td class="text-center">';
                    detail_meeting_room += numberWithCommas(meeting_room[i].detail_price);
                    detail_meeting_room += '<input type="hidden" value="'+meeting_room[i].detail_price+'" id="meeting_room_detail_price_'+i+'">';
                detail_meeting_room += '</td>';
                detail_meeting_room += '<td class="text-center">';
                    detail_meeting_room += numberWithCommas(meeting_room[i].detail_service_charge);
                    detail_meeting_room += '<input type="hidden" value="'+meeting_room[i].detail_service_charge+'" id="meeting_room_detail_service_charge_'+i+'">';
                detail_meeting_room += '</td>';
                detail_meeting_room += '<td class="text-center">';
                    detail_meeting_room += numberWithCommas(meeting_room[i].detail_tax_price);
                    detail_meeting_room += '<input type="hidden" value="'+meeting_room[i].detail_tax_price+'" id="meeting_room_detail_tax_price_'+i+'">';
                detail_meeting_room += '</td>';
                detail_meeting_room += '<td class="text-center">';
                    detail_meeting_room += numberWithCommas(quantity);
                    detail_meeting_room += '<input type="hidden" value="'+quantity+'" id="meeting_room_quantity_'+i+'">';
                detail_meeting_room += '</td>';
            detail_meeting_room += '</tr>';
        }

        for(var i = 0; i < coworking.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_detail_id != null)
                        if(coworking[i].id == '{{ $proforma_detail->booking_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "booking_detail")
                    if(coworking[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(coworking[i].quantity) * (parseFloat(coworking[i].length_of_detail) - parseFloat(coworking[i].detail_use_complimentary));

            detail_name = coworking[i].room_number;

            detail_coworking += '<tr>';
                detail_coworking += '<td class="text-center">';
                    detail_coworking += '<div class="form-check">';
                        detail_coworking += '<label class="form-check-label">';
                            detail_coworking += '<input class="form-check-input" type="checkbox" value="'+coworking[i].id+'" id="coworking_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_coworking += '<span class="form-check-sign">';
                                detail_coworking += '<span class="check"></span>';
                            detail_coworking += '</span>';
                        detail_coworking += '</label>';
                    detail_coworking += '</div>';
                detail_coworking += '</td>';
                detail_coworking += '<td class="text-left">';
                    detail_coworking += '<p>Date : '+coworking[i].start_date+' to '+coworking[i].end_date+'</p>';
                    detail_coworking += '<p>Time : '+coworking[i].start_time+' to '+coworking[i].end_time+'</p>';
                    detail_coworking += '<input type="hidden" value="'+coworking[i].start_date+' to '+coworking[i].end_date+'" id="coworking_date_'+i+'" >';
                    detail_coworking += '<input type="hidden" value="'+coworking[i].start_time+' to '+coworking[i].end_time+'" id="coworking_time_'+i+'" >';
                detail_coworking += '</td>';
                detail_coworking += '<td class="text-center">';
                    detail_coworking += '<p>'+detail_name+' : '+coworking[i].code+'</p>';
                    detail_coworking += '<input type="hidden" value="'+detail_name+' : '+coworking[i].code+'" id="coworking_desc_'+i+'" >';
                detail_coworking += '</td>';
                detail_coworking += '<td class="text-center">';
                    detail_coworking += numberWithCommas(coworking[i].detail_price);
                    detail_coworking += '<input type="hidden" value="'+coworking[i].detail_price+'" id="coworking_detail_price_'+i+'">';
                detail_coworking += '</td>';
                detail_coworking += '<td class="text-center">';
                    detail_coworking += numberWithCommas(coworking[i].detail_service_charge);
                    detail_coworking += '<input type="hidden" value="'+coworking[i].detail_service_charge+'" id="coworking_detail_service_charge_'+i+'">';
                detail_coworking += '</td>';
                detail_coworking += '<td class="text-center">';
                    detail_coworking += numberWithCommas(coworking[i].detail_tax_price);
                    detail_coworking += '<input type="hidden" value="'+coworking[i].detail_tax_price+'" id="coworking_detail_tax_price_'+i+'">';
                detail_coworking += '</td>';
                detail_coworking += '<td class="text-center">';
                    detail_coworking += numberWithCommas(quantity);
                    detail_coworking += '<input type="hidden" value="'+quantity+'" id="coworking_quantity_'+i+'">';
                detail_coworking += '</td>';
            detail_coworking += '</tr>';
        }

        for(var i = 0; i < hotel.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_detail_id != null)
                        if(hotel[i].id == '{{ $proforma_detail->booking_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "booking_detail")
                    if(hotel[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(hotel[i].quantity) * (parseFloat(hotel[i].length_of_detail) - parseFloat(hotel[i].detail_use_complimentary));

            detail_name = hotel[i].room_number;

            detail_hotel += '<tr>';
                detail_hotel += '<td class="text-center">';
                    detail_hotel += '<div class="form-check">';
                        detail_hotel += '<label class="form-check-label">';
                            detail_hotel += '<input class="form-check-input" type="checkbox" value="'+hotel[i].id+'" id="hotel_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_hotel += '<span class="form-check-sign">';
                                detail_hotel += '<span class="check"></span>';
                            detail_hotel += '</span>';
                        detail_hotel += '</label>';
                    detail_hotel += '</div>';
                detail_hotel += '</td>';
                detail_hotel += '<td class="text-left">';
                    detail_hotel += '<p>Date : '+hotel[i].start_date+' to '+hotel[i].end_date+'</p>';
                    detail_hotel += '<p>Time : '+hotel[i].start_time+' to '+hotel[i].end_time+'</p>';
                    detail_hotel += '<input type="hidden" value="'+hotel[i].start_date+' to '+hotel[i].end_date+'" id="hotel_date_'+i+'" >';
                    detail_hotel += '<input type="hidden" value="'+hotel[i].start_time+' to '+hotel[i].end_time+'" id="hotel_time_'+i+'" >';
                detail_hotel += '</td>';
                detail_hotel += '<td class="text-center">';
                    detail_hotel += '<p>'+detail_name+' : '+hotel[i].code+'</p>';
                    detail_hotel += '<input type="hidden" value="'+detail_name+' : '+hotel[i].code+'" id="hotel_desc_'+i+'" >';
                detail_hotel += '</td>';
                detail_hotel += '<td class="text-center">';
                    detail_hotel += numberWithCommas(hotel[i].detail_price);
                    detail_hotel += '<input type="hidden" value="'+hotel[i].detail_price+'" id="hotel_detail_price_'+i+'">';
                detail_hotel += '</td>';
                detail_hotel += '<td class="text-center">';
                    detail_hotel += numberWithCommas(hotel[i].detail_service_charge);
                    detail_hotel += '<input type="hidden" value="'+hotel[i].detail_service_charge+'" id="hotel_detail_service_charge_'+i+'">';
                detail_hotel += '</td>';
                detail_hotel += '<td class="text-center">';
                    detail_hotel += numberWithCommas(hotel[i].detail_tax_price);
                    detail_hotel += '<input type="hidden" value="'+hotel[i].detail_tax_price+'" id="hotel_detail_tax_price_'+i+'">';
                detail_hotel += '</td>';
                detail_hotel += '<td class="text-center">';
                    detail_hotel += numberWithCommas(quantity);
                    detail_hotel += '<input type="hidden" value="'+quantity+'" id="hotel_quantity_'+i+'">';
                detail_hotel += '</td>';
            detail_hotel += '</tr>';
        }

        for(var i = 0; i < regular_offices.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_detail_id != null)
                        if(regular_offices[i].id == '{{ $proforma_detail->booking_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "booking_detail")
                    if(regular_offices[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(regular_offices[i].quantity) * (parseFloat(regular_offices[i].length_of_detail) - parseFloat(regular_offices[i].detail_use_complimentary));

            detail_name = regular_offices[i].room_number;

            detail_regular_office += '<tr>';
                detail_regular_office += '<td class="text-center">';
                    detail_regular_office += '<div class="form-check">';
                        detail_regular_office += '<label class="form-check-label">';
                            detail_regular_office += '<input class="form-check-input" type="checkbox" value="'+regular_offices[i].id+'" id="regular_office_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_regular_office += '<span class="form-check-sign">';
                                detail_regular_office += '<span class="check"></span>';
                            detail_regular_office += '</span>';
                        detail_regular_office += '</label>';
                    detail_regular_office += '</div>';
                detail_regular_office += '</td>';
                detail_regular_office += '<td class="text-left">';
                    detail_regular_office += '<p>Date : '+regular_offices[i].start_date+' to '+regular_offices[i].end_date+'</p>';
                    detail_regular_office += '<p>Time : '+regular_offices[i].start_time+' to '+regular_offices[i].end_time+'</p>';
                    detail_regular_office += '<input type="hidden" value="'+regular_offices[i].start_date+' to '+regular_offices[i].end_date+'" id="regular_office_date_'+i+'" >';
                    detail_regular_office += '<input type="hidden" value="'+regular_offices[i].start_time+' to '+regular_offices[i].end_time+'" id="regular_office_time_'+i+'" >';
                detail_regular_office += '</td>';
                detail_regular_office += '<td class="text-center">';
                    detail_regular_office += '<p>'+regular_offices[i].room_number+' : '+regular_offices[i].code+'</p>';
                    detail_regular_office += '<input type="hidden" value="'+regular_offices[i].room_number+' : '+regular_offices[i].code+'" id="regular_office_desc_'+i+'" >';
                detail_regular_office += '</td>';
                detail_regular_office += '<td class="text-center">';
                    detail_regular_office += numberWithCommas(regular_offices[i].detail_price);
                    detail_regular_office += '<input type="hidden" value="'+regular_offices[i].detail_price+'" id="regular_office_detail_price_'+i+'">';
                detail_regular_office += '</td>';
                detail_regular_office += '<td class="text-center">';
                    detail_regular_office += numberWithCommas(regular_offices[i].detail_service_charge);
                    detail_regular_office += '<input type="hidden" value="'+regular_offices[i].detail_service_charge+'" id="regular_office_detail_service_charge_'+i+'">';
                detail_regular_office += '</td>';
                detail_regular_office += '<td class="text-center">';
                    detail_regular_office += numberWithCommas(regular_offices[i].detail_tax_price);
                    detail_regular_office += '<input type="hidden" value="'+regular_offices[i].detail_tax_price+'" id="regular_office_detail_tax_price_'+i+'">';
                detail_regular_office += '</td>';
                detail_regular_office += '<td class="text-center">';
                    detail_regular_office += numberWithCommas(quantity);
                    detail_regular_office += '<input type="hidden" value="'+quantity+'" id="regular_office_quantity_'+i+'">';
                detail_regular_office += '</td>';
            detail_regular_office += '</tr>';
        }

        for(var i = 0; i < order_details.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->order_detail_id != null)
                        if(order_details[i].id == '{{ $proforma_detail->order_detail_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
                @if(Request::get('type') == "order_detail")
                    if(order_details[i].id == "{{ Request::get('detail_id') }}"){
                        checked = 'checked';
                    }
                @endif
            @endif

            var quantity = parseFloat(order_details[i].quantity) * parseFloat(order_details[i].length_of_term);

            detail_order_detail += '<tr>';
                detail_order_detail += '<td class="text-center">';
                    detail_order_detail += '<div class="form-check">';
                        detail_order_detail += '<label class="form-check-label">';
                            detail_order_detail += '<input class="form-check-input" type="checkbox" value="'+order_details[i].id+'" id="order_detail_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_order_detail += '<span class="form-check-sign">';
                                detail_order_detail += '<span class="check"></span>';
                            detail_order_detail += '</span>';
                        detail_order_detail += '</label>';
                    detail_order_detail += '</div>';
                detail_order_detail += '</td>';
                detail_order_detail += '<td class="text-left">';
                    detail_order_detail += '<p>Date : '+order_details[i].order_date+'</p>';
                    detail_order_detail += '<input type="hidden" value="'+order_details[i].order_date+'" id="order_detail_date_'+i+'" >';
                detail_order_detail += '</td>';
                detail_order_detail += '<td class="text-center">';
                    detail_order_detail += '<p>'+order_details[i].product_name+' : '+order_details[i].code+'</p>';
                    detail_order_detail += '<input type="hidden" value="'+order_details[i].product_name+' : '+order_details[i].code+'" id="order_detail_desc_'+i+'" >';
                    detail_order_detail += '<p>Remarks: ' +order_details[i].remarks+'</p>';
                detail_order_detail += '</td>';
                detail_order_detail += '<td class="text-center">';
                    detail_order_detail += numberWithCommas(order_details[i].detail_price);
                    detail_order_detail += '<input type="hidden" value="'+order_details[i].detail_price+'" id="order_detail_detail_price_'+i+'">';
                detail_order_detail += '</td>';
                detail_order_detail += '<td class="text-center">';
                    detail_order_detail += numberWithCommas(order_details[i].detail_service_charge);
                    detail_order_detail += '<input type="hidden" value="'+order_details[i].detail_service_charge+'" id="order_detail_detail_service_charge_'+i+'">';
                detail_order_detail += '</td>';
                detail_order_detail += '<td class="text-center">';
                    detail_order_detail += numberWithCommas(order_details[i].detail_tax_price);
                    detail_order_detail += '<input type="hidden" value="'+order_details[i].detail_tax_price+'" id="order_detail_detail_tax_price_'+i+'">';
                detail_order_detail += '</td>';
                detail_order_detail += '<td class="text-center">';
                    detail_order_detail += numberWithCommas(quantity);
                    detail_order_detail += '<input type="hidden" value="'+quantity+'" id="order_detail_quantity_'+i+'">';
                detail_order_detail += '</td>';
            detail_order_detail += '</tr>';
        }

        for(var i = 0; i < cancellations.length; i++){
            var checked = '';

            @if(!empty($proforma))
                @foreach($proforma->proforma_detail as $proforma_detail)
                    @if($proforma_detail->booking_cancellation_id != null)
                        if(cancellations[i].id == '{{ $proforma_detail->booking_cancellation_id }}'){
                            checked = 'checked';
                        }
                    @endif
                @endforeach
            @endif

            detail_cancellation += '<tr>';
                detail_cancellation += '<td class="text-center">';
                    detail_cancellation += '<div class="form-check">';
                        detail_cancellation += '<label class="form-check-label">';
                            detail_cancellation += '<input class="form-check-input" type="checkbox" value="'+cancellations[i].id+'" id="cancellation_id_'+i+'" '+checked+' onchange="checkProformaData()">';
                            detail_cancellation += '<span class="form-check-sign">';
                                detail_cancellation += '<span class="check"></span>';
                            detail_cancellation += '</span>';
                        detail_cancellation += '</label>';
                    detail_cancellation += '</div>';
                detail_cancellation += '</td>';
                detail_cancellation += '<td class="text-left">';
                    detail_cancellation += '<p>Date : '+cancellations[i].start_date+'</p>';
                    detail_cancellation += '<input type="hidden" value="'+cancellations[i].start_date+'" id="cancellation_date_'+i+'" >';
                detail_cancellation += '</td>';
                detail_cancellation += '<td class="text-center">';
                    detail_cancellation += '<p>'+cancellations[i].code+'</p>';
                    detail_cancellation += '<input type="hidden" value="'+cancellations[i].code+'" id="cancellation_desc_'+i+'" >';
                detail_cancellation += '</td>';
                detail_cancellation += '<td class="text-center">';
                    detail_cancellation += numberWithCommas(cancellations[i].detail_price);
                    detail_cancellation += '<input type="hidden" value="'+cancellations[i].detail_price+'" id="cancellation_detail_price_'+i+'">';
                detail_cancellation += '</td>';
                detail_cancellation += '<td class="text-center">';
                    detail_cancellation += numberWithCommas(cancellations[i].detail_tax_price);
                    detail_cancellation += '<input type="hidden" value="'+cancellations[i].detail_tax_price+'" id="cancellation_detail_tax_price_'+i+'">';
                detail_cancellation += '</td>';
            detail_cancellation += '</tr>';
        }

        document.getElementById("detail_serviced_office").innerHTML = detail_serviced_office;
        document.getElementById("detail_virtual_office").innerHTML = detail_virtual_office;
        document.getElementById("detail_meeting_room").innerHTML = detail_meeting_room;
        document.getElementById("detail_coworking").innerHTML = detail_coworking;
        document.getElementById("detail_hotel").innerHTML = detail_hotel;
        document.getElementById("detail_regular_office").innerHTML = detail_regular_office;
        document.getElementById("detail_order_detail").innerHTML = detail_order_detail;
        document.getElementById("detail_cancellation").innerHTML = detail_cancellation;

        @if(!empty($proforma))
            checkProformaData();
        @endif

        @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('type')) && !empty(Request::get('detail_id')))
            checkProformaData();
        @endif
    }

    function getBooking(){
        var booking_id = $("#booking_id option:selected").val();
        var link_list = "{{ url('getProformaInvoiceByBooking') }}";
        var link_detail = "{{ url('booking/get_by_id') }}";
        var total_price = 0;
        var total_tax_price = 0;
        var total_paid = 0;
        var total_unpaid = 0;

        @if(!empty($proforma))
            if(booking_id == ''){
                booking_id = '{{ $proforma->booking_id }}';
            }
        @endif

        if(booking_id != ''){
            var proforma_detail_by_reference = '';
            var invoice_detail_by_reference = '';

            var url_list = link_list+"/"+booking_id;
            $.get(url_list, function (data){
                for(var i=0; i < data.proformas.length; i++){
                    var sub_total = parseFloat(data.proformas[i].total_price) + parseFloat(data.proformas[i].total_tax_price);

                    proforma_detail_by_reference += '<tr>';
                        proforma_detail_by_reference += '<td>'+data.proformas[i].code+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(data.proformas[i].total_price)+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(data.proformas[i].total_tax_price)+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(sub_total)+'</td>';
                    proforma_detail_by_reference += '</tr>';
                }
                document.getElementById("proforma_detail_by_reference").innerHTML = proforma_detail_by_reference;

                for(var i=0; i < data.invoices.length; i++){
                    var sub_total = parseFloat(data.invoices[i].total_price) + parseFloat(data.invoices[i].total_tax_price);

                    invoice_detail_by_reference += '<tr>';
                        invoice_detail_by_reference += '<td>'+data.invoices[i].code+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(data.invoices[i].total_price)+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(data.invoices[i].total_tax_price)+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(sub_total)+'</td>';
                    invoice_detail_by_reference += '</tr>';
                }
                document.getElementById("invoice_detail_by_reference").innerHTML = invoice_detail_by_reference;
            });

            var url_detail = link_detail+"/"+booking_id;
            $.get(url_detail, function (data){
                total_price = parseFloat(data.total_price);
                total_tax_price = parseFloat(data.total_tax_price);
                total_paid = parseFloat(data.total_paid);
                total_unpaid = (total_price + total_tax_price) - total_paid;
                $("#format_total_unpaid").val(numberWithCommas(total_unpaid));
                $("#total_unpaid").val(total_unpaid);
            });
        }
    }

    function getOrder(){
        var order_id = $("#order_id option:selected").val();
        var link_list = "{{ url('getProformaInvoiceByOrder') }}";
        var link_detail = "{{ url('point_of_sales/get_by_id') }}";
        var total_price = 0;
        var total_tax_price = 0;
        var total_paid = 0;
        var total_unpaid = 0;

        @if(!empty($proforma))
            if(order_id == ''){
                order_id = '{{ $proforma->order_id }}';
            }
        @endif

        if(order_id != ''){
            var proforma_detail_by_reference = '';
            var invoice_detail_by_reference = '';

            var url_list = link_list+"/"+order_id;
            $.get(url_list, function (data){
                for(var i=0; i < data.proformas.length; i++){
                    var sub_total = parseFloat(data.proformas[i].total_price) + parseFloat(data.proformas[i].total_tax_price);

                    proforma_detail_by_reference += '<tr>';
                        proforma_detail_by_reference += '<td>'+data.proformas[i].code+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(data.proformas[i].total_price)+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(data.proformas[i].total_tax_price)+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(sub_total)+'</td>';
                    proforma_detail_by_reference += '</tr>';
                }
                document.getElementById("proforma_detail_by_reference").innerHTML = proforma_detail_by_reference;

                for(var i=0; i < data.invoices.length; i++){
                    var sub_total = parseFloat(data.invoices[i].total_price) + parseFloat(data.invoices[i].total_tax_price);

                    invoice_detail_by_reference += '<tr>';
                        invoice_detail_by_reference += '<td>'+data.invoices[i].code+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(data.invoices[i].total_price)+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(data.invoices[i].total_tax_price)+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(sub_total)+'</td>';
                    invoice_detail_by_reference += '</tr>';
                }
                document.getElementById("invoice_detail_by_reference").innerHTML = invoice_detail_by_reference;
            });

            var url_link = link_detail+"/"+order_id;
            $.get(url_link, function (data){
                total_price = parseFloat(data.total_price);
                total_tax_price = parseFloat(data.total_tax_price);
                total_paid = parseFloat(data.total_paid);
                total_unpaid = (total_price + total_tax_price) - total_paid;
                $("#format_total_unpaid").val(numberWithCommas(total_unpaid));
                $("#total_unpaid").val(total_unpaid);
            });
        }
    }

    function getInquiry(){
        var inquiry_id = $("#inquiry_id option:selected").val();
        var link_list = "{{ url('getProformaInvoiceByOrder') }}";
        var link_detail = "{{ url('inquiry/get_by_id') }}";
        var total_price = 0;
        var total_tax_price = 0;
        var total_paid = 0;
        var total_unpaid = 0;

        @if(!empty($proforma))
            if(inquiry_id == ''){
                inquiry_id = '{{ $proforma->inquiry_id }}';
            }
        @endif

        if(inquiry_id != ''){
            var proforma_detail_by_reference = '';
            var invoice_detail_by_reference = '';

            var url_list = link_list+"/"+inquiry_id;
            $.get(url_list, function (data){
                for(var i=0; i < data.proformas.length; i++){
                    var sub_total = parseFloat(data.proformas[i].total_price) + parseFloat(data.proformas[i].total_tax_price);

                    proforma_detail_by_reference += '<tr>';
                        proforma_detail_by_reference += '<td>'+data.proformas[i].code+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(data.proformas[i].total_price)+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(data.proformas[i].total_tax_price)+'</td>';
                        proforma_detail_by_reference += '<td>'+numberWithCommas(sub_total)+'</td>';
                    proforma_detail_by_reference += '</tr>';
                }
                document.getElementById("proforma_detail_by_reference").innerHTML = proforma_detail_by_reference;

                for(var i=0; i < data.invoices.length; i++){
                    var sub_total = parseFloat(data.invoices[i].total_price) + parseFloat(data.invoices[i].total_tax_price);

                    invoice_detail_by_reference += '<tr>';
                        invoice_detail_by_reference += '<td>'+data.invoices[i].code+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(data.invoices[i].total_price)+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(data.invoices[i].total_tax_price)+'</td>';
                        invoice_detail_by_reference += '<td>'+numberWithCommas(sub_total)+'</td>';
                    invoice_detail_by_reference += '</tr>';
                }
                document.getElementById("invoice_detail_by_reference").innerHTML = invoice_detail_by_reference;
            });

            var url_link = link_detail+"/"+inquiry_id;
            $.get(url_link, function (data){
                total_price = parseFloat(data.total_price);
                total_tax_price = parseFloat(data.total_tax_price);
                total_unpaid = total_price + total_tax_price;
                $("#format_total_unpaid").val(numberWithCommas(total_unpaid));
                $("#total_unpaid").val(total_unpaid);
            });
        }
    }

    function checkProformaData(){
        proforma_details = new Array;
        for(var i = 0; i < serviced_offices.length; i++){
            var check_status = document.getElementById("serviced_office_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("serviced_office_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("serviced_office_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("serviced_office_detail_tax_price_"+i).value;
                var quantity = document.getElementById("serviced_office_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'serviced_office';
                new_array['type_name'] = 'Serviced Office';
                new_array['booking_detail_id'] = document.getElementById("serviced_office_id_"+i).value;
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("serviced_office_date_"+i).value;
                new_array['time'] = document.getElementById("serviced_office_time_"+i).value;
                new_array['desc'] = document.getElementById("serviced_office_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < virtual_office.length; i++){
            var check_status = document.getElementById("virtual_office_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("virtual_office_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("virtual_office_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("virtual_office_detail_tax_price_"+i).value;
                var quantity = document.getElementById("virtual_office_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'virtual_office';
                new_array['type_name'] = 'Virtual Office';
                new_array['booking_detail_id'] = document.getElementById("virtual_office_id_"+i).value;
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("virtual_office_date_"+i).value;
                new_array['time'] = document.getElementById("virtual_office_time_"+i).value;
                new_array['desc'] = document.getElementById("virtual_office_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < meeting_room.length; i++){
            var check_status = document.getElementById("meeting_room_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("meeting_room_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("meeting_room_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("meeting_room_detail_tax_price_"+i).value;
                var quantity = document.getElementById("meeting_room_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'meeting_room';
                new_array['type_name'] = 'Meeting Room';
                new_array['booking_detail_id'] = document.getElementById("meeting_room_id_"+i).value;
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("meeting_room_date_"+i).value;
                new_array['time'] = document.getElementById("meeting_room_time_"+i).value;
                new_array['desc'] = document.getElementById("meeting_room_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < coworking.length; i++){
            var check_status = document.getElementById("coworking_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("coworking_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("coworking_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("coworking_detail_tax_price_"+i).value;
                var quantity = document.getElementById("coworking_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'coworking';
                new_array['type_name'] = 'Workstation';
                new_array['booking_detail_id'] = document.getElementById("coworking_id_"+i).value;
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("coworking_date_"+i).value;
                new_array['time'] = document.getElementById("coworking_time_"+i).value;
                new_array['desc'] = document.getElementById("coworking_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < hotel.length; i++){
            var check_status = document.getElementById("hotel_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("hotel_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("hotel_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("hotel_detail_tax_price_"+i).value;
                var quantity = document.getElementById("hotel_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'hotel';
                new_array['type_name'] = 'Hotel';
                new_array['booking_detail_id'] = document.getElementById("hotel_id_"+i).value;
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("hotel_date_"+i).value;
                new_array['time'] = document.getElementById("hotel_time_"+i).value;
                new_array['desc'] = document.getElementById("hotel_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < regular_offices.length; i++){
            var check_status = document.getElementById("regular_office_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("regular_office_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("regular_office_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("regular_office_detail_tax_price_"+i).value;
                var quantity = document.getElementById("regular_office_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'regular_office';
                new_array['type_name'] = 'Regular Office';
                new_array['booking_detail_id'] = document.getElementById("regular_office_id_"+i).value;
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("regular_office_date_"+i).value;
                new_array['time'] = document.getElementById("regular_office_time_"+i).value;
                new_array['desc'] = document.getElementById("regular_office_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < order_details.length; i++){
            var check_status = document.getElementById("order_detail_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("order_detail_detail_price_"+i).value;
                var detail_service_charge = document.getElementById("order_detail_detail_service_charge_"+i).value;
                var detail_tax_price = document.getElementById("order_detail_detail_tax_price_"+i).value;
                var quantity = document.getElementById("order_detail_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'order_detail';
                new_array['type_name'] = 'Order Detail';
                new_array['booking_detail_id'] = '';
                new_array['order_detail_id'] = document.getElementById("order_detail_id_"+i).value;
                new_array['booking_cancellation_id'] = '';
                new_array['date'] = document.getElementById("order_detail_date_"+i).value;
                new_array['time'] = '';
                new_array['desc'] = document.getElementById("order_detail_desc_"+i).value;
                new_array['remarks'] = order_details[i].remarks;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        for(var i = 0; i < cancellations.length; i++){
            var check_status = document.getElementById("cancellation_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var detail_price = document.getElementById("cancellation_detail_price_"+i).value;
                var detail_service_charge = 0;
                var detail_tax_price = document.getElementById("cancellation_detail_tax_price_"+i).value;
                var quantity = document.getElementById("cancellation_quantity_"+i).value;

                new_array['array_index'] = i;
                new_array['name'] = '';
                new_array['type'] = 'cancellation';
                new_array['type_name'] = 'Cancellation';
                new_array['booking_detail_id'] = '';
                new_array['order_detail_id'] = '';
                new_array['booking_cancellation_id'] = document.getElementById("cancellation_id_"+i).value;
                new_array['date'] = document.getElementById("cancellation_date_"+i).value;
                new_array['time'] = '';
                new_array['desc'] = document.getElementById("cancellation_desc_"+i).value;
                new_array['detail_price'] = parseFloat(detail_price) * parseFloat(quantity);
                new_array['detail_service_charge'] = parseFloat(detail_service_charge) * parseFloat(quantity);
                new_array['detail_tax_price'] = parseFloat(detail_tax_price) * parseFloat(quantity);

                proforma_details.push(new_array);
            }
        }
        setDetailTransaction();
    }

    function getDeposit(){
        var customer_id = document.getElementById("customer_id").value;
        var link = "{{ url('get_deposit_by_customer') }}";
        var proforma_id = '';
        var deposit_list = '<option value="">SELECT YOUR OPTION</option>';

        @if(!empty($proforma))
            proforma_id = '{{ $proforma->id }}';
        @endif

        if(customer_id != ''){
            var url = link+"/"+customer_id+"?proforma_id="+proforma_id;
            $.get(url, function (data){
                for(var i=0; i < data.length; i++){
                    deposit_list += '<option value="'+data[i]['id']+'">'+data[i]['code']+' : Rp '+numberWithCommas(data[i]['total_deposit'])+'</option>';
                }

                document.getElementById("deposit_list").innerHTML = deposit_list;

                $('#deposit_list').selectpicker('refresh');
            });
        }
    }

    function addDeposit(deposit_id = null){
        var manual_add = false;
        var new_item = new Array;
        var link = "{{ url('deposit/get_by_id') }}";
        var availability = true;

        if(deposit_id == null){
            deposit_id = document.getElementById("deposit_list").value;
            manual_add = true;
        }

        if(deposit_id != ""){
            var url = link+"/"+deposit_id;

            for(var i=0; i < deposits.length; i++){
                if(deposits[i].id == deposit_id){
                    availability = false;
                    break;
                }
            }

            if(availability){
                $.get(url, function (data){

                    deposits.push(data);

                    if(manual_add){
                        alert("New Deposit Added");
                    }

                    setDetailTransaction();
                });
            }else{
                alert("You already select this item");
            }

        }else{
            alert("You have to select one of the item");
        }
    }

    function removeDeposit(index){
        deposits.splice(index, 1);
        setDetailTransaction();
    }

    function setDetailTransaction(){
        var total_price = 0;
        var total_service_charge = 0;
        var total_tax = 0;
        var stamp_duty = parseFloat(document.getElementById("stamp_duty").value);
        var round_price = 0;
        var total_deposit = 0;
        var total_price_on_tax = 0;
         var has_deduction = document.getElementById("has_deduction").value;
        var deduction_price = parseFloat(document.getElementById("deduction_price_text").value);

        var row_proforma_details = '';
        var row_deposit_details = '';

        for(var i = 0; i < proforma_details.length; i++){
            row_proforma_details += '<tr>';
                row_proforma_details += '<td><a class="btn btn-danger" onclick="removeProformaDetail('+i+','+proforma_details[i].array_index+','+"'"+proforma_details[i].type+"'"+')"><i class="fa fa-times"></i></a></td>';
                row_proforma_details += '<td>'+proforma_details[i].type_name+'</td>';
                row_proforma_details += '<td>';
                    row_proforma_details += '<p>'+proforma_details[i].desc+'</p>';
                    row_proforma_details += '<p>'+proforma_details[i].date+'</p>';
                    row_proforma_details += '<p>'+proforma_details[i].time+'</p>';
                    row_proforma_details += '<p>'+proforma_details[i].remarks+'</p>';
                    row_proforma_details += '<input type="hidden" name="booking_detail_id[]" value="'+proforma_details[i].booking_detail_id+'">';
                    row_proforma_details += '<input type="hidden" name="order_detail_id[]" value="'+proforma_details[i].order_detail_id+'">';
                    row_proforma_details += '<input type="hidden" name="booking_cancellation_id[]" value="'+proforma_details[i].booking_cancellation_id+'">';
                    row_proforma_details += '<input type="hidden" name="name[]" value="'+proforma_details[i].name+'">';
                    row_proforma_details += '<input type="hidden" name="detail_price[]" value="'+parseFloat(proforma_details[i].detail_price)+'">';
                    row_proforma_details += '<input type="hidden" name="detail_service_charge[]" value="'+parseFloat(proforma_details[i].detail_service_charge)+'">';
                    row_proforma_details += '<input type="hidden" name="detail_tax_price[]" value="'+parseFloat(proforma_details[i].detail_tax_price)+'">';
                    row_proforma_details += '<input type="hidden" name="detail_remarks[]" value="'+proforma_details[i].remarks+'">';
                row_proforma_details += '</td>';
                row_proforma_details += '<td class="text-right">'+numberWithCommas(parseFloat(proforma_details[i].detail_price))+'</td>';
                row_proforma_details += '<td class="text-right">'+numberWithCommas(parseFloat(proforma_details[i].detail_service_charge))+'</td>';
                row_proforma_details += '<td class="text-right">'+numberWithCommas(parseFloat(proforma_details[i].detail_tax_price))+'</td>';
                row_proforma_details += '<td class="text-right">'+numberWithCommas(parseFloat(proforma_details[i].detail_price + proforma_details[i].detail_service_charge + proforma_details[i].detail_tax_price))+'</td>';

            row_proforma_details += '</tr>';

            total_price = parseFloat(total_price) + parseFloat(proforma_details[i].detail_price);
            
            total_service_charge = parseFloat(total_service_charge) + parseFloat(proforma_details[i].detail_service_charge);
            total_tax = parseFloat(total_tax) + parseFloat(proforma_details[i].detail_tax_price);
        }

        for(var i = 0; i < deposits.length; i++){
            var deposit_type = '';
            var deposit_detail = parseFloat(deposits[i].total_deposit);

            if(deposits[i].payment_status != 'PA'){
                deposit_detail = parseFloat(deposit_detail) - parseFloat(deposits[i].total_paid);
            }else{
                deposit_detail = parseFloat(deposit_detail) * -1;
            }

            if(deposits[i].category == "booking_fee"){
                deposit_type = 'Booking Fee';
            }else if(deposits[i].category == "down_payment"){
                deposit_type = 'Down Payment (DP)';
            }else if(deposits[i].category == "security_deposit"){
                deposit_type = 'Security Deposit';
            }

            total_deposit = parseFloat(total_deposit) + parseFloat(deposit_detail);

            row_deposit_details += "<tr>";
                row_deposit_details += '<td><a class="btn btn-danger" onclick="removeDeposit('+i+')"><i class="fa fa-times"></i></a></td>';
                row_deposit_details += '<td colspan="3">';
                    row_deposit_details += deposits[i].code;
                    row_deposit_details += '<input type="hidden" name="deposit_id[]" value="'+deposits[i].id+'">';
                row_deposit_details += '</td>';
                row_deposit_details += '<td colspan="2">'+deposit_type+'</td>';
                row_deposit_details += '<td class="text-right">'+numberWithCommas(deposits[i].total_deposit)+'</td>';
            row_deposit_details += "</tr>";
        }

        total_price = Math.round(total_price);
        total_service_charge = Math.round(total_service_charge);
        total_tax = Math.round(total_tax);
			
		var total = total_price + total_service_charge ;
        total_price_on_tax = Math.round((11/12) * total);
        
        var grand_total = parseFloat(total_price) + parseFloat(total_service_charge) + parseFloat(total_tax) + parseFloat(stamp_duty);
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

        if(has_deduction == 'Y'){
            grand_total = parseFloat(grand_total) + parseFloat(total_deposit) - deduction_price;
        }else{
            grand_total = parseFloat(grand_total) + parseFloat(total_deposit);
        }

        document.getElementById("row_proforma_details").innerHTML = row_proforma_details;
        document.getElementById("row_deposit_details").innerHTML = row_deposit_details;

        document.getElementById("format_total_price").value = numberWithCommas(total_price);
        document.getElementById("format_total_service_charge").value = numberWithCommas(total_service_charge);
        document.getElementById("format_total_tax_price").value = numberWithCommas(total_tax);
        document.getElementById("format_price_on_tax").value = numberWithCommas(total_price_on_tax);
        
        document.getElementById("view_round_price").value = numberWithCommas(parseFloat(round_price));
        document.getElementById("view_total_deposit").value = numberWithCommas(parseFloat(total_deposit));
        document.getElementById("total_deposit").value = parseFloat(total_deposit);
        document.getElementById("grand_total").value = numberWithCommas(grand_total);

        document.getElementById("total_price").value = total_price;
        document.getElementById("total_service_charge").value = total_service_charge;
        document.getElementById("total_price_on_tax").value = total_price_on_tax;
        document.getElementById("total_tax_price").value = total_tax;
        document.getElementById("round_price").value = round_price;
    }

    function removeProformaDetail(array_index, array_index_of_type, type){

        if(type == "serviced_office"){
            document.getElementById("serviced_office_id_"+array_index_of_type).checked = false;
        }else if(type == 'regular_office'){
            document.getElementById("regular_office_id_"+array_index_of_type).checked = false;
        }else if(type == 'order_detail'){
            document.getElementById("order_detail_id_"+array_index_of_type).checked = false;
        }else if(type == 'cancellation'){
            document.getElementById("cancellation_id_"+array_index_of_type).checked = false;
        }else{

        }
        proforma_details.splice(array_index);
        checkProformaData();
    }
</script>
@endsection
