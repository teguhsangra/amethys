@extends('layouts.app')
@section('title')
Rakomsis Booking Reminder
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
                                <option value="" selected>Select All</option>
                                @foreach ($location as $item)
                                    @php
                                        $selected = '';
                                        if(!empty($location_id)){
                                            if($location_id == $item->id){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{$item->id}}" {{$selected}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Service Type</b></label><br>
                            <select class="selectpicker form-control" name="room_category_id" id="room_category_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" selected>Select All</option>
                                <option value="VO" @if(!empty($room_category_id)) @if($room_category_id == "VO") selected @endif @endif>Virtual Office</option>
                                @foreach($room_categories as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($room_category_id)){
                                            if($room_category_id == $detail->id){
                                                $selected = 'selected';
                                            }
                                        }

                                    @endphp
                                    <option value="{{ $detail->id }}" {{$selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="col-sm-3 "><b>Choose Renewal Status</b></label>
                            <br>
                            <select id="renewal_status" name="renewal_status" data-live-search="true" class="form-control selectpicker" data-style="btn btn-primary btn-round" data-show-subtext="true">
                                <option value="">Please select one of location</option>
                                <option value="" selected>Select All</option>
                                <option value="OR" @if(!empty($renewal_status)) @if($renewal_status == "OR") selected @endif @endif>On Running</option>
                                <option value="RN" @if(!empty($renewal_status)) @if($renewal_status == "RN") selected @endif @endif>Renewal</option>
                                <option value="TM" @if(!empty($renewal_status)) @if($renewal_status == "TM") selected @endif @endif>Terminate</option>
                            </select>
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

    <div class="col-md-12" id="OR_table">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Booking Reminder</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <button class="btn btn-success" onclick="exportExcel()">Export To Excel </button>
                </div>
                <div class="material-datatables table-responsive">
                    <table id="booking_or-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Modify</th>
                                <th>Location</th>
                                <th>Code</th>
                                <th>Customer</th>
                                <th>Type</th>
                                <th>Remarks</th>
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
                    <input type="hidden" name="invoice_id" value="">

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Date</label>
                        <input type="text" name="follow_up_date" class="form-control datepicker" required>
                    </div>
                    <p>Are you sure, Do you want to save this activity? ?</p>
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
<div id="renewOrTerminateModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation box</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => url(''), 'method' => 'PUT', 'id' => 'updateForm', 'name' => 'updateForm')) }}
                    <p id="modalRenewMessage">Modal Message</p>
                    <br>
                    <input type="hidden" name="booking_id" id="renew_booking_id">
                    <input type="hidden" name="invoice_id" value="">
                    <input type="hidden" name="renewal_status" id="renewal_status_">

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
    // var renewal_status = "OR";

    $(function() {
        var table = $('#booking_or-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}'+'?location_id={{$location_id}}&room_category_id={{$room_category_id}}&renewal_status={{$renewal_status}}',
	        columns: [
                {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
                        var status_name = full.status_name;
                        var renewal_status = full.renewal_status;
	            		var id = full.id;
	            		var employee_id = full.employee_id;
                        var product_id = full.product_id;
                        var room_category_id = full.room_category_id;
                        var type = full.type;
                        var return_html = '';
                        var followUpUrl = '{{ url('getDataBookingReminder') }}';
                        var booking_url = '';

                        if(type == 'product'){
                            booking_url = "{{ url('virtual_office') }}";
                        }else if(type == 'package'){
                            booking_url = "{{ url('booking_package') }}";
                        }else if(type == 'room'){
                            if(room_category_id == 1){
                                booking_url = "{{ url('serviced_office') }}";
                            }else if(room_category_id == 2){
                                booking_url = "{{ url('meeting_room') }}";
                            }else if(room_category_id == 3){
                                booking_url = "{{ url('coworking') }}";
                            }else if(room_category_id == 4){
                                booking_url = "{{ url('hotel') }}";
                            }else if(room_category_id == 5){
                                booking_url = "{{ url('regular_office') }}";
                            }
                        }

                        @if($a_g_and_module->update == 1)
                            if(renewal_status == "OR"){
                                return_html += '<button onclick=openFollowUp('+id+',"'+followUpUrl+'")  class="btn btn-block btn-round btn-info" title="Detail">Follow UP</button><br>';
                                return_html += '<button onclick=renewOrTerminateBooking('+id+',"RN") class="btn btn-block btn-round  btn-primary" title="Edit">Ready For Renewal</button><br>';
                                return_html += '<button onclick=renewOrTerminateBooking('+id+',"TM")  class="btn btn-block btn-round btn-danger" title="Delete">Ready For Terminate</button><br>';
                            }else if(renewal_status == "RN"){
                                return_html += '<a href="'+booking_url+'/create?booking_id='+id+'" class="btn btn-block btn-round btn-success">Renewal</a>';
                            }else if(renewal_status == "TM"){
                                return_html += '<a onclick=deleteAction('+id+')  class="btn btn-block btn-round btn-danger" title="Delete">Terminate</button>';
                            }
                        @endif
                        return return_html;
	            	}
	            },
                { data: 'location_name', name: 'location_name' },
	            { data: 'code', name: 'code' },
	            { data: 'customer_name', name: 'customer_name' },
                {
	            	sortable:true,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
                        var room_category_id = full.room_category_id;
                        var type = full.type;
                        var return_html = '';

                        if(type == 'product'){
                            return_html = "Virtual Office";
                        }else if(type == 'package'){
                            return_html = "Package";
                        }else if(type == 'room'){
                            if(room_category_id == 1){
                                return_html = "Serviced Office";
                            }else if(room_category_id == 2){
                                return_html = "Meeting Room";
                            }else if(room_category_id == 3){
                                return_html = "Workstation";
                            }else if(room_category_id == 4){
                                return_html = "Hotel";
                            }else if(room_category_id == 5){
                                return_html = "Regular Office";
                            }
                        }
                        return return_html;
	            	}
	            },
	            { data: 'discard_or_cancel_reason', name: 'discard_or_cancel_reason' },
	            { data: 'start_date', name: 'start_date' },
	            { data: 'end_date', name: 'end_date' },

	        ],
	        sorting:[[ 5, 'desc' ]]
	    });

        $("#filter").on('click', function(){
            var location_id = $("#location_id").val();
            var room_category_id = $("#room_category_id").val();
            var renewal_status = $("#renewal_status").val();

            window.location.href = '{{ url($url)}}'+"?location_id="+location_id+'&room_category_id='+room_category_id+'&renewal_status='+renewal_status;
        });

	});

    function openFollowUp(bookingID, followUpUrl){
        var url = followUpUrl+"?booking_id="+bookingID;
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
            followUpList += '</tbody>'+
                        '</table>';
            document.getElementById("modal_booking_id").value = bookingID;
            document.createForm.action = "{{ url($url) }}";
            document.getElementById("follow_up_list").innerHTML = followUpList;
            $("#followUpModal").modal();
        });

    }

    function renewOrTerminateBooking(bookingID, renewal_status)
    {
        var message = '';
        if(renewal_status == "RN"){
            message = "Are you sure, do you want to renew this booking ?";
        }else{
            message = "Are you sure, do you want to terminate this booking ?";
        }
        document.getElementById("modalRenewMessage").innerHTML = message;
        document.getElementById("renew_booking_id").value = bookingID;
        document.getElementById("renewal_status_").value = renewal_status;
        document.updateForm.action = "{{ url($url) }}/"+bookingID;
        $("#renewOrTerminateModal").modal();
    }

    function exportExcel(){
        var location_id = $("#location_id").val();
            var room_category_id = $("#room_category_id").val();
            var renewal_status = $("#renewal_status").val();
        var url = '{{ url('exportBookingReminder') }}'+"?location_id="+location_id+'&room_category_id='+room_category_id+'&renewal_status='+renewal_status;
        var link =url;
        window.location =link;
        return false;


    }

    function deleteAction(id){
        document.getElementById('discard_or_cancel_label').innerHTML = "Are you wan to terminate this booking ?";
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
</script>
@endsection
