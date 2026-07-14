@extends('layouts.app')
@section('title')
Rakomsis Inquiry Report
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
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
                <h4 class="card-title">Inquiry Report</h4>
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
            { data: 'type', name: 'type' },
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
        table.ajax.url('{{ url('datatables/'.$url) }}'+'?start_date='+start_date+'&end_date='+end_date).load();
    });

});
function exportExcel(){
    var start_date = document.getElementById("start_date").value;
    var end_date = document.getElementById("end_date").value;
    var url = '{{ url('exportInquiry') }}'+"?start_date="+start_date+"&end_date="+end_date;

    var link =url;
    window.location =link;
    return false;
}
</script>
@endsection
