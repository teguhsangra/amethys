@extends('layouts.app')
@section('title')
Rakomsis Booking Report
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Service Type</b></label><br>
                            <select class="selectpicker form-control" name="room_category_id" id="room_category_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Select Your Option</option>
                                @foreach($room_categories as $detail)
                                    <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Start Date</b></label><br>
                            <input type="date" name="start_date" id="start_date" class="form-control col-sm-10" value="">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>End Date</b></label><br>
                            <input type="date" name="end_date" id="end_date" class="form-control col-sm-10" value="">
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
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Booking Report</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="inquiry-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Customer</th>
                                <th>Contact Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Created Date</th>
                                <th>Created By</th>
                            </tr>
                        </thead>
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




    var table = $('#inquiry-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url('datatables/'.$url) }}'+'?start_date=&end_date=',
        columns: [
            { data: 'code', name: 'code' },
            { data: 'location_name', name: 'location_name' },
            {
                sortable:false,
                className: "td-actions text-right",
                "render" : function(row, data, full){
	            	var type = full.type;
	            	var room_category_id = full.room_category_id;
                    var value = '';

                    if(type == "product"){
                        value = "Virtual Office";
                    }else if (type == "package"){
                        value = "Package";
                    }else{
                        if(room_category_id == 1){
                            value = "Serviced Office";
                        }else if(room_category_id == 2){
                            value = "Meeting Room";
                        }else if(room_category_id == 3){
                            value = "Coworking";
                        }else if(room_category_id == 4){
                            value = "Lodgement";
                        }else if(room_category_id == 5){
                            value = "Regular Office";
                        }
                    }

                    return value;
                }
            },
            { data: 'customer_name', name: 'customer_name' },
            { data: 'customer_contact_name', name: 'customer_contact_name' },
            { data: 'customer_phone_no', name: 'customer_phone_no' },
            { data: 'customer_email', name: 'customer_email' },
            { data: 'created_at', name: 'created_at' },
            { data: 'posting_by', name: 'posting_by' },

        ],
        sorting:[[ 5, 'desc' ]]
    });


    $("#filter").click(function() {

        var start_date = document.getElementById("start_date").value;
        var end_date = document.getElementById("end_date").value;
        var room_category_id = document.getElementById("room_category_id").value;
        table.ajax.url('{{ url('datatables/'.$url) }}'+'?room_category_id='+room_category_id+'&start_date='+start_date+'&end_date='+end_date).load();

    });

});
function exportExcel(){
    var start_date = document.getElementById("start_date").value;
    var end_date = document.getElementById("end_date").value;
    var room_category_id = document.getElementById("room_category_id").value;

    var url = '{{ url('exportBooking') }}'+'?room_category_id='+room_category_id+'&start_date='+start_date+'&end_date='+end_date;

    var link =url;
    window.location =link;
    return false;
}
</script>
@endsection
