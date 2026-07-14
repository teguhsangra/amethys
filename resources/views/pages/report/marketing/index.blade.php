
@extends('layouts.app')
@section('title')
Rakomsis Marketing Report
@endsection

@section('css')
<style>
    .ct-chart .ct-label.ct-horizontal {
        text-anchor: middle !important;
    }

    .ct-chart .ct-bar {
        stroke-linecap: round;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">trending_up</i>
                </div>
                <h4 class="card-title">Sales Achievement</h4>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Month</b></label>
                            <select name="month" id="month" class="form-control selectpicker" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled>--- Select Month ---</option>
                                @for($m = 1;$m <= 12; $m++){
                                    @php
                                        $selected = '';
                                        $month_name =  date("F", mktime(0, 0, 0, $m, 1));
                                        if($month == $m){
                                            $selected = 'selected';
                                        }
                                    @endphp
                                    <option value='{{ $m }}' {{ $selected }}>{{ $month_name }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Year</b></label>
                            <input type="number" name="year" id="year" class="form-control" value="{{$year}}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="material-datatables table-responsive">
                        <table id="marketing_achievement_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Target VO</th>
                                    <th>Target SO</th>
                                    <th>VO Achievement</th>
                                    <th>SO Achievement</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button id="filterAchievement" class="btn btn-block btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-icon card-header-rose">
                <div class="card-icon">
                    <i class="material-icons">insert_chart</i>
                </div>
                <h4 class="card-title">Sales Target
                    <small>- Chart</small>
                </h4>
                <br>
                <table>
                    <tr>
                        <td style="color: black !important;">Target (in %)</td>
                        <td><div style="background-color:#00bcd4;width:100px;">&nbsp;</div></td>
                    </tr>
                    <tr>
                        <td style="color: black !important;">Achievement (in %)</td>
                        <td><div style="background-color:#f44336;width:100px;">&nbsp;</div></td>
                    </tr>
                </table>
            </div>
            <div class="card-body">
                <div id="sales_target_chart" class="ct-chart"></div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">table_chart</i>
                </div>
                <h4 class="card-title">Marketing KPI</h4>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Employee</b></label>
                            <select name="employee_id" id="employee_id" class="form-control selectpicker" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="">Select All</option>
                                @foreach($employees as $no => $employee)
                                    <option value="{{ $employee->id }}"> {{$employee->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Location</b></label>
                            <select name="location_id" id="location_id" class="form-control selectpicker" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="">Select All</option>
                                @foreach($locations as $no => $location)
                                    <option value="{{ $location->id }}" @if(!empty($location_id)) @if($location_id == $location->id) selected="selected" @endif @endif> {{$location->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><b>Start Date</b></label>
                                    <input type="text" name="start_date" id="start_date" class="form-control datepicker" placeholder="Start Date" value="{{ date('m/01/Y') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label><b>End Date</b></label>
                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker" placeholder="End Date" value="{{ date('m/t/Y') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button id="filterKPI" class="btn btn-primary">Filter</button>
                        &nbsp;
                        <button onclick="exportExcel('acrual_basis')" class="btn btn-success">Export Detail Data</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="material-datatables table-responsive">
                        <table id="marketing_kpi_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Total <br>Inquiry</th>
                                    <th>Total <br>New Virtual Office</th>
                                    <th>Total <br>Re-New Virtual Office</th>
                                    <th>Total <br>New Serviced Office</th>
                                    <th>Total <br>Re-New Serviced Office</th>
                                    <th>Total <br>Terminate Virtual Office</th>
                                    <th>Total <br>Terminate Serviced Office</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var employee_id = document.getElementById("employee_id").value;
    var location_id = document.getElementById("location_id").value;
    var start_date = document.getElementById("start_date").value;
    var end_date = document.getElementById("end_date").value;
    var month = document.getElementById("month").value;
    var year = document.getElementById("year").value;
    var labels = new Array;
    var targets = new Array;
    var achievements = new Array;

    getSalesTargetChart();

    $(function() {
        var marketing_kpi_table = $('#marketing_kpi_table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/kpi/'.$url) }}'+'?location_id='+location_id+'&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date,
	        columns: [
	            { data: 'name', name: 'name' },
	            { data: 'total_inquiry', name: 'total_inquiry' },
	            { data: 'total_new_vo', name: 'total_new_vo' },
	            { data: 'total_renew_vo', name: 'total_renew_vo' },
	            { data: 'total_new_so', name: 'total_new_so' },
	            { data: 'total_renew_so', name: 'total_renew_so' },
	            { data: 'total_terminate_vo', name: 'total_terminate_vo' },
	            { data: 'total_terminate_so', name: 'total_terminate_so' },
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });

        var marketing_achievement_table = $('#marketing_achievement_table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/achievement/'.$url) }}'+'?month='+month+'&year='+year,
	        columns: [
	            { data: 'name', name: 'name' },
	            { data: 'total_target_vo', name: 'total_target_vo' },
	            { data: 'total_target_so', name: 'total_target_so' },
	            { data: 'vo_achievement', name: 'vo_achievement' },
	            { data: 'so_achievement', name: 'so_achievement' },
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });

        $("#filterKPI").click(function() {
            employee_id = document.getElementById("employee_id").value;
            location_id = document.getElementById("location_id").value;
            start_date = document.getElementById("start_date").value;
            end_date = document.getElementById("end_date").value;
            marketing_kpi_table.ajax.url('{{ url('datatables/kpi/'.$url) }}'+'?location_id='+location_id+'&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date).load();
        });

        $("#filterAchievement").click(function() {
            month = document.getElementById("month").value;
            year = document.getElementById("year").value;
            marketing_achievement_table.ajax.url('{{ url('datatables/achievement/'.$url) }}'+'?month='+month+'&year='+year).load();

            getSalesTargetChart();
        });
	});

    function exportExcel(type){
        employee_id = document.getElementById("employee_id").value;
        location_id = document.getElementById("location_id").value;
        start_date = document.getElementById("start_date").value;
        end_date = document.getElementById("end_date").value;

        var url = "{{ url('export/marketing_report') }}"+'?location_id='+location_id+'&employee_id='+employee_id+'&start_date='+start_date+'&end_date='+end_date;

        window.location =url;
        return false;
    }

    function getSalesTargetChart(){
        var url = "{{ url('chart/kpi/marketing_report') }}"+'?location_id='+location_id+'&month='+month+'&year='+year;
        $.get(url, function (data){
            sales_target_chart_data = data;
            labels = data.labels;
            targets = data.targets;
            achievements = data.achievements;
            drawChart();
        });
    }

    function drawChart(){
        var dataMultipleBarsChart = {
            labels: labels,
            series: [targets, achievements]
        };

        var optionsMultipleBarsChart = {
            seriesBarDistance: 10,
            axisX: {
                showGrid: true,
                offset: 60
            },
            axisY: {
                offset: 80,
                labelInterpolationFnc: function(value) {
                    return value + ' %'
                },
                scaleMinSpace: 20,
                ticks: 30
            },
            height: '300px'
        };

        var responsiveOptionsMultipleBarsChart = [
            ['screen and (max-width: 640px)', {
                    seriesBarDistance: 5,
                    axisX: {
                    labelInterpolationFnc: function(value) {
                        return value[0];
                    }
                }
            }]
        ];

        var sales_target_chart = Chartist.Bar('#sales_target_chart', dataMultipleBarsChart, optionsMultipleBarsChart, responsiveOptionsMultipleBarsChart);

        //start animation for the Emails Subscription Chart
        md.startAnimationForBarChart(sales_target_chart);
    }
</script>
@endsection
