@extends('layouts.app')
@section('title')
Rakomsis Product Occupancy Report
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
                            <label class="col-sm-2"><b>Enda Year</b></label><br>
                            <input type="number" name="end_year" id="end_year" class="form-control" value="{{$end_year}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="toolbar">
                            <button class="btn btn-success" id="filter" onclick="Filter()">Filter </button>
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
                <h4 class="card-title">Product Occupancy Report {{$selected_location->name}}</h4>
            </div>
            <div class="card-body">
                <b>Product Occupancy {{date("j F Y",strtotime($first_of_start_date))}} To {{date("j F Y",strtotime($end_of_end_date))}}</b>
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="resume_report" class="table table-striped table-no-bordered table-hover " cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <th class="warning">Metrix</th>
                            @for($i=0; $i<=$total_month; $i++)
                                <th>{{$array_of_str_month[$i]}} <br> {{$array_of_str_year[$i]}}</th>
                            @endfor
                        </thead>
                        <tbody>
                            <tr>
                                <td class="warning-label">Total Revenue</td>
                                @for($i=0; $i<=$total_month; $i++)
                                    <td>{{number_format($array_total_revenue_of_month[$i],0,',','.')}}</td>
                                @endfor
                            </tr>
                            <tr>
                                <td class="warning-label">Total VO</td>
                                @for($i=0; $i<=$total_month; $i++)
                                    <td>{{number_format($array_total_vo_of_month[$i],0,',','.')}}</td>
                                @endfor
                            </tr>
                            <tr>
                                <td class="warning-label">Average Price</td>
                                @for($i=0; $i<=$total_month; $i++)
                                    <td>@if($array_total_vo_of_month[$i] > 0){{number_format($array_total_revenue_of_month[$i]/$array_total_vo_of_month[$i],0,',','.')}}@endif</td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
                <hr>
                <b>Detail Reports {{$selected_location->name}}</b>
                <div class="material-datatables table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="detail-report">
                        <thead>
                            <th class="table-info">Service Name</th>
                            <th class="table-info">Quoted Price</th>
                            <th class="table-info">Paid Deposit</th>
                            <th class="table-info">Customer</th>
                            <th class="table-info">Commencement Date</th>
                            <th class="table-info">End Date</th>
                            <th class="table-info">Term</th>
                            <th class="table-info">Sales</th>
                            @for($i=0; $i<=$total_month; $i++)
                                <th>{{$array_of_str_month[$i]}} <br> {{$array_of_str_year[$i]}}</th>
                            @endfor
                            <th class="success">Total</th>
                        </thead>
                        <tbody>
                            @php
                                $grand_total = 0;
                            @endphp
                            @foreach($bookings as $no=> $booking)
                                @php
                                    $total_in_row = 0;
                                @endphp 
                                <tr>
                                    <td class="table-info">{{ $booking->product->name }}</td>
                                    <td class="table-info">{{ number_format($booking->product->price,0,',','.')}}</td>
                                    <td class="table-info">{{ number_format($booking->security_deposit,0,',','.')}}</td>
                                    <td class="table-info">{{ $booking->customer->name }}</td>
                                    <td class="table-info">{{ date("j F Y",strtotime($booking->start_date))}}</td>
                                    <td class="table-info">{{ date("j F Y",strtotime($booking->end_date))}}</td>
                                    <td class="table-info">{{ $booking->length_of_term}}</td>
                                    <td class="table-info">{{ $booking->employee->name}}</td>
                                    @for($i=0; $i<=$total_month; $i++)
                                        @php
                                            $total_in_row = $total_in_row + $array_of_booking_price[$no][$i];
                                            $grand_total = $grand_total + $array_of_booking_price[$no][$i];
                                        @endphp
                                        <td>{{ number_format($array_of_booking_price[$no][$i], 0, ',', '.') }}</td>
                                    @endfor
                                    <td class="success text-right">{{ number_format($total_in_row, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr class="success">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                @for($i=0; $i<=$total_month; $i++)
                                    <td class="table-bordered">
                                        {{number_format($array_total_revenue_of_month[$i],0,',','.')}}
                                    </td>
                                @endfor
                                <td>{{number_format($grand_total,0,',','.')}}</td>
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
    $(document).ready(function() {
        $('#resume_report').DataTable({
            scrollY: "100%",
            scrollX: true,
            "ordering" : false,
            resposive: true,
            "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]]
        });
        $('#detail-report').DataTable({
            scrollY: "100%",
            scrollX: true,
            "ordering" : false,
            "lengthMenu": [[-1, 10, 25, 50], ["All", 10, 25, 50]]
        });
    } );
    function Filter(){
        var locations_id = document.getElementById("location_id").value;
        var start_month = document.getElementById("start_month").value;
        var start_year = document.getElementById("start_year").value;
        var end_month = document.getElementById("end_month").value;
        var end_year = document.getElementById("end_year").value;
        window.location.href = '{{ url('product_occupancy_report')}}'+"?locations_id="+locations_id+"&start_month="+start_month+"&start_year="+start_year+"&end_month="+end_month+"&end_year="+end_year;
    }
    function exportExcel(){
        var locations_id = document.getElementById("location_id").value;
        var start_month = document.getElementById("start_month").value;
        var start_year = document.getElementById("start_year").value;
        var end_month = document.getElementById("end_month").value;
        var end_year = document.getElementById("end_year").value;
        var url = '{{ url('exportProductOccupancy') }}'+"?locations_id="+locations_id+"&start_month="+start_month+"&start_year="+start_year+"&end_month="+end_month+"&end_year="+end_year;

        var link =url;
        window.location =link;
        return false;
    }
</script>
@endsection
