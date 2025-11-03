@extends('layouts.print')
@section('content')
<div class="container">
<br>
<div class="row">
    <div class="col-md-6 text-left">
        <h4>{{ $payment->code }}</h4>
    </div>
    <div class="col-md-6 text-right">
        <img class="img-fluid pull-right" src="{{asset('company-logo.png')}}" width="200">
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-6 text-left">
        <h3 class="panel-title"><strong>Payment Summary</strong></h3>
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
    <div class="col-md-6 text-right">
        <h3>Payment Receipt</h3>
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
<br>
<div class="row">
    <div class="col-md-6 text-left">
        <p>Detail Payment</p>
    </div>
    <br><br>
    <div class="col-md-12">
        <div class="card card-primary">
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
@endsection
