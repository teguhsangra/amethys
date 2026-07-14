@extends('layouts.print')
@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-6 text-left">
            <h4>Deposit Details</h4>
        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid pull-right" src="{{asset('company-logo.png')}}" width="200">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 text-left">
            <table>
                <tr>
                    <td><h5><b>Bill To</b></h5></td>
                </tr>
                <tr>
                    <td>{{$deposit->customer->name}}</td>
                </tr>
            </table>
            <br>
        </div>
        <div class="col-md-6 text-right">
            <br>
            <h3  class="text-right">Security Deposit</h3>
            <br>
            <table style="float:right;">
                <tr>
                    <td class="text-left">Date</td>
                    <td>:</td>
                    <td class="text-left">
                        {{date("j F Y",strtotime($deposit->due_date))}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <table class="table table-border">
                    <thead>
                        <th width="30%">Description</th>
                        <th width="19%">Period</th>
                        <th width="3%">Qty</th>
                        <th width="19%" class="text-right">Detail Price</th>
                        <th width="19%" class="text-right">Total</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Deposit <span v-show="form.bookings_id">{{$deposit->code}}</span></td>
                            <td>{{date("j F Y",strtotime($deposit->created_at))}}</td>
                            <td>1</td>
                            <td class="text-right">IDR {{number_format($deposit->total_deposit,0,',','.')}} </td>
                            <td class="text-right">IDR  {{number_format($deposit->total_deposit,0,',','.')}} </td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td class="text-right"><b>Total</b></td>
                            <td class="text-right"><b>IDR {{number_format($deposit->total_deposit,0,',','.')}}</b></td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right">Stamp Duty (Materai)</td>
                            <td class="text-right">IDR {{number_format($deposit->stamp_duty,0,',','.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                            <td class="text-right"><b>Grand Total</b></td>
                            <td class="text-right"><b>IDR {{number_format($deposit->total_deposit+$deposit->stamp_duty,0,',','.')}}</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
