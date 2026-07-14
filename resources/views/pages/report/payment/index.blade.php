@extends('layouts.app')
@section('title')
Rakomsis Payment Report
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Location</b></label><br>
                            <select class="selectpicker form-control" name="location_id" id="location_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Select Your Option</option>
                                @foreach($locations as $location)
                                    @php
                                        $selected = '';
                                        if($location_id == $location->id){
                                            $selected = 'selected';
                                        }
                                    @endphp
                                     <option value="{{ $location->id }}" {{ $selected }}> {{$location->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Start Month</b></label><br>
                            <select name="start_month" id="start_month" class="form-control selectpicker" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled>--- Select Month ---</option>
                                @for($m = 1;$m <= 12; $m++){
                                    @php
                                        $selected = '';
                                        $month_name =  date("F", mktime(0, 0, 0, $m, 1));
                                        if($start_month == $m){
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value='{{ $m }}' {{ $selected }}>{{ $month_name }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Start Year</b></label><br>
                            <input type="number" name="start_year" id="start_year" class="form-control" value="{{$start_year}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>End Month</b></label><br>
                            <select name="end_month" id="end_month" class="form-control selectpicker" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled>--- Select Month ---</option>
                                @for($m = 1;$m <= 12; $m++){
                                    @php
                                        $selected = '';
                                        $month_name =  date("F", mktime(0, 0, 0, $m, 1));
                                        if($end_month == $m){
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value='{{ $m }}' {{ $selected }}>{{ $month_name }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>End Year</b></label><br>
                            <input type="number" name="end_year" id="end_year" class="form-control" value="{{$end_year}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="toolbar">
                            <button class="btn btn-success" id="filter">Filter </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-table-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Payment Report</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="payment-report" class="table table-striped table-no-bordered table-hover " cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <th class="table-info">Customer</th>
                            @for($i=0; $i<=$total_month; $i++)
                            <th>{{$array_of_str_month[$i]}} {{$array_of_str_year[$i]}}</th>
                            @php
                                $grand_total_payment[$i] = 0;
                            @endphp
                            @endfor
                        </thead>
                        <tbody>
                        @foreach($payments as $no=> $payment)
                            @php
                                $total_of_summary = 0;
                            @endphp
                            <tr>
                                <td class="table-info">{{ $payment->customer['name'] }}</td>
                                @for($i=0; $i<=$total_month; $i++)
                                <td class="text-right">
                                    @if($payment->payment_date >= $array_of_first_month[$i] && $payment->payment_date <= $array_of_end_month[$i])
                                        @foreach($payment->payment_detail as $detail)
                                            @php
                                                $total_of_summary = $total_of_summary + $detail->amount;
                                                $grand_total_payment[$i] = $grand_total_payment[$i] + $total_of_summary;
                                            @endphp
                                        @endforeach
                                        {{number_format($total_of_summary,0,',','.')}}
                                    @endif
                                </td>
                                @endfor
                            </tr>
                        @endforeach
                            <tr>
                                <td class="table-warning">Total</td>
                                @for($i=0; $i<=$total_month; $i++)
                                <td class="table-success text-right">{{number_format($grand_total_payment[$i],0,',','.')}}</td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $("#filter").on('click', function(){
        var locations_id = document.getElementById("location_id").value;
        var start_month = document.getElementById("start_month").value;
        var start_year = document.getElementById("start_year").value;
        var end_month = document.getElementById("end_month").value;
        var end_year = document.getElementById("end_year").value;
        window.location.href = '{{ url('payment_report')}}'+"?locations_id="+locations_id+"&start_month="+start_month+"&start_year="+start_year+"&end_month="+end_month+"&end_year="+end_year;
    });
    $(document).ready(function() {
        $('#payment-report').DataTable({
            "ordering" : false,
            "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]]
        });
    } );
    function exportExcel(){
        var locations_id = document.getElementById("location_id").value;
        var start_month = document.getElementById("start_month").value;
        var start_year = document.getElementById("start_year").value;
        var end_month = document.getElementById("end_month").value;
        var end_year = document.getElementById("end_year").value;

        var url = '{{ url('exportPayment') }}'+"?locations_id="+locations_id+"&start_month="+start_month+"&start_year="+start_year+"&end_month="+end_month+"&end_year="+end_year;

        var link =url;
        window.location =link;
        return false;
    }
</script>
@endsection
