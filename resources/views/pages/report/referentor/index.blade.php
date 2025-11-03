
@extends('layouts.app')
@section('title')
Rakomsis Referral Report
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">search</i>
                </div>
                <h4 class="card-title">Filter</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><b>Location</b></label>
                            <select name="location_id" id="location_id" class="form-control selectpicker" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                @foreach($locations as $no => $location)
                                    <option value="{{ $location->id }}" @if(!empty($location_id)) @if($location_id == $location->id) selected="selected" @endif @endif> {{$location->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-block btn-success text-white" onclick="exportExcel()">Export to Excel</a>
                    </div>
                    <div class="col-md-12">
                        <button onclick="filter()" class="btn btn-block btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">view_list</i>
                </div>
                <h4 class="card-title">Referral Achievement</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables">
                    <table class="table table-bordered text-center" id="referral_achievment_table">
                        <thead>
                            <tr>
                            @foreach($referrals as $detail)
                                <th>{{ $detail->name }}</th>
                            @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $all_referral_achievement = 0;
                                @endphp
                                @foreach($referrals as $no => $detail)
                                    @php
                                        $all_referral_achievement = $all_referral_achievement + $referral_achievement[$no];
                                    @endphp
                                    <td>{{ number_format($referral_achievement[$no], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td colspan="{{ sizeof($referrals) }}">{{ number_format($all_referral_achievement, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">view_list</i>
                </div>
                <h4 class="card-title">Agent Achievement</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables">
                    <table class="table table-bordered text-center" id="agent_achievment_table">
                        <thead class="thead-dark">
                            <tr>
                                <th>Name</th>
                                <th>Achievement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $all_agent_achievement = 0;
                            @endphp
                            @foreach($agents as $no => $detail)
                                @php
                                    $all_agent_achievement = $all_agent_achievement + $agent_achievement[$no];
                                @endphp
                                <tr>
                                    <td>{{ $detail->name }}</td>
                                    <td>{{ number_format($all_agent_achievement[$no], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-success">
                                <td>Total</td>
                                <td>{{ number_format($all_agent_achievement, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(function() {
	    $('#agent_achievment_table').DataTable();
	});

    function filter(){
        var location_id = document.getElementById("location_id").value;
        var month = document.getElementById("month").value;
        var year = document.getElementById("year").value;
        window.location.href = '{{ url('referentor_report')}}'+"?location_id="+location_id+"&month="+month+"&year="+year;
    }

    function exportExcel(){
        var location_id = document.getElementById("location_id").value;
        var month = document.getElementById("month").value;
        var year = document.getElementById("year").value;
        var url = '{{ url('exportReferentor') }}'+"?location_id="+location_id+"&month="+month+"&year="+year;

        var link =url;
        window.location =link;
        return false;
    }
</script>
@endsection
