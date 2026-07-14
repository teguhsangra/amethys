@extends('layouts.app')
@section('title')
Rakomsis Invoice Report
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
                                <option value="">Select All</option>
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
                            <label class="col-sm-2"><b>Start Date</b></label><br>
                            <input type="date" name="start_date" id="start_date" class="form-control col-sm-10" value="{{$start_date}}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>End Date</b></label><br>
                            <input type="date" name="end_date" id="end_date" class="form-control col-sm-10" value="{{$end_date}}">
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
                <h4 class="card-title">Invoice Report</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables">
                    <table id="invoice-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Total Price</th>
                                <th>Total Service Price</th>
                                <th>Tax Price</th>
                                <th>Total Paid</th>
                                <th>Total Outstanding</th>
                                <th>Invoice Status</th>
                                <th>Invoice Date</th>
                                <th>Due Date</th>
                                <th>Start Period</th>
                                <th>End Period</th>
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

    $("#filter").on('click', function(){
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var location_id = document.getElementById('location_id').value;
        window.location.href = '{{ url('invoice_report')}}'+"?location_id="+location_id+"&start_date="+start_date+"&end_date="+end_date;
    });
    $(document).ready(function() {
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var location_id = document.getElementById('location_id').value;
        $('#invoice-table').DataTable({
            processing: true,
            serverSide: true,
            scrollY: 300,
            scrollX: 300,
            ajax: '{{ url('datatables/'.$url) }}'+'?location_id='+location_id+'&start_date='+start_date+"&end_date="+end_date,
            columns: [
                { data: 'code', name: 'code' },
                { data: 'customer_name', name: 'customer_name'},
                { data: 'total_price', name: 'total_price'},
                { data: 'total_service_charge', name: 'total_service_charge' },
                { data: 'total_tax_price', name: 'total_tax_price' },
                { data: 'total_paid', name: 'total_paid' },
                { data: 'total_outstanding', name: 'total_outstanding' },
                { data: 'payment_status', name: 'payment_status'},
                { data: 'invoice_date', name: 'invoice_date'},
                { data: 'due_date', name: 'due_date' },
                { data: 'start_period', name: 'start_period' },
                { data: 'end_period', name: 'end_period' },
            ],
            sorting:[[ 0, 'asc' ]]
        });
    });
    function exportExcel(){
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var location_id = document.getElementById('location_id').value;

        var url = '{{ url('exportInvoice') }}'+'?location_id='+location_id+'&start_date='+start_date+"&end_date="+end_date;

        var link =url;
        window.location =link;
        return false;
    }
</script>
@endsection
