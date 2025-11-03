@extends('layouts.app')
@section('title')
Rakomsis Billing Reminder
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" id="OR_table">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Billing Reminder</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables table-responsive">
                    <table id="billing_reminder-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Modify</th>
                                <th>Customer</th>
                                <th>Category</th>
                                <th>Start Date</th>
                                <th>End Date</th>
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

        $('#billing_reminder-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
                {
	            	sortable:false,
                    className: "td-actions text-left",
	            	"render" : function(row, data, full){
	            		var id = full.id;
	            		var location_id = full.location_id;
	            		var customer_id = full.customer_id;
	            		var type = full.type;
                        var return_html = '';
                        return_html +=  '<a href="{{ url('invoice')}}/create?location_id='+location_id+'&customer_id='+customer_id+'&type='+type+'&detail_id='+id+'" class="btn btn-block btn-round btn-info" title="Detail">Create Invoice</a><br>';
                        return_html +=  '<a href="{{ url('proforma')}}/create?location_id='+location_id+'&customer_id='+customer_id+'&type='+type+'&detail_id='+id+'"  class="btn btn-block btn-round  btn-primary" title="Edit">Create Proforma</a><br>';
                        return return_html;
	            	}
	            },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'category', name: 'category' },
	            { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
	        ],
	        sorting:[[ 3, 'desc' ]]
	    });
	});



</script>
@endsection
