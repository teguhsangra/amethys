@extends('layouts.app')
@section('title')
Rakomsis Complimentary Usage Report
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">table_chart</i>
                </div>
                <h4 class="card-title">Complimentary Usage Report</h4>
                <br>
                <div class="row">
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
                        <table id="compliment_table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Complimentary</th>
                                    <th>Total Use Complimentary</th>
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
$(function() {
        var marketing_kpi_table = $('#compliment_table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}'+'?location_id='+location_id+'&start_date='+start_date+'&end_date='+end_date,
	        columns: [
	            { data: 'name', name: 'name' },
	            { data: 'total_use_complimentary', name: 'total_use_complimentary' },
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });


        $("#filterKPI").click(function() {
            location_id = document.getElementById("location_id").value;
            start_date = document.getElementById("start_date").value;
            end_date = document.getElementById("end_date").value;
            marketing_kpi_table.ajax.url('{{ url('datatables/'.$url) }}'+'?location_id='+location_id+'&start_date='+start_date+'&end_date='+end_date).load();
        });


	});
    function exportExcel(type){
        location_id = document.getElementById("location_id").value;
        start_date = document.getElementById("start_date").value;
        end_date = document.getElementById("end_date").value;

        var url = "{{ url('export/complimentary_report') }}"+'?location_id='+location_id+'&start_date='+start_date+'&end_date='+end_date;

        window.location =url;
        return false;
    }
</script>
@endsection
