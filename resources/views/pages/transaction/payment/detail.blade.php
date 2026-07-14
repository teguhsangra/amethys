@extends('layouts.app')
@section('title')
Rakomsis Payment - {{ $payment->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Payment</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Payment
                    </a>
                    <a onclick="print('{{ url($print_url) }}')" class="btn btn-primary pull-right text-white">
                        <i class="fa fa-print"></i> Print
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-6 text-left">
                        <div class="card card-info">
                            <div class="card-header">
                                <h4 class="card-title">Payment Summary</h4>
                            </div>
                            <div class="card-body">
                                <table>
                                    <tr>
                                        <td>Payment Date</td>
                                        <td>:</td>
                                        <td>{{date("j F Y",strtotime($payment->payment_date))}}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment Paid</td>
                                        <td>:</td>
                                        <td>{{$payment->code}}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment from</td>
                                        <td>:</td>
                                        <td>{{$payment->customer->name}}</td>
                                    </tr>
                                </table>
                                <br>
                                <strong>Total Amount Received</strong> <font style="text-align: justify; margin-left:6.5%">:&emsp;IDR {{number_format($payment->total_payment,0,',','.')}}</font>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="card card-danger">
                            <div class="card-header">
                                <h4 class="card-title">Payment Receipt</h4>
                            </div>
                            <div class="card-body">
                                <table style="float:right;">
                                    <tr>
                                        <td>Date</td>
                                        <td>:</td>
                                        <td>{{date("j F Y",strtotime($payment->payment_date))}}</td>
                                    </tr>
                                    <tr>
                                        <td>No.</td>
                                        <td>:</td>
                                        <td>{{$payment->code}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 text-left">
                        <p>Detail Payment</p>
                    </div>
                    <br><br>
                    <div class="col-md-12">
                        <div class="card  card-primary">
                            <div class="card-header">
                                Payment Summary
                            </div>
                            <div class="card-body">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td class="text-center"><strong>Invoice/Deposit Date</strong></td>
                                            <td class="text-center"><strong>Invoice/Deposit No.</strong></td>
                                            <td class="text-center"><strong>Payment Date</strong></td>
                                            <td class="text-center"><strong>Payment Type</strong></td>
                                            <td class="text-center"><strong>Amount</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payment->payment_allocation as $payment_details)
                                        <tr>
                                            <td class="text-center">
                                                @if($payment_details->invoice_id != null)
                                                {{date("j F Y",strtotime($payment_details->invoice->created_at))}}
                                                @endif
                                                @if($payment_details->deposit_id != null)
                                                {{date("j F Y",strtotime($payment_details->deposit->created_at))}}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($payment_details->invoice_id != null)
                                                    {{$payment_details->invoice->code}}
                                                @else
                                                    {{$payment_details->deposit->code}}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                {{date("j F Y",strtotime($payment_details->payment->payment_date))}}
                                            </td>
                                            <td class="text-center">
                                                @foreach($payment->payment_detail as $payment_detail)
                                                {{$payment_detail->payment_type}}
                                                @endforeach
                                            </td>


                                            <td class="text-center">IDR
                                                    {{number_format($payment_details->total_need,0,',','.')}}
                                            </td>

                                        </tr>
                                        @endforeach
                                        <tr class="success">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center"><b>Grand Total</b></td>
                                            <td class="text-center"><b>IDR {{number_format($payment->total_payment,0,',','.')}}</b></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->update == 1 && $a_g_and_module->isExec == 1 && $payment->status_id != 3 && $payment->status_id != 4 && $payment->status_id != 5)
                    {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }} 
                    {{ Form::close() }}
                    <a data-toggle="modal" data-target="#continueTransactionModal" class="col-md-12 btn-lg btn btn-success">Complete</a>
                    
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
                @endif
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script>
    function print(link){
        var printWindow = window.open(link);
        var printAndClose = function () {
            if (printWindow.document.readyState == 'complete') {
                clearInterval(sched);
                printWindow.print();
                printWindow.close();
            }
        }
        var sched = setInterval(printAndClose, 800);
    }
</script>
@endsection
