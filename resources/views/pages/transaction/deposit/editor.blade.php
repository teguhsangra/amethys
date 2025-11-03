@extends('layouts.app')

@section('title')
Rakomsis Deposit - Editor
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
                    Deposit Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Deposit
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="status_name" id="status_name">
                    <input type="hidden" name="category" value="security_deposit">
                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')) || !empty($deposit))
                                    <input type="text" class="form-control" value="{{$deposit->location->name}}" readonly>
                                    <input type="hidden" name="location_id" id="location_id" value="{{$deposit->location_id}}">
                                @else
                                    <select class="selectpicker form-control" name="location_id" id="location_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            @php
                                                $selected = '';
                                                if(!empty($deposit)){
                                                    if($deposit->location_id == $detail->id){
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
                                    <input type="text" class="form-control" value="{{$deposit->customer->name}}" readonly>
                                    <input type="hidden" name="customer_id" id="customer_id" value="{{$deposit->customer_id}}">
                                @else
                                <select class="selectpicker form-control" name="customer_id" id="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($customers as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($deposit)){
                                                if($deposit->customer_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}} id="{{ $detail->total_security_deposit }}" >{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="type_selection">
                        <label class="col-sm-2 col-form-label">Category</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="category" value="security_deposit" @if(!empty($deposit)) @if($deposit->category == 'security_deposit') checked @endif @else checked @endif> Security Deposit
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="category" value="down_payment" @if(!empty($deposit)) @if($deposit->category == 'down_payment') checked @endif @endif> Down Payment
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="category" value="booking_fee" @if(!empty($deposit)) @if($deposit->category == 'booking_fee') checked @endif @endif> Booking Fee
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="type_selection">
                        <label class="col-sm-2 col-form-label">Type</label>
                        <div class="col-sm-10 checkbox-radios">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="type_security_deposit" onchange="selectType('IN')" value="IN" @if(!empty($deposit)) @if($deposit->type_security_deposit == 'IN') checked @endif @else checked @endif> In
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="radio" name="type_security_deposit" onchange="selectType('OUT')" value="OUT" @if(!empty($deposit)) @if($deposit->type_security_deposit == 'OUT') checked @endif @endif> Out
                                    <span class="circle">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Total Deposit</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('total_deposit') ? ' has-error' : '' }}">
                                <input type="text" id="format_total_deposit" name="format_total_deposit" class="form-control" onchange="changeToCurrencyFormat('format_total_deposit','total_deposit')" @if(!empty($deposit)) value="{{ number_format($deposit->total_deposit, 0,',','.') }}" @else value="{{ number_format(old('total_deposit'), 0,',','.') }}" @endif>
                                <input type="hidden" id="total_deposit" name="total_deposit" @if(!empty($deposit)) value="{{ $deposit->total_deposit }}" @else value="{{ old('total_deposit') }}" @endif>
                                <label class="error">{{ $errors->first('total_deposit') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
                                <input type="text" name="due_date" class="form-control datepicker" @if(!empty($deposit)) value="{{ date('m/d/Y', strtotime($deposit->due_date)) }}" @endif>
                                <label class="error">{{ $errors->first('due_date') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($deposit)){{ $deposit->remarks }}@endif</textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
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
    var total_security_deposit = 0;

    $(function() {
        $("#customer_id").change(function() {
            var selected_customer_deposit = $(this).children(":selected").attr("id");
            total_security_deposit = selected_customer_deposit;
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
        var total_deposit = document.getElementById("total_deposit").value;
        var type_security_deposit = $("input[name=type_security_deposit]:checked").val();

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

        if(total_deposit == 0){
            error_list +=   '<div class="alert alert-warning">'+
                                '<span><b> Sorry !!! You have to input total deposit</b> </span>'+
                            '</div>';
        }

        if(type_security_deposit == 'OUT'){
            if(total_deposit > total_security_deposit){
                error_list +=   '<div class="alert alert-warning">'+
                                    "<span><b> Sorry !!! Selected customer's total deposut is less then total deposit you want to withdraw</b> </span>"+
                                '</div>';
            }

            if(total_deposit != total_payment){
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

    function selectType(type_value){
        if(type_value == 'OUT'){
            $("#payment").show();
            $("#type_selection").hide();
        }else{
            $("#payment").hide();
            $("#type_selection").show();
        }
    }

    function showReference(reference_value){
        if(reference_value == 'not_use'){
            $("#inquiry").hide();
            $("#booking").hide();
        }else if(reference_value == 'inquiry'){
            $("#inquiry").show();
            $("#booking").hide();
        }else if(reference_value == 'booking'){
            $("#inquiry").hide();
            $("#booking").show();
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
