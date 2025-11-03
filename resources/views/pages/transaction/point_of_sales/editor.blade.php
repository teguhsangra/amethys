@extends('layouts.app')

@section('title')
Rakomsis Point Of Sales - Editor
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
                    Point Of Sales Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Point Of Sales
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <input type="hidden" name="start_date_counted" id="start_date_counted" value="Y">
                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($order))
                                    <input type="text" class="form-control" value="{{$order->location->name}}" readonly>
                                    <input type="hidden" name="location_id" id="location_id" value="{{$order->location_id}}">
                                @else
                                    <select class="selectpicker form-control" name="location_id" id="location_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($order)){
                                                    if($order->location_id == $detail->id){
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
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$order->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$order->customer_id}}">
                                @else
                                <select class="selectpicker form-control" name="customer_id" id="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($customers as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($order)){
                                                if($order->customer_id == $detail->id){
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
                    <div class="row" id="product">
                        <label class="col-sm-2 col-form-label">Product</label>
                        <div class="col-sm-10">
                            <div class="input-group mb-3">
                                <select class="selectpicker form-control col-md-11" id="product_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="">--- Select Product ---</option>
                                    @foreach($products as $detail)
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <a class="btn btn-success btn-round" style="color: #fff;" onclick="addProduct()">
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
                                <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($order)){{ $order->remarks }}@endif</textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Detail Transaction</label>
                        <div class="col-sm-10">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-primary text-center">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="20%">Item Name</th>
                                            <th width="25%">Periode</th>
                                            <th width="15%">Detail Price</th>
                                            <th width="15%">Quantity</th>
                                            <th width="20%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_transaction">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
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
                                                            <input class="form-check-input" type="radio" name="usable_discount" onclick="changeDiscountType('not_use')" value="not_use" @if(!empty($order))  @if($order->usable_discount == "not_use") checked @endif @endif checked> Not Use
                                                            <span class="circle">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="radio" name="usable_discount" onclick="changeDiscountType('percentage')" value="percentage" @if(!empty($order)) @if($order->usable_discount == "percentage") checked @endif @endif> Precentage
                                                            <span class="circle">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="radio" name="usable_discount" onclick="changeDiscountType('price')" value="price" @if(!empty($order)) @if($order->usable_discount == "price") checked @endif @endif> Fix Price
                                                            <span class="circle">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td colspan="2">
                                                <div class="input-group mb-3">
                                                    <input type="number" name="discount_percentage" min="0" max="100" id="discount_percentage"  value="0"  @if(!empty($order)) value="{{ $order->discount_percentage }}" @endif class="form-control text-center" placeholder="Percentage..." style="margin-top: 5px;height: 42px;" onchange="setDiscountValue('percentage',this.value)" readonly>
                                                    <div class="input-group-append">
                                                        <a class="btn btn-default btn-round" style="color: #fff;">%</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" id="format_discount_price" name="format_discount_price" class="form-control text-right" onchange="changeToCurrencyFormat('format_discount_price','discount_price');setDiscountValue('price',this.value)" value="0" @if(!empty($order)) value="{{ number_format($order->discount_price, 0, ',', '.') }}" @endif readonly>
                                                <input type="hidden" id="discount_price" name="discount_price"  value="0" @if(!empty($order)) value="{{ $order->discount_price }}" @endif>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">Service Charge Price</td>
                                            <td id="view_total_service_charge" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Tax Price</td>
                                            <td>
                                                <div class="checkbox-radios">
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="radio" name="tax_status" onclick="countPrice()" value="no_tax" @if(!empty($order)) @if($order->tax_status == "no_tax") checked  @endif @else checked @endif> No Tax
                                                            <span class="circle">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="radio" name="tax_status" onclick="countPrice();" value="exclude" @if(!empty($order)) @if($order->tax_status == "exclude") checked @endif @endif> Exclude
                                                            <span class="circle">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <label class="form-check-label">
                                                            <input class="form-check-input" type="radio" name="tax_status" onclick="countPrice();" value="include" @if(!empty($order)) @if($order->tax_status == "include") checked @endif @endif> Include
                                                            <span class="circle">
                                                                <span class="check"></span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="view_total_tax_price" class="text-right"></td>
                                        </tr>
                                        <tr style="display:none">
                                            <td colspan="5">Rounded Price</td>
                                            <td id="view_round_price" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="5">Total Price</td>
                                            <td id="view_total_price" class="text-right"></td>
                                            <input type="hidden" id="total_price" name="total_price">
                                            <input type="hidden" id="total_service_charge" name="total_service_charge">
                                            <input type="hidden" id="total_tax_price" name="total_tax_price">
                                            <input type="hidden" id="round_price" name="round_price">
                                        </tr>

                                        <!-- <tr>
                                            <td colspan="3">Tax Price</td>
                                            <td id="view_total_tax_price" class="text-right"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Grand Total</td>
                                            <td id="view_grand_total" class="text-right"></td>
                                        </tr> -->
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="type_selection">
                        <label class="col-sm-2 col-form-label">Pay Now ?</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="pay_now" onclick="payNow('N')" value="N" checked> No
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="pay_now" onclick="payNow('Y')" value="Y"> Yes
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="payment" style="display:none">
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
                                                <div class="modal fade" id="cashModel" tabindex="-1" role="dialog" aria-labelledby="customerModelLabel" aria-hidden="true">
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

                                                <a class="btn btn-success btn-round text-white" data-toggle="modal" data-target="#nonCashModel"><i class="material-icons">add</i> Non Cash</a>
                                                <div class="modal fade" id="nonCashModel" tabindex="-1" role="dialog" aria-labelledby="customerModelLabel" aria-hidden="true">
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
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail_payment"></tbody>
                                    <tfoot>
                                        <td colspan="3">Grand Total</td>
                                        <td id="format_total_payment"></td>
                                        <td></td>
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

    var selected_product = new Array;
    var cash = 0;
    var non_cash = new Array;
    var total_payment = 0;
    var total_transaction = 0;
    var tax_percentage = {{ $tax_percentage }};
    var service_charge = {{ $service_charge }};
    var total_mod_rounding = {{ $total_mod_rounding }};
    var new_item = new Array;
    var link_detail_transaction = "{{ url('product/get_by_id') }}";

    @if(!empty($order))
         @foreach($order->order_detail as  $no => $order_detail)
            var tax_status = '{{ $order->tax_status }}';
            new_item = new Array;
            
            new_item['id'] = '{{ $order_detail->product_id }}';
            new_item['name'] = '{{ $order_detail->product->name }}';
            new_item['price_type'] = '{{ $order_detail->product->price_type }}';

            if(tax_status == "include"){
                new_item['price'] = '{{ $order_detail->detail_price + $order_detail->detail_service_charge + $order_detail->detail_tax_price }}';
            }else{
                new_item['price'] = '{{ $order_detail->detail_price }}';
            }

            new_item['qty'] = '{{ $order_detail->quantity }}';
            new_item['is_editable_price'] = '{{ $order_detail->product->is_editable_price }}';
            new_item['has_service_charge']= '{{ $order_detail->product->has_service_charge }}';
            new_item['quantity_status'] = '{{ $order_detail->product->quantity_status }}';
            new_item['start_date'] ='{{ $order_detail->start_date }}';
            new_item['end_date'] ='{{ $order_detail->end_date }}';
            new_item['start_time'] ='{{ $order_detail->start_time }}';
            new_item['end_time'] = '{{ $order_detail->end_time }}';
            new_item['length_of_term'] = '{{ $order_detail->length_of_term }}';
            new_item['remarks'] = '{{ $order_detail->remarks }}';

            selected_product.push(new_item);
        @endforeach
        
        setDetailTransaction();
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

        var error_list = "";
        var location_id = document.getElementById("location_id").value;
        var customer_id = document.getElementById("customer_id").value;
        var pay_now = $("input[name=pay_now]:checked").val();

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

        if(selected_product.length == 0){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to select product</b> </span>'+
                            '</div>';
        }

        if(pay_now == 'Y'){
            if(total_payment != total_transaction){
                error_list +=   '<div class="alert alert-warning">'+
                                    '<span><b> Sorry !!! Your payment amount and total price are not same</b> </span>'+
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

    function addProduct(id = null, quantity = 1){
        var error_list = "";
        var new_item = new Array;
        var product_id = document.getElementById("product_list").value;

        var link = "{{ url('product/get_by_id') }}";
        var availability = true;

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
                $.get(url, function (data){
                    name = data['name'];
                    price_type = data['price_type'];
                    product_price = data['price'];

                    new_item['id'] = data['id'];
                    new_item['name'] = data['name'];
                    new_item['price_type'] = data['price_type'];
                    new_item['price'] = data['price'];
                    new_item['qty'] = 1;
                    new_item['is_editable_price'] = data['is_editable_price'];
                    new_item['has_service_charge']= data['has_service_charge'];
                    new_item['quantity_status'] = data['quantity_status'];
                    new_item['start_date'] = "{{ date('Y-m-d') }}";
                    new_item['end_date'] = "{{ date('Y-m-d') }}";
                    new_item['start_time'] = "";
                    new_item['end_time'] = "";
                    new_item['length_of_term'] = "1";
                    new_item['remarks'] = "";

                    selected_product.push(new_item);
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
        var quantity = 1;
        var detail_transaction = '';

        for(var i=0; i < selected_product.length; i++){
            var product_id = selected_product[i].id;
            var product_name = selected_product[i].name;
            var price_type = selected_product[i].price_type;
            var product_price = selected_product[i].price;
            var quantity = selected_product[i].qty;
            var start_date = selected_product[i].start_date;
            var end_date = selected_product[i].end_date;
            var start_time = selected_product[i].start_time;
            var end_time = selected_product[i].end_time;
            var length_of_term = selected_product[i].length_of_term;
            var has_service_charge = selected_product[i]['has_service_charge'];
            var quantity_status = selected_product[i]['quantity_status'];
            var is_editable_price = selected_product[i]['is_editable_price'];
             var remarks = selected_product[i].remarks;


            detail_transaction += '<tr>';
                detail_transaction += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeProduct('+i+')"><i class="material-icons">remove</i></a></td>';
                    detail_transaction += '<td>';
                    detail_transaction += '<div class="row">';
                        detail_transaction += '<div class="col-sm-12">';
                            detail_transaction += '<div class="form-group text-center">';
                                detail_transaction += '<input type="hidden" name="other_product_id[]" value="'+product_id+'">'+product_name;
                            detail_transaction += '</div>';
                        detail_transaction += '</div>';
                    detail_transaction += '</div>';
                detail_transaction += '</td>';
                detail_transaction += '<td>';

                    detail_transaction += '<div id="start_to_end_'+i+'">';
                        detail_transaction += '<div class="row" id="datepicker_'+i+'">';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="text" name="ac_price_type[]" id="price_type_'+i+'" value="'+price_type+'" class="form-control text-center" readonly>';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="date" name="ac_start_date[]" id="start_date_'+i+'" value="'+start_date+'" class="form-control text-center" placeholder="Start Date" onchange="onPeriodeChanged('+"'start_date'"+', '+"'_"+i+"'"+', '+"'Y-m-d'"+');countPrice();">';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="number" name="ac_length_of_term[]" id="length_of_term_'+i+'" min="1" value="'+length_of_term+'" class="form-control text-center"  placeholder="Length Of Term" onchange="onPeriodeChanged('+"'length_of_term'"+', '+"'_"+i+"'"+', '+"'Y-m-d'"+');countPrice();">';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="date" name="ac_end_date[]" id="end_date_'+i+'" value="'+end_date+'" class="form-control text-center" placeholder="End Date" onchange="onPeriodeChanged('+"'end_date'"+', '+"'_"+i+"'"+', '+"'Y-m-d'"+');countPrice();">';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                        detail_transaction += '</div>';
                        detail_transaction += '<div class="row" id="timepicker_'+i+'">';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="time" name="ac_start_time[]" id="start_time_'+i+'" value="'+start_time+'" class="form-control timepicker text-center" placeholder="Start Time" onchange="onPeriodeChanged('+"'start_time'"+', '+"'_"+i+"'"+');countPrice();">';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="time" name="ac_end_time[]" id="end_time_'+i+'" value="'+end_time+'" class="form-control timepicker text-center" placeholder="End Time" onchange="onPeriodeChanged('+"'end_time'"+', '+"'_"+i+"'"+');countPrice();">';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                            detail_transaction += '<div class="col-sm-12">';
                                detail_transaction += '<div class="form-group">';
                                    detail_transaction += '<input type="text" name="ac_remarks[]" id="remarks_'+i+'" value="'+remarks+'" class="form-control text-center" placeholder="Remarks">';
                                detail_transaction += '</div>';
                            detail_transaction += '</div>';
                        detail_transaction += '</div>';
                    detail_transaction += '</div>';
                detail_transaction += '</td>';

                if(selected_product[i].is_editable_price == 'Y'){
                    detail_transaction += '<td>';
                    detail_transaction += '<input type="text" class="form-control text-center" id="format_ac_detail_price_'+product_id+'" value="'+numberWithCommas(product_price)+'" onchange="changeToCurrencyFormat('+"'format_ac_detail_price_"+product_id+"'"+', '+"'ac_detail_price_"+product_id+"'"+');countPrice();">';
                    detail_transaction += '<input type="hidden" name="ac_detail_price[]" id="ac_detail_price_'+product_id+'" value="'+product_price+'">';
                    detail_transaction += '<input type="hidden" name="has_service_charge[]" id="has_service_charge'+product_id+'" value="'+has_service_charge+'">';
                    detail_transaction += '</td>';
                }else{
                    detail_transaction += '<td>'
                    detail_transaction += '<input type="text" class="form-control text-center" id="format_ac_detail_price_'+product_id+'" value="'+numberWithCommas(product_price)+'" readonly>';
                    detail_transaction += '<input type="hidden" name="ac_detail_price[]" id="ac_detail_price_'+product_id+'" value="'+product_price+'">';
                    detail_transaction += '</td>';
                }

                if(selected_product[i].quantity_status == 'Y'){
                    detail_transaction += '<td class="text-center"><input type="number" class="form-control text-center" name="ac_quantity[]" id="ac_quantity_'+product_id+'" min="1" value="'+quantity+'" onchange="countPrice()"></td>';
                }else{
                    detail_transaction += '<td class="text-center"><input type="number" class="form-control text-center" name="ac_quantity[]" id="ac_quantity_'+product_id+'" min="1" value="'+quantity+'" readonly></td>';
                }

                detail_transaction += '<td colspan="2" id="ac_sub_total_'+product_id+'" class="text-right">'+numberWithCommas(product_price)+'</td>';

            detail_transaction += '</tr>';
        }

        document.getElementById("detail_transaction").innerHTML = detail_transaction;

        countPrice();
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

        });
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

    function countPrice(){
        var detail_price = 0;
        var quantity = 0;
        var discount_price = parseFloat(document.getElementById("discount_price").value);
        var sub_total = 0;
        var grand_total = 0;
        var total_tax_price= 0;
        var total_service_charge = 0;
        var tax_status = $("input[name=tax_status]:checked").val();

        for(var i=0; i < selected_product.length; i++){
            var service_charge_status = selected_product[i].has_service_charge;
            var ac_length_of_term = parseFloat(document.getElementById("length_of_term_"+i).value);
            var ac_start_date = document.getElementById("start_date_"+i).value;
            var ac_end_date = document.getElementById("end_date_"+i).value;
            var ac_start_time = document.getElementById("start_time_"+i).value;
            var ac_end_time = document.getElementById("end_time_"+i).value;

            detail_price = parseFloat($("#ac_detail_price_"+selected_product[i].id).val());
            quantity = parseInt($("#ac_quantity_"+selected_product[i].id).val());

            if(document.getElementById("ac_sub_total_"+selected_product[i].id) != null){
                document.getElementById("ac_sub_total_"+selected_product[i].id).innerHTML = numberWithCommas(detail_price * quantity * ac_length_of_term);
            }

            sub_total = sub_total + (detail_price * quantity * ac_length_of_term);
            onPeriodeChanged('start_date', '_'+i, 'Y-m-d');

            // Start : For Service Charge
            if(service_charge_status == null){
                detail_service_charge = 0;
            }else{
                if(service_charge_status == "Y"){
                    detail_service_charge = (detail_price * quantity * ac_length_of_term) * parseFloat(service_charge);
                }else{
                    detail_service_charge = 0;
                }
            }
            total_service_charge = parseFloat(total_service_charge) + parseFloat(detail_service_charge);
            // End : For Service Charge

            selected_product[i].price = detail_price;
            selected_product[i].qty = quantity;
            selected_product[i].start_date = ac_start_date;
            selected_product[i].end_date = ac_end_date;
            selected_product[i].start_time = ac_start_time;
            selected_product[i].end_time = ac_end_time;
            selected_product[i].length_of_term = ac_length_of_term;
        }

        var total_price = sub_total - discount_price;

        if(tax_status == null){
            total_tax_price = 0;
        }else{
            if(tax_status == 'no_tax'){
                total_tax_price= 0;
            }else if(tax_status == 'exclude'){
                total_tax_price= parseFloat(parseFloat(total_price) + parseFloat(total_service_charge)) * parseFloat(tax_percentage);
            }else if(tax_status == 'include'){
                var temp_1 = total_price;
                total_price = parseFloat(total_price) / (1 + parseFloat(tax_percentage));
                total_tax_price = parseFloat(temp_1) - parseFloat(total_price);
                
                if(service_charge_status == "Y"){
                    var temp_2 = total_price;
                    total_price = parseFloat(total_price) / (1 + parseFloat(service_charge));
                    total_service_charge = parseFloat(temp_2) - parseFloat(total_price);
                }
            }else{
                total_tax_price= 0;
            }
        }

        total_price = Math.round(total_price);
        total_service_charge = Math.round(total_service_charge);
        total_tax_price= Math.round(total_tax_price);

        var grand_total_tax= parseFloat(total_tax_price);
        var view_total_service_charge = parseFloat(total_service_charge);

        grand_total = parseFloat(total_price) + parseFloat(total_service_charge) + parseFloat(total_tax_price);
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

        total_transaction = parseFloat(grand_total);

        document.getElementById("total_price").value = total_price;
        document.getElementById("total_service_charge").value = total_service_charge;
        document.getElementById("total_tax_price").value = total_tax_price;
        document.getElementById("round_price").value = round_price;
        document.getElementById("view_total_service_charge").innerHTML = numberWithCommas(parseInt(view_total_service_charge));
        document.getElementById("view_total_tax_price").innerHTML = numberWithCommas(parseInt(grand_total_tax));
        document.getElementById("view_sub_total").innerHTML = numberWithCommas(sub_total);
        document.getElementById("view_total_price").innerHTML = numberWithCommas(grand_total);
        document.getElementById("view_round_price").innerHTML = numberWithCommas(parseInt(round_price));
        // document.getElementById("view_grand_total").innerHTML = numberWithCommas(grand_total);
    }

    function payNow(pay_now){
        if(pay_now == 'Y'){
            $("#payment").show();
        }else{
            $("#payment").hide();
        }
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
        loadPaymentDetail();
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

        loadPaymentDetail();
        $('#nonCashModel').modal('toggle');
    }

    function updateCash(){
        var detail_cash = $("#detail_cash").val();
        cash = detail_cash;
        loadPaymentDetail();
    }

    function updateNonCash(array_index){
        for(var i=0; i < non_cash.length; i++){
            if(i == array_index){
                var detail_amount = $("#detail_non_cash_"+i).val();
                non_cash[i].amount = detail_amount;
                break;
            }
        }
        loadPaymentDetail();
    }

    function clearNonCash(array_index){
        non_cash.splice(array_index);
        loadPaymentDetail();
    }

    function loadPaymentDetail(){
        detail_payment = '';
        total_payment = 0;

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

            detail_payment += '<td class="text-center"><a class="btn btn-warning" id="cash_button"><i class="fa fa-times"></i> Clear Cash</a></td>';

            detail_payment += '</tr>';

            total_payment = parseInt(total_payment) + parseInt(cash);
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
            detail_payment += '<input type="hidden" name="payment_card_holder_name[]" value="'+non_cash[i].account_number+'">';
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

        document.getElementById("detail_payment").innerHTML = detail_payment;
        document.getElementById("format_total_payment").innerHTML = numberWithCommas(total_payment);
    }

    $(function() {
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

        $("#cash_button").change(function() {
            cash = 0;
            loadPaymentDetail();
        });
    });
</script>
@endsection
