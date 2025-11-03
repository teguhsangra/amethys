@extends('layouts.app')

@section('title')
Rakomsis Payment - Editor
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
                    Payment Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Payment
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($payment))
                                    <input type="text" class="form-control" value="{{$payment->location->name}}" readonly>
                                    <input type="hidden" name="location_id" id="location_id" value="{{$payment->location_id}}">
                                @else
                                    <select class="selectpicker form-control" name="location_id" id="location_id" onchange="getData()" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($payment)){
                                                    if($payment->location_id == $detail->id){
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
                                @if(!empty(Request::get('action_status')) || !empty($payment))
                                    <input type="text" class="form-control" value="{{$payment->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$payment->customer_id}}">
                                @else
                                <select class="selectpicker form-control" name="customer_id" id="customer_id" onchange="getData()" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($customers as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($payment)){
                                                if($payment->customer_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                            if(!empty(Request::get('customer_id'))){
                                                if(Request::get('customer_id') == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}} >{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Available Deposit</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('total_available_deposit') ? ' has-error' : '' }}">
                                <input type="text" name="total_available_deposit" id="total_available_deposit" class="form-control" readonly @if(!empty($payment)) value="{{ number_format($payment->customer->total_security_deposit, 0, ',', '.') }}" @endif>
                                <label class="error">{{ $errors->first('total_available_deposit') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Payment Date</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('payment_date') ? ' has-error' : '' }}">
                                <input type="text" name="payment_date" id="payment_date" class="form-control datepicker" @if(!empty($payment)) value="{{ date('m/d/Y', strtotime($payment->payment_date)) }}" @endif @if(!empty(Request::get('action_status'))) readonly @endif>
                                <label class="error">{{ $errors->first('payment_date') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($payment)){{ $payment->remarks }}@endif</textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="payment_for">
                        <label class="col-sm-2 col-form-label">Selection</label>
                        <div class="col-sm-10">
                            <ul class="nav nav-pills nav-pills-warning" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#invoice" role="tablist">
                                        Invoice
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#deposit" role="tablist">
                                        Deposit
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content tab-space">
                                <div class="tab-pane active" id="invoice">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="text-success text-center">
                                                <tr>
                                                    <th class="text-center">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" id="select_all_invoice">
                                                                <span class="form-check-sign">
                                                                <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th>Invoice No</th>
                                                    <th>Total Invoice</th>
                                                </tr>
                                            </thead>
                                            <tbody id="inquiry_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="deposit">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="text-warning text-center">
                                                <tr>
                                                    <th class="text-center">
                                                        <div class="form-check">
                                                            <label class="form-check-label">
                                                                <input class="form-check-input" type="checkbox" id="select_all_deposit">
                                                                <span class="form-check-sign">
                                                                <span class="check"></span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </th>
                                                    <th>Deposit No</th>
                                                    <th>Total Deposit</th>
                                                </tr>
                                            </thead>
                                            <tbody id="deposit_list"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="payment">
                        <label class="col-sm-2 col-form-label">Payment List</label>
                        <div class="col-sm-10">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th>Payment Type</th>
                                            <th>Bank</th>
                                            <th>Card No</th>
                                            <th>Total</th>
                                            <th>
                                                <a class="btn btn-success btn-round text-white" data-toggle="modal" data-target="#cashModel"><i class="material-icons">add</i> Cash</a>
                                                <div class="modal fade" id="cashModel" tabindex="-1" role="dialog" aria-labelledby="cashModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Input Cash</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                    <i class="material-icons">clear</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Amount</label>
                                                                            <input type="text" class="form-control" id="format_cash_amount" onchange="changeToCurrencyFormat('format_cash_amount','cash_amount')">
                                                                            <input type="hidden" class="form-control" id="cash_amount" value="0">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                                <button type="button" onclick="setCash()" class="btn btn-success btn-link pull-right">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <a class="btn btn-info btn-round text-white" data-toggle="modal" data-target="#nonCashModel"><i class="material-icons">add</i> Non Cash</a>
                                                <div class="modal fade" id="nonCashModel" tabindex="-1" role="dialog" aria-labelledby="nonCashModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Create New Non Cash</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                <i class="material-icons">clear</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Amount</label>
                                                                            <input type="text" class="form-control" id="format_non_cash_amount" onchange="changeToCurrencyFormat('format_non_cash_amount','non_cash_amount')">
                                                                            <input type="hidden" class="form-control" id="non_cash_amount" value="0">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Non Cash Type</label>
                                                                            <div class="form-group bmd-form-group">
                                                                                <input type="hidden" id="non_cash_name">
                                                                                <select class="selectpicker form-control" name="non_cash_id" id="non_cash_id" onchange="getNonCashDetail(this.value)" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                                                                    <option value="" disabled selected>Select Your Option</option>
                                                                                    @foreach($non_cashes as $detail)
                                                                                        <option value="{{ $detail->id }}" id="{{ $detail->name }}" >{{ $detail->name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Bank</label>
                                                                            <div class="form-group bmd-form-group">
                                                                                <input type="hidden" id="bank_account_name">
                                                                                <select class="selectpicker form-control" name="bank_account_id" id="bank_account_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                                                                    <option value="" disabled selected>Select Your Option</option>
                                                                                    @foreach($bank_accounts as $detail)
                                                                                        <option value="{{ $detail->id }}" id="{{ $detail->bank_name }} : {{ $detail->account_no }} a/n {{ $detail->account_name }}" >{{ $detail->bank_name }} : {{ $detail->account_no }} a/n {{ $detail->account_name }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Account Name</label>
                                                                            <input type="text" class="form-control" name="account_name">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Account Number</label>
                                                                            <input type="text" class="form-control" name="account_number">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="card_detail" style="display:none">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Card Type</label>
                                                                            <div class="checkbox-radios">
                                                                                <div class="form-check">
                                                                                    <label class="form-check-label">
                                                                                        <input class="form-check-input" type="radio" name="card_type" value="CREDIT"> Credit
                                                                                        <span class="circle">
                                                                                            <span class="check"></span>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="form-check">
                                                                                    <label class="form-check-label">
                                                                                        <input class="form-check-input" type="radio" name="card_type" value="DEBIT"> Debit
                                                                                        <span class="circle">
                                                                                            <span class="check"></span>
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Card Number</label>
                                                                            <input type="text" class="form-control" name="card_number" maxlength="16">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Card Holder Name</label>
                                                                            <input type="text" class="form-control" name="card_holder_name">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Batch</label>
                                                                            <input type="text" class="form-control" name="batch">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Description</label>
                                                                            <div class="form-group bmd-form-group">
                                                                                <textarea name="description" class="form-control"></textarea>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                                <button type="button" onclick="setNonCash()" class="btn btn-success btn-link pull-right">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <a class="btn btn-primary btn-round text-white" data-toggle="modal" data-target="#depositModel"><i class="material-icons">add</i> Deposit</a>
                                                <div class="modal fade" id="depositModel" tabindex="-1" role="dialog" aria-labelledby="depositModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Input Deposit</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                    <i class="material-icons">clear</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Amount</label>
                                                                            <input type="text" class="form-control" id="format_deposit_amount" onchange="changeToCurrencyFormat('format_deposit_amount','deposit_amount')">
                                                                            <input type="hidden" class="form-control" id="deposit_amount" value="0">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                                <button type="button" onclick="setDeposit()" class="btn btn-success btn-link pull-right">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <a class="btn btn-rose btn-round text-white" data-toggle="modal" data-target="#whtModel"><i class="material-icons">add</i> With Holding Tax</a>
                                                <div class="modal fade" id="whtModel" tabindex="-1" role="dialog" aria-labelledby="whtModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Input With Holding Tax</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                    <i class="material-icons">clear</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Amount</label>
                                                                            <input type="text" class="form-control" id="format_wht_amount" onchange="changeToCurrencyFormat('format_wht_amount','wht_amount')">
                                                                            <input type="hidden" class="form-control" id="wht_amount" value="0">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                                <button type="button" onclick="setWHT()" class="btn btn-success btn-link pull-right">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <a class="btn btn-warning btn-round text-white" data-toggle="modal" data-target="#lgModel"><i class="material-icons">add</i> Letter Of Guarantee</a>
                                                <div class="modal fade" id="lgModel" tabindex="-1" role="dialog" aria-labelledby="lgModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Input Letter Of Guarantee</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                    <i class="material-icons">clear</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Amount</label>
                                                                            <input type="text" class="form-control" id="format_lg_amount" onchange="changeToCurrencyFormat('format_lg_amount','lg_amount')">
                                                                            <input type="hidden" class="form-control" id="lg_amount" value="0">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                                <button type="button" onclick="setLetterOfGuarantee()" class="btn btn-success btn-link pull-right">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br>
                                                <a class="btn btn-default btn-round text-white" data-toggle="modal" data-target="#otherPaymentModel"><i class="material-icons">add</i> Other Payment</a>
                                                <div class="modal fade" id="otherPaymentModel" tabindex="-1" role="dialog" aria-labelledby="otherPaymentModelLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Input Other</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                                    <i class="material-icons">clear</i>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Amount</label>
                                                                            <input type="text" class="form-control" id="format_other_payment_amount" onchange="changeToCurrencyFormat('format_other_payment_amount','other_payment_amount')">
                                                                            <input type="hidden" class="form-control" id="other_payment_amount" value="0">
                                                                            <span class="material-input"></span>
                                                                            <span class="material-input"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group bmd-form-group is-filled">
                                                                            <label class="label-control">Description</label>
                                                                            <input type="text" class="form-control" name="description" id="desc_other_payment">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                                                                <button type="button" onclick="setOtherPayment()" class="btn btn-success btn-link pull-right">Save</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_payment"></tbody>
                                    <tfoot>
                                        <td colspan="3">Grand Total</td>
                                        <td id="format_total_payment"></td>
                                        <td>
                                            <input type="hidden" name="total_payment" id="total_payment">
                                        </td>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="payment_allocation">
                        <label class="col-sm-2 col-form-label">Payment Allocation</label>
                        <div class="col-sm-10">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th>Type</th>
                                            <th>Code</th>
                                            <th>Total Need</th>
                                            <th>Total Allocation</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_payment_allocation"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">Grand Total</td>
                                            <td id="grand_total_need"></td>
                                            <td id="total_allocation"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Total Not Allocation</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('total_not_allocate') ? ' has-error' : '' }}">
                                <input type="text" id="format_total_not_allocate" class="form-control" readonly @if(!empty($payment)) value="{{ number_format($payment->total_not_allocate, 0, ',', '.') }}" @endif>
                                <input type="hidden" id="total_not_allocate" name="total_not_allocate">
                                <label class="error">{{ $errors->first('total_not_allocate') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Payment Photo(s) <br> <small>You can upload with holding tax, payment receipt, letter of guarantee, and others</small></label>
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="col-sm-3 text-center">
                                    <label class="form-label">With Holding Tax Receipt</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                        <div class="fileupload-new thumbnail">
                                            @if(!empty($payment))
                                                @if($payment->with_holding_tax != null)
                                                    <img src="{{ asset($payment->with_holding_tax) }}" alt="with_holding_tax" width="150">
                                                @else
                                                    <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="with_holding_tax" width="150">
                                                @endif
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="with_holding_tax" width="150">
                                            @endif
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                                        <div>
                                            <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                                <input type="file" id="with_holding_tax_doc" name="with_holding_tax" />
                                                <input type="hidden" id="with_holding_tax_doc_for_update" @if(!empty($payment)) value="{{ $payment->with_holding_tax }}" @else value="" @endif>
                                            </span>
                                            <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                                <i class="fa fa-times"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="form-label">Other Photo 1</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                        <div class="fileupload-new thumbnail">
                                            @if(!empty($payment))
                                                @if($payment->other_doc_1 != null)
                                                    <img src="{{ asset($payment->other_doc_1) }}" alt="other_doc_1" width="150">
                                                @else
                                                    <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_1" width="150">
                                                @endif
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_1" width="150">
                                            @endif
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                                        <div>
                                            <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                                <input type="file" id="other_doc_1" name="other_doc_1"/>
                                                <input type="hidden" id="other_doc_1_for_update" @if(!empty($payment)) value="{{ $payment->other_doc_1 }}" @else value="" @endif>
                                            </span>
                                            <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                                <i class="fa fa-times"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="form-label">Other Photo 2</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                        <div class="fileupload-new thumbnail">
                                            @if(!empty($payment))
                                                @if($payment->other_doc_2 != null)
                                                    <img src="{{ asset($payment->other_doc_2) }}" alt="other_doc_2" width="150">
                                                @else
                                                    <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_2" width="150">
                                                @endif
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_2" width="150">
                                            @endif
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                                        <div>
                                            <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                                <input type="file" id="other_doc_2" name="other_doc_2"/>
                                                <input type="hidden" id="other_doc_2_for_update" @if(!empty($payment)) value="{{ $payment->other_doc_2 }}" @else value="" @endif>
                                            </span>
                                            <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                                <i class="fa fa-times"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">
                                    <label class="form-label">Other Photo 3</label>
                                    <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                                        <div class="fileupload-new thumbnail">
                                            @if(!empty($payment))
                                                @if($payment->other_doc_3 != null)
                                                    <img src="{{ asset($payment->other_doc_3) }}" alt="other_doc_3" width="150">
                                                @else
                                                    <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_3" width="150">
                                                @endif
                                            @else
                                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="other_doc_3" width="150">
                                            @endif
                                        </div>
                                        <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                                        <div>
                                            <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                                <input type="file" id="other_doc_3" name="other_doc_3"/>
                                                <input type="hidden" id="other_doc_3_for_update" @if(!empty($payment)) value="{{ $payment->other_doc_3 }}" @else value="" @endif>
                                            </span>
                                            <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                                <i class="fa fa-times"></i> Remove
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
    var payment_allocations = new Array;
    var cash = 0;
    var deposit_payment = 0;
    var with_holding_tax = 0;
    var non_cash = new Array;
    var letter_of_guarantee = 0;
    var other_payment = new Array;
    var total_allocated = 0;
    var total_payment = 0;
    var total_available_deposit = 0;
    var total_list_deposit = 0;
    var total_list_invoice = 0;

    getData();
    @if(!empty($payment))
        @foreach($payment->payment_detail as $payment_detail)
            @if($payment_detail->payment_type == "CASH")
                cash = '{{ $payment_detail->amount }}';
            @endif

            @if($payment_detail->payment_type == "DEPOSIT")
                deposit_payment = '{{ $payment_detail->amount }}';
            @endif

            @if($payment_detail->payment_type == "WHT")
                with_holding_tax = '{{ $payment_detail->amount }}';
            @endif

            @if($payment_detail->payment_type == "LG")
                letter_of_guarantee = '{{ $payment_detail->amount }}';
            @endif

            @if($payment_detail->payment_type == "NON_CASH")
                var new_non_cash = new Array;

                new_non_cash['non_cash_id'] = '{{ $payment_detail->non_cash_id }}';
                new_non_cash['bank_account_id'] = '{{ $payment_detail->bank_account_id }}';
                new_non_cash['amount'] = '{{ $payment_detail->amount }}';
                new_non_cash['non_cash_name'] = '{{ $payment_detail->non_cash->name }}';
                new_non_cash['bank_account_name'] = '{{ $payment_detail->bank_account_name }}';
                new_non_cash['account_name'] = '{{ $payment_detail->account_name }}';
                new_non_cash['account_number'] = '{{ $payment_detail->account_number }}';
                new_non_cash['card_type'] = '{{ $payment_detail->card_type }}';
                new_non_cash['card_holder_name'] = '{{ $payment_detail->card_holder_name }}';
                new_non_cash['card_number'] = '{{ $payment_detail->card_number }}';
                new_non_cash['batch'] = '{{ $payment_detail->batch }}';
                new_non_cash['description'] = '{{ $payment_detail->description }}';

                non_cash.push(new_non_cash);
            @endif

            @if($payment_detail->payment_type == "OTHER")
                var new_other_payment = new Array;

                new_other_payment['amount'] = '{{ $payment_detail->amount }}';
                new_other_payment['description'] = '{{ $payment_detail->description }}';

                other_payment.push(new_other_payment);
            @endif
        @endforeach

        @foreach($payment->payment_allocation as $payment_allocation)
            var array_from_data = new Array;
            array_from_data['invoice_id'] = '{{ $payment_allocation->invoice_id }}';
            array_from_data['deposit_id'] = '{{ $payment_allocation->deposit_id }}';

            @if($payment_allocation->invoice_id != null)
                array_from_data['type'] = 'invoice';
                array_from_data['code'] = '{{ $payment_allocation->invoice->code }}';
            @else
                array_from_data['type'] = 'deposit';
                array_from_data['code'] = '{{ $payment_allocation->deposit->code }}';
            @endif

            array_from_data['total_need'] = '{{ $payment_allocation->total_need }}';
            array_from_data['payment_allocation'] = '{{ $payment_allocation->payment_allocation }}';

            payment_allocations.push(array_from_data);
        @endforeach

        loadPaymentDetail();
        loadPaymentAllocation();
    @endif

    @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')))
        getDeposit();
    @endif

    $(function() {
        $("#customer_id").change(function() {
            getDeposit();
        });

        $("#select_all_invoice").click(function() {
            var checked_status = document.getElementById("select_all_invoice").checked;

            for(var i=0; i < total_list_invoice; i++){
                document.getElementById("invoice_id_"+i).checked = checked_status;
            }
            checkPaymentData();
        });

        $("#select_all_deposit").click(function() {
            var checked_status = document.getElementById("select_all_deposit").checked;

            for(var i=0; i < total_list_deposit; i++){
                document.getElementById("deposit_id_"+i).checked = checked_status;
            }
            checkPaymentData();
        });

        $("#non_cash_id").change(function() {
            var non_cash_id = document.getElementById("non_cash_id").value;
            var non_cash_name = $(this).children(":selected").attr("id");
            var link = "{{ url('non_cash/get_by_id') }}";
            var url = link+"/"+non_cash_id;
            $.get(url, function (data){
                if(data['has_card'] == 'Y'){
                    $("#card_detail").show();
                }else{
                    $("#card_detail").hide();
                }
                document.getElementById("non_cash_name").value = non_cash_name;
            });
        });

        $("#bank_account_id").change(function() {
            var bank_account_name = $(this).children(":selected").attr("id");
            document.getElementById("bank_account_name").value = bank_account_name;
        });
    });

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
        var with_holding_tax_doc = document.getElementById("with_holding_tax_doc").value;
        var payment_date = document.getElementById("payment_date").value;
        var other_doc_1 = document.getElementById("other_doc_1").value;
        var other_doc_2 = document.getElementById("other_doc_2").value;
        var other_doc_3 = document.getElementById("other_doc_3").value;
        var with_holding_tax_doc_for_update = document.getElementById("with_holding_tax_doc_for_update").value;
        var other_doc_1_for_update = document.getElementById("other_doc_1_for_update").value;
        var other_doc_2_for_update = document.getElementById("other_doc_2_for_update").value;
        var other_doc_3_for_update = document.getElementById("other_doc_3_for_update").value;

        if(customer_id == ""){ // Cek Customer
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select customer</b> </span>'+
                            '</div>';
        }

        if(location_id == ""){ // Cek Location
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select location</b> </span>'+
                            '</div>';
        }

        if(payment_date == ""){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select payment date</b> </span>'+
                            '</div>';
        }

        if(total_payment == 0){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to input total payment</b> </span>'+
                            '</div>';
        }

        if(payment_allocations.length == 0){ // Cek allocation
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select invoice or deposit</b> </span>'+
                            '</div>';
        }

        if(total_allocated == 0){ // Cek total allocated
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to allocate payment</b> </span>'+
                            '</div>';
        }

        if(with_holding_tax > 0){ // Cek WHT Receipt
            if(with_holding_tax_doc == "" && with_holding_tax_doc_for_update == ""){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to upload with holding tax receipt</b> </span>'+
                                '</div>';
            }
        }

        if(letter_of_guarantee > 0){ // Cek Letter Of Guarantee
            if(other_doc_1 == "" && other_doc_2 == "" && other_doc_3 == "" && other_doc_1_for_update == "" && other_doc_2_for_update == "" && other_doc_3_for_update == ""){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! You have to upload letter of guarantee receipt</b> </span>'+
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

    function getData(){
        var location_id = $("#location_id option:selected").val();
        var customer_id = $("#customer_id option:selected").val();
        var link_invoice = "{{ url('invoice/get_by_param') }}";
        var link_deposit = "{{ url('deposit/get_by_param') }}";

        if(location_id != '' && customer_id != ''){
            var url_invoice = link_invoice+"/"+location_id+'/'+customer_id+'/PA';
            var url_deposit = link_deposit+"/"+location_id+'/'+customer_id+'/PA';

            $.get(url_invoice, function (data){
                var invoice_list = '';
                total_list_invoice = data.length;
                for(var i=0; i < data.length; i++){
                    var checked = '';
                    @if(!empty($payment))
                        @foreach($payment->payment_allocation as $payment_allocation)
                            if(data[i].id == '{{ $payment_allocation->invoice_id }}'){
                                checked = 'checked';
                            }
                        @endforeach
                    @endif

                    @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('invoice_id')))
                        if(data[i].id == "{{ Request::get('invoice_id') }}"){
                            checked = 'checked';
                        }
                    @endif

                    var invoice_total_need = parseFloat(data[i].total_price) + parseFloat(data[i].total_service_charge) + parseFloat(data[i].total_tax_price) + parseFloat(data[i].stamp_duty) + parseFloat(data[i].round_price) + parseFloat(data[i].total_penalty) - parseFloat(data[i].total_paid);
                    invoice_list += '<tr>';
                        invoice_list += '<td class="text-center">';
                            invoice_list += '<div class="form-check">';
                                invoice_list += '<label class="form-check-label">';
                                    invoice_list += '<input class="form-check-input" type="checkbox" value="'+data[i].id+'" id="invoice_id_'+i+'" onchange="checkPaymentData()" '+checked+'>';
                                    invoice_list += '<span class="form-check-sign">';
                                        invoice_list += '<span class="check"></span>';
                                    invoice_list += '</span>';
                                invoice_list += '</label>';
                            invoice_list += '</div>';
                        invoice_list += '<td class="text-center">'+data[i].code+'</td>';
                        invoice_list += '<td class="text-center">';
                            invoice_list += numberWithCommas(invoice_total_need);
                            invoice_list += '<input type="hidden" value="'+data[i].code+'" id="invoice_code_'+i+'">';
                            invoice_list += '<input type="hidden" value="'+invoice_total_need+'" id="invoice_total_need_'+i+'">';
                        invoice_list += '</td>';
                    invoice_list += '</tr>';
                }
                if(invoice_list != '') document.getElementById("inquiry_list").innerHTML = invoice_list;
                @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('invoice_id')))
                    checkPaymentData();
                @endif
            });

            $.get(url_deposit, function (data){
                console.log(data);
                var deposit_list = '';
                var checked = '';
                total_list_deposit = data.length;
                for(var i=0; i < data.length; i++){
                    @if(!empty($payment))
                        @foreach($payment->payment_allocation as $payment_allocation)
                            if(data[i].id == '{{ $payment_allocation->deposit_id }}'){
                                checked = 'checked';
                            }
                        @endforeach
                    @endif

                    @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('deposit_id')))
                        if(data[i].id == "{{ Request::get('deposit_id') }}"){
                            checked = 'checked';
                        }
                    @endif

                    var deposit_total_need = parseFloat(data[i].total_deposit) + parseFloat(data[i].stamp_duty) - parseFloat(data[i].total_paid);
                    deposit_list += '<tr>';
                        deposit_list += '<td class="text-center">';
                            deposit_list += '<div class="form-check">';
                                deposit_list += '<label class="form-check-label">';
                                    deposit_list += '<input class="form-check-input" type="checkbox" value="'+data[i].id+'" id="deposit_id_'+i+'" onchange="checkPaymentData" '+checked+'>';
                                    deposit_list += '<span class="form-check-sign">';
                                        deposit_list += '<span class="check"></span>';
                                    deposit_list += '</span>';
                                deposit_list += '</label>';
                            deposit_list += '</div>';
                        deposit_list += '</td>';
                        deposit_list += '<td class="text-center">'+data[i].code+'</td>';
                        deposit_list += '<td class="text-center">';
                            deposit_list += numberWithCommas(deposit_total_need);
                            deposit_list += '<input type="hidden" name="code[]" value="'+data[i].code+'" id="deposit_code_'+i+'">';
                            deposit_list += '<input type="hidden" name="deposit_total_need[]" value="'+deposit_total_need+'" id="deposit_total_need_'+i+'">';
                        deposit_list += '</td>';
                    deposit_list += '</tr>';
                }
                if(deposit_list != '') document.getElementById("deposit_list").innerHTML = deposit_list;
                @if(!empty(Request::get('location_id')) && !empty(Request::get('customer_id')) && !empty(Request::get('deposit_id')))
                    checkPaymentData();
                @endif
            });
        }
    }

    function getDeposit(){
        var customer_id = document.getElementById("customer_id").value;
        var link = "{{ url('customer/get_by_id') }}";
        var url = link+'/'+customer_id;

        $.get(url, function (data){
            total_available_deposit = data.total_security_deposit;
            if(total_available_deposit == 0){
                document.getElementById("total_available_deposit").value = numberWithCommas(0);
            }else{
                document.getElementById("total_available_deposit").value = numberWithCommas(total_available_deposit);
            }
        });
    }

    function checkPaymentData(){
        total_allocated = 0;
        payment_allocations = new Array;
        for(var i=0; i < total_list_invoice; i++){
            var check_status = document.getElementById("invoice_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var total_need = document.getElementById("invoice_total_need_"+i).value;
                var detail_allocated = 0;

                if(total_payment >= total_need){
                    detail_allocated = total_need;
                }else{
                    detail_allocated = total_payment;
                }
                total_allocated = parseFloat(total_allocated) + parseFloat(detail_allocated);

                new_array['invoice_id'] = document.getElementById("invoice_id_"+i).value;
                new_array['deposit_id'] = '';
                new_array['type'] = 'invoice';
                new_array['code'] = document.getElementById("invoice_code_"+i).value;
                new_array['total_need'] = total_need;
                new_array['payment_allocation'] = detail_allocated;

                payment_allocations.push(new_array);
            }
        }
        for(var i=0; i < total_list_deposit; i++){
            var check_status = document.getElementById("deposit_id_"+i).checked;
            if(check_status){
                var new_array = new Array;
                var total_need = document.getElementById("deposit_total_need_"+i).value;
                var detail_allocated = 0;

                if(total_payment >= total_need){
                    detail_allocated = total_need;
                }else{
                    detail_allocated = total_payment;
                }
                total_allocated = parseFloat(total_allocated) + parseFloat(detail_allocated);

                new_array['invoice_id'] = '';
                new_array['deposit_id'] = document.getElementById("deposit_id_"+i).value;
                new_array['type'] = 'invoice';
                new_array['code'] = document.getElementById("deposit_code_"+i).value;
                new_array['total_need'] = total_need;
                new_array['payment_allocation'] = detail_allocated;

                payment_allocations.push(new_array);
            }
        }
        loadPaymentDetail();
        loadPaymentAllocation();
    }

    function getNonCashDetail(non_cash_id){
        var link = "{{ url('non_cash/get_by_id') }}";
        var url = link+"/"+non_cash_id;
        $.get(url, function (data){
            if(data['has_card'] == 'Y'){
                $("#card_detail").show();
            }else{
                $("#card_detail").hide();
            }
        });
    }

    function setCash(){
        var cash_amount = $("#cash_amount").val();
        cash = cash_amount;
        checkPaymentData();
        $('#cashModel').modal('toggle');
    }

    function setNonCash(){
        var non_cash_id = document.getElementsByName("non_cash_id")[0].value;
        var bank_account_id = document.getElementsByName("bank_account_id")[0].value;
        var non_cash_name = $("#non_cash_name").val();
        var bank_account_name = $("#bank_account_name").val();
        var account_name = $("input[name='account_name']").val();
        var account_number = $("input[name='account_number']").val();
        var card_type = $("input[name=card_type]:checked").val();
        var card_number = $("input[name=card_number]").val();
        var batch = $("input[name='batch']").val();
        var description = $("input[name='description']").val();
        var amount = $("#non_cash_amount").val();

        if(card_type == '') card_type = 'NO_CARD';

        var new_detail = new Array;

        new_detail['non_cash_id'] = non_cash_id;
        new_detail['bank_account_id'] = bank_account_id;
        new_detail['non_cash_name'] = non_cash_name;
        new_detail['bank_account_name'] = bank_account_name;
        new_detail['account_name'] = account_name;
        new_detail['account_number'] = account_number;
        new_detail['card_type'] = card_type;
        new_detail['card_number'] = card_number;
        new_detail['batch'] = batch;
        new_detail['description'] = description;
        new_detail['amount'] = amount;

        if(amount == 0){
            alert("You have input amount");
        }else if(non_cash_id == ''){
            alert("You have to select non cash type");
        }else if(bank_account_id == ''){
            alert("You have to select bank");
        }else{
            non_cash.push(new_detail);
        }

        checkPaymentData();
        $('#nonCashModel').modal('toggle');
    }

    function setDeposit(){
        var deposit_amount = $("#deposit_amount").val();
        deposit_payment = deposit_amount;
        if(deposit_payment > total_available_deposit){
            alert("Sorry, this customer's deposit is not enough for the inputed amount");
        }else{
            checkPaymentData();
        }
        $('#depositModel').modal('toggle');
    }

    function setWHT(){
        var wht_amount = $("#wht_amount").val();
        with_holding_tax = wht_amount;
        checkPaymentData();
        $('#whtModel').modal('toggle');
    }

    function setLetterOfGuarantee(){
        var lg_amount = $("#lg_amount").val();
        letter_of_guarantee = lg_amount;
        checkPaymentData();
        $('#lgModel').modal('toggle');
    }

    function setOtherPayment(){
        var amount = $("#other_payment_amount").val();
        var description = $("#desc_other_payment").val();

        var new_detail = new Array;

        new_detail['description'] = description;
        new_detail['amount'] = amount;

        if(amount == 0){
            alert("You have input amount");
        }else if(description == ""){
            alert("You have input description");
        }else{
            other_payment.push(new_detail);
        }

        checkPaymentData();
        $('#otherPaymentModel').modal('toggle');
    }

    function updateCash(){
        var detail_cash = $("#detail_cash").val();
        cash = detail_cash;
        checkPaymentData();
    }

    function updateNonCash(array_index){
        for(var i=0; i < non_cash.length; i++){
            if(i == array_index){
                var detail_amount = $("#detail_non_cash_"+i).val();
                non_cash[i].amount = detail_amount;
                break;
            }
        }
        checkPaymentData();
    }

    function updateDeposit(){
        var detail_deposit = $("#detail_deposit").val();
        if(deposit_amount > total_available_deposit){
            alert("Sorry, this customer's deposit is not enough for the inputed amount");
            document.getElementById("detail_deposit").value = deposit_payment;
            document.getElementById("format_detail_deposit").value = numberWithCommas(deposit_payment);
        }else{
            deposit_payment = detail_deposit;
        }
        checkPaymentData();
    }

    function updateWHT(){
        var detail_wht = $("#detail_wht").val();
        with_holding_tax = detail_wht;
        checkPaymentData();
    }

    function updateLetterOfGuarantee(){
        var detail_lg = $("#detail_lg").val();
        letter_of_guarantee = detail_lg;
        checkPaymentData();
    }

    function updateOtherPayment(array_index){
        var detail_amount = $("#detail_other_payment_"+array_index).val();
        other_payment[array_index].amount = detail_amount;
        checkPaymentData();
    }

    function clearCash(){
        cash = 0;
        checkPaymentData();
    }

    function clearDeposit(){
        deposit_payment = 0;
        checkPaymentData();
    }

    function clearWHT(){
        with_holding_tax = 0;
        checkPaymentData();
    }

    function clearNonCash(array_index){
        non_cash.splice(array_index);
        checkPaymentData();
    }

    function clearLetterOfGuarantee(){
        letter_of_guarantee = 0;
        checkPaymentData();
    }

    function clearOtherPayment(array_index){
        other_payment.splice(array_index);
        checkPaymentData();
    }

    function loadPaymentDetail(){
        detail_payment = '';
        total_payment = 0;

        for(var i=0; i < payment_allocations.length; i++){
            if(payment_allocations[i].deposit_id != ''){
                deposit_payment = 0;
                break;
            }
        }

        if(cash > 0){
            detail_payment += '<tr>';

            detail_payment += '<td>Cash</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>';
            detail_payment += '<input type="text" class="form-control" id="format_detail_cash" value="'+numberWithCommas(cash)+'" onchange="changeToCurrencyFormat('+"'format_detail_cash'"+','+"'detail_cash'"+');updateCash();">';
            detail_payment += '<input type="hidden" id="detail_cash" name="payment_amount[]" value="'+cash+'">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="CASH">';
            detail_payment += '<input type="hidden" name="payment_bank_issuer[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_type[]" value="NO_CARD">';
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_batch[]" value="">';
            detail_payment += '<input type="hidden" name="payment_description[]" value="">';
            detail_payment += '<input type="hidden" name="payment_bank_account_id[]" value="">';
            detail_payment += '<input type="hidden" name="payment_non_cash_id[]" value="">';
            detail_payment += '</td>';

            detail_payment += '<td class="text-center"><a class="btn btn-warning" onclick="clearCash()"><i class="fa fa-times"></i> Clear Cash</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(cash);
        }

        if(deposit_payment > 0){
            detail_payment += '<tr>';

            detail_payment += '<td>Deposit</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>';
            detail_payment += '<input type="text" class="form-control" id="format_detail_deposit" value="'+numberWithCommas(deposit_payment)+'" onchange="changeToCurrencyFormat('+"'format_detail_deposit'"+','+"'detail_deposit'"+');updateDeposit();">';
            detail_payment += '<input type="hidden" id="detail_deposit" name="payment_amount[]" value="'+deposit_payment+'">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="DEPOSIT">';
            detail_payment += '<input type="hidden" name="payment_bank_issuer[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_type[]" value="NO_CARD">';
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_batch[]" value="">';
            detail_payment += '<input type="hidden" name="payment_description[]" value="">';
            detail_payment += '<input type="hidden" name="payment_bank_account_id[]" value="">';
            detail_payment += '<input type="hidden" name="payment_non_cash_id[]" value="">';
            detail_payment += '</td>';

            detail_payment += '<td class="text-center"><a class="btn btn-warning" onclick="clearDeposit()"><i class="fa fa-times"></i> Clear Deposit</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(deposit_payment);
        }

        if(with_holding_tax > 0){
            detail_payment += '<tr>';

            detail_payment += '<td>With Holding Tax</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>';
            detail_payment += '<input type="text" class="form-control" id="format_detail_wht" value="'+numberWithCommas(with_holding_tax)+'" onchange="changeToCurrencyFormat('+"'format_detail_wht'"+','+"'detail_wht'"+');updateWHT();">';
            detail_payment += '<input type="hidden" id="detail_wht" name="payment_amount[]" value="'+with_holding_tax+'">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="WHT">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="WHT">';
            detail_payment += '<input type="hidden" name="payment_bank_issuer[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_type[]" value="NO_CARD">';
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_batch[]" value="">';
            detail_payment += '<input type="hidden" name="payment_description[]" value="">';
            detail_payment += '<input type="hidden" name="payment_bank_account_id[]" value="">';
            detail_payment += '<input type="hidden" name="payment_non_cash_id[]" value="">';
            detail_payment += '</td>';

            detail_payment += '<td class="text-center"><a class="btn btn-warning" onclick="clearWHT()"><i class="fa fa-times"></i> Clear With Holding Tax</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(with_holding_tax);
        }

        if(letter_of_guarantee > 0){
            detail_payment += '<tr>';

            detail_payment += '<td>Letter Of Guarantee</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>';
            detail_payment += '<input type="text" class="form-control" id="format_detail_lg" value="'+numberWithCommas(letter_of_guarantee)+'" onchange="changeToCurrencyFormat('+"'format_detail_lg'"+','+"'detail_lg'"+');updateLetterOfGuarantee();">';
            detail_payment += '<input type="hidden" id="detail_lg" name="payment_amount[]" value="'+letter_of_guarantee+'">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="WHT">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="WHT">';
            detail_payment += '<input type="hidden" name="payment_bank_issuer[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_type[]" value="NO_CARD">';
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_batch[]" value="">';
            detail_payment += '<input type="hidden" name="payment_description[]" value="">';
            detail_payment += '<input type="hidden" name="payment_bank_account_id[]" value="">';
            detail_payment += '<input type="hidden" name="payment_non_cash_id[]" value="">';
            detail_payment += '</td>';

            detail_payment += '<td class="text-center"><a class="btn btn-warning" onclick="clearLetterOfGuarantee()"><i class="fa fa-times"></i> Clear Letter Of Guarantee</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(letter_of_guarantee);
        }

        for(var i=0; i < non_cash.length; i++){
            var detail_card = '';

            if(non_cash[i].card_type != '' && non_cash[i].card_number != ''){
                detail_card = non_cash[i].card_type+' <br> '+non_cash[i].card_number;
            }

            if(non_cash[i].account_number == null){
                non_cash[i].account_number = '';
            }

            if(non_cash[i].account_name == null){
                non_cash[i].account_name = '';
            }

            if(non_cash[i].card_type == null){
                non_cash[i].card_type = '';
            }

            if(non_cash[i].card_number == null){
                non_cash[i].card_number = '';
            }

            if(non_cash[i].batch == null){
                non_cash[i].batch = '';
            }

            if(non_cash[i].description == null){
                non_cash[i].description = '';
            }

            detail_payment += '<tr>';

            detail_payment += '<td>'+non_cash[i].non_cash_name+'</td>';

            detail_payment += '<td>'+non_cash[i].bank_account_name+'</td>';

            detail_payment += '<td>'+detail_card+'</td>';

            detail_payment += '<td>';
            detail_payment += '<input type="text" class="form-control" id="format_detail_non_cash_'+i+'" value="'+numberWithCommas(non_cash[i].amount)+'" onchange="changeToCurrencyFormat('+"'format_detail_non_cash_"+i+"'"+','+"'detail_non_cash_"+i+"'"+');updateNonCash('+i+')">';
            detail_payment += '<input type="hidden" id="detail_non_cash_'+i+'" name="payment_amount[]" value="'+non_cash[i].amount+'">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="NON_CASH">';
            detail_payment += '<input type="hidden" name="payment_bank_issuer[]" value="'+non_cash[i].bank_account_name+'">';
            detail_payment += '<input type="hidden" name="payment_account_number[]" value="'+non_cash[i].account_number+'">';
            detail_payment += '<input type="hidden" name="payment_account_name[]" value="'+non_cash[i].account_name+'">';
            detail_payment += '<input type="hidden" name="payment_card_type[]" value="'+non_cash[i].card_type+'">';
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="'+non_cash[i].account_name+'">';
            detail_payment += '<input type="hidden" name="payment_card_number[]" value="'+non_cash[i].card_number+'">';
            detail_payment += '<input type="hidden" name="payment_batch[]" value="'+non_cash[i].batch+'">';
            detail_payment += '<input type="hidden" name="payment_description[]" value="'+non_cash[i].description+'">';
            detail_payment += '<input type="hidden" name="payment_bank_account_id[]" value="'+non_cash[i].bank_account_id+'">';
            detail_payment += '<input type="hidden" name="payment_non_cash_id[]" value="'+non_cash[i].non_cash_id+'">';
            detail_payment += '</td>';

            detail_payment += '<td class="text-center"><a class="btn btn-warning" onclick="clearNonCash('+i+')"><i class="fa fa-times"></i> Clear Non Cash</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(non_cash[i].amount);
        }

        for(var i=0; i < other_payment.length; i++){
            detail_payment += '<tr>';

            detail_payment += '<td>'+other_payment[i].description+'</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>-</td>';

            detail_payment += '<td>';
            detail_payment += '<input type="text" class="form-control" id="format_detail_other_payment_'+i+'" value="'+numberWithCommas(other_payment[i].amount)+'" onchange="changeToCurrencyFormat('+"'format_detail_other_payment_"+i+"'"+','+"'detail_other_payment_"+i+"'"+');updateOtherPayment('+i+')">';
            detail_payment += '<input type="hidden" id="detail_other_payment_'+i+'" name="payment_amount[]" value="'+other_payment[i].amount+'">';
            detail_payment += '<input type="hidden" name="payment_type[]" value="OTHER">';
            detail_payment += '<input type="hidden" name="payment_bank_issuer[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_account_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_type[]" value="NO_CARD">';
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="">';
            detail_payment += '<input type="hidden" name="payment_card_number[]" value="">';
            detail_payment += '<input type="hidden" name="payment_batch[]" value="">';
            detail_payment += '<input type="hidden" name="payment_description[]" value="">';
            detail_payment += '<input type="hidden" name="payment_bank_account_id[]" value="">';
            detail_payment += '<input type="hidden" name="payment_non_cash_id[]" value="">';
            detail_payment += '</td>';

            detail_payment += '<td class="text-center"><a class="btn btn-warning" onclick="clearOtherPayment('+i+')"><i class="fa fa-times"></i> Clear Other Payment</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(other_payment[i].amount);
        }

        document.getElementById("detail_payment").innerHTML = detail_payment;
        document.getElementById("total_payment").value = total_payment;
        document.getElementById("format_total_payment").innerHTML = numberWithCommas(total_payment);

        loadPaymentAllocation();
    }

    function loadPaymentAllocation(){
        detail_payment_allocation = '';
        total_allocated = 0;
        var grand_total_need = 0;
        var total_not_allocate = 0;

        for(var i=0; i < payment_allocations.length; i++){
            var detail_allocated = 0;
            var total_need = payment_allocations[i].total_need;
            grand_total_need = parseFloat(grand_total_need) + parseFloat(total_need);

            detail_payment_allocation += '<tr>';
                detail_payment_allocation += '<td>'+payment_allocations[i].type+'</td>';
                detail_payment_allocation += '<td>'+payment_allocations[i].code+'</td>';
                detail_payment_allocation += '<td>'+numberWithCommas(total_need)+'</td>';
                detail_payment_allocation += '<td>';
                    detail_payment_allocation += '<input type="text" class="form-control" id="format_payment_allocation_'+i+'" value="'+numberWithCommas(payment_allocations[i].payment_allocation)+'" onchange="changeToCurrencyFormat('+"'format_payment_allocation_"+i+"'"+','+"'payment_allocation_"+i+"'"+');updateAllocation('+i+')">';
                    detail_payment_allocation += '<input type="hidden" name="payment_allocation[]" id="payment_allocation_'+i+'"value="'+payment_allocations[i].payment_allocation+'">';
                    detail_payment_allocation += '<input type="hidden" name="invoice_id[]" value="'+payment_allocations[i].invoice_id+'">';
                    detail_payment_allocation += '<input type="hidden" name="deposit_id[]" value="'+payment_allocations[i].deposit_id+'">';
                    detail_payment_allocation += '<input type="hidden" name="total_need[]" value="'+payment_allocations[i].total_need+'">';
                detail_payment_allocation += '</td>';
            detail_payment_allocation += '</tr>';

            total_allocated = parseFloat(total_allocated) + parseFloat(payment_allocations[i].payment_allocation);
        }

        total_not_allocate = parseFloat(total_payment) - parseFloat(total_allocated);

        document.getElementById("grand_total_need").innerHTML = numberWithCommas(grand_total_need);
        document.getElementById("total_allocation").innerHTML = numberWithCommas(total_allocated);
        document.getElementById("detail_payment_allocation").innerHTML = detail_payment_allocation;
        document.getElementById("total_not_allocate").value = total_not_allocate;
        document.getElementById("format_total_not_allocate").value = numberWithCommas(total_not_allocate);
    }

    function updateAllocation(array_index){
        var temp_total_allocation = total_allocated;
        var payment_allocation = parseFloat(document.getElementById("payment_allocation_"+array_index).value);
        if(payment_allocation > payment_allocations[array_index].total_need){
            // Do Nothing
        }else{
            temp_total_allocation = parseFloat(temp_total_allocation) - parseFloat(payment_allocations[array_index].payment_allocation);
            temp_total_allocation = parseFloat(temp_total_allocation) + parseFloat(payment_allocation);
            if(temp_total_allocation > total_payment){
                // Do Nothing
            }else{
                payment_allocations[array_index].payment_allocation = payment_allocation;
            }
        }
        loadPaymentAllocation();
    }
</script>
@endsection
