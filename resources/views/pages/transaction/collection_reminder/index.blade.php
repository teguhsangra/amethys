@extends('layouts.app')
@section('title')
Rakomsis Collection Reminder
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Choose Location</b></label>
                            <br>
                            <select id="location_id" class="selectpicker form-control" data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Select Your Location </option>
                                @foreach ($location as $item)
                                    <option value="{{$item->id}}" >{{$item->name}}</option>
                                @endforeach
                            </select>
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
                <h4 class="card-title">Collection Reminder</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables table-responsive">
                    <table id="collection_reminder-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Modify</th>
                                <th>Code</th>
                                <th>Location</th>
                                <th>Customer</th>
                                <th>Total Price</th>
                                <th>Total Tax</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="followUpModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Follow Up</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-md-12">
                    <label class="control-label">Follow Up List</label>
                    <div id="follow_up_list">

                    </div>
                </div>
                {{ Form::open(array('url' => url(''), 'method' => 'POST', 'id' => 'createForm', 'name' => 'createForm')) }}
                    <input type="hidden" name="booking_id" id="modal_booking_id">
                    <input type="hidden" name="invoice_id" id="modal_invoice_id">

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Date</label>
                        <input type="text" name="follow_up_date" class="form-control datepicker" required>
                    </div>
                    <p>Are you sure, do you want to renew this booking ?</p>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                <button type="submit" class="btn btn-success btn-link" onclick="submitForm('createForm')">Yes
                    <div class="ripple-container"></div>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    var location_id = null;
    $(function() {

      if(location_id == null){
        var table = $('#collection_reminder-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
                {
	            	sortable:false,
                    className: "td-actions text-center",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
                        var status_name = full.status_name;
	            		var id = full.id;
	            		var employee_id = full.employee_id;
                        var location_id = full.location_id;
                        var customer_id = full.customer_id;
                        var return_html = '';
                        var followUpUrl = '{{ url('getDataBookingReminder') }}';
                        @if($a_g_and_module->update == 1)
                            return_html +=  '<button onclick=openFollowUp('+id+',"'+followUpUrl+'")  class="btn btn-block btn-round btn-info" title="Detail">Follow UP</button><br>';
                            return_html += ' <a href="payment/create?invoice_id='+id+'&location_id='+location_id+'&customer_id='+customer_id+'" rel="tooltip"  class="btn btn-round btn-success" title="Pay Now"><i class="material-icons">money</i> Pay Now</a> ';
                        @endif
                        return return_html;
	            	}
	            },
	            { data: 'code', name: 'code' },
	            { data: 'location_name', name: 'location_name' },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'total_price', name: 'total_price' },
	            { data: 'total_tax_price', name: 'total_tax_price' },
	            { data: 'due_date', name: 'due_date' },

	        ],
	        sorting:[[ 5, 'desc' ]]


	    });
      }else{
        var table = $('#collection_reminder-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}'+'?location_id='+location_id,
	        columns: [
                {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
                        var status_name = full.status_name;
	            		var id = full.id;
	            		var employee_id = full.employee_id;
                        var return_html = '';
                        var followUpUrl = '{{ url('getDataBookingReminder') }}';
                        @if($a_g_and_module->update == 1)
                            return_html +=  '<button onclick=openFollowUp('+id+',"'+followUpUrl+'")  class="btn btn-block btn-round btn-info" title="Detail">Follow UP</button><br>';
                            return_html +=  '<a  class="btn btn-block btn-round  btn-primary" title="Edit">Create Payment</a><br>';

                        @endif
                        return return_html;
	            	}
	            },
	            { data: 'code', name: 'code' },
	            { data: 'location_name', name: 'location_name' },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'total_price', name: 'total_price' },
	            { data: 'total_tax_price', name: 'total_tax_price' },
	            { data: 'due_date', name: 'due_date' },

	        ],
	        sorting:[[ 5, 'desc' ]]


	    });
      }



        $("#location_id").change(function() {
            var location_id = document.getElementById("location_id").value;
            table.ajax.url('{{ url('datatables/'.$url) }}'+'?location_id='+location_id).load();
        });


    });


    function openFollowUp(invoiceID, followUpUrl){
        var url = followUpUrl+"?invoice_id="+invoiceID;
        $("#follow_up_list").empty();
    	$.get(url, function (data){
            var followUpList = '<table width="100%" class="table table-striped table-bordered table-hover " id="table-renewal-reminders">'+
                            '<thead>'+
                                '<th class="text-center"></th>'+
                                '<th>Date</th>'+
                                '<th>Remarks</th>'+
                                '<th>Follow Up By</th>'+
                            '</thead>'+
                            '<tbody>';
            for(var i=0; i<data.length; i++)
            {
                followUpList += '<tr>'+
                                    '<td>'+data[i]['follow_up_number']+'</td>'+
                                    '<td>'+data[i]['follow_up_date']+'</td>'+
                                    '<td>'+data[i]['remarks']+'</td>'+
                                    '<td>'+data[i]['created_by']+'</td>'+
                                '<tr>';
            }
            $("#follow_up_list").append(followUpList);
            followUpList += '/<tbody>'+
                        '</table>';
            document.getElementById("modal_invoice_id").value = invoiceID;
            document.createForm.action = "{{ url($url) }}";
            $("#followUpModal").modal();
        });

    }


</script>
@endsection
