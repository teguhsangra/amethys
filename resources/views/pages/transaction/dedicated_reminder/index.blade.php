@extends('layouts.app')
@section('title')
Rakomsis Dedicated Phone Reminder
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" id="OR_table">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Dedicated Phone Reminder</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables table-responsive">
                    <table id="billing_reminder-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Customer</th>
                                <th>Number</th>
                                <th>Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="updateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation box</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => url(''), 'method' => 'PUT', 'id' => 'updateForm', 'name' => 'updateForm')) }}
                    <p id="modalDeactivate">Modal Message</p>
                    <br>
                    <input type="hidden" name="activity" value="deactivation">

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                <button type="submit" class="btn btn-success btn-link" onclick="submitForm('updateForm')">Yes
                    <div class="ripple-container"></div>
                </button>
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
                { data: 'location_name', name: 'location_name' },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'number', name: 'number' },
	            { data: 'type', name: 'type' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                {
                    sortable:false,
                    className: "td-actions text-right",
                    "render" : function(row, data, full){
                        var dedicated_phone_id = full.dedicated_phone_id;
                        var return_html = '';
                        return_html +=  '<a onclick=deactivate('+dedicated_phone_id+') class="btn btn-md btn-round btn-danger" title="Deactivate"><i class="fa fa-times"></i></a><br>';
                        return return_html;
                    }
                }

	        ],
	        sorting:[[ 3, 'desc' ]]
	    });
	});
    function deactivate(dedicated_phone_id)
    {
        var message = "Are you sure, do you want to deactivate this phone number ?";

        document.getElementById("modalDeactivate").innerHTML = message;
        document.updateForm.action = "{{ url('dedicated_phone_transaction') }}/"+dedicated_phone_id;

        $("#updateModal").modal();
    }


</script>
@endsection
