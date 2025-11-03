@extends('layouts.app')
@section('title')
Rakomsis Deposit - {{ $deposit->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Deposit</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Deposit
                    </a>
                    <a onclick="print('{{ url($print_url) }}')" class="btn btn-primary pull-right text-white">
                        <i class="fa fa-print"></i> Print
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h4 class="card-title">Customer Detail</h4>
                            </div>
                            <div class="card-body">
                                <table >
                                    <tr>
                                        <td><h5><b>Bill To</b></h5></td>
                                    </tr>
                                    <tr>
                                        <td>{{$deposit->customer->name}}</td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h4 class="card-title">Security Deposit</h4>
                            </div>
                            <div class="card-body">
                                    <table>
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
                    </div>
                </div>
                <br>
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
