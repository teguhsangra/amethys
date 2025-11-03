@extends('layouts.app')
@section('title')
Rakomsis Booking Package
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Booking Package</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <ul class="nav nav-pills nav-pills-warning" role="tablist">
                        <li>
                        @if($a_g_and_module->create == 1)
                            <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                                <i class="fa fa-plus"></i>
                            </a>
                            &nbsp;
                            &nbsp;
                        @endif
                        </li>
                        @foreach($statuses as $no => $status)
                        <li class="nav-item">
                            <a class="nav-link @if($no == 0) active @endif" data-toggle="tab" href="#booking_{{ $status->id }}" role="tablist">
                                {{ $status->action }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content tab-space">
                    @foreach($statuses as $no => $status)
                    <div class="tab-pane material-datatables table-responsive @if($no == 0) active @endif" id="booking_{{ $status->id }}">
                        <table id="booking_packages_{{ $status->id }}-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Employee</th>
                                    <th>Customer</th>
                                    <th>Room</th>
                                    <th>Total Price</th>
                                    <th>Start Date</th>
                                    <th>Created At</th>
                                    <th>Status</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>

$('.date-own').datetimepicker({
            format: 'YYYY'
        });

    $(function() {
        @foreach($statuses as $no => $status)
	    $('#booking_packages_{{ $status->id }}-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url.'?status_id='.$status->id) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'employee_name', name: 'employee_name' },
	            { data: 'customer_name', name: 'customer_name' },
	            {
	            	sortable:false,
	            	"render" : function(row, data, full){
                        var package_name = full.package_name;
                        var return_html = '';
                        for(var i=0; i < package_name.length; i++){
                            return_html += '<p>'+package_name[i]+'</p>';
                        }
                        return return_html;
	            	}
                },
	            { data: 'total_price', name: 'total_price' },
	            { data: 'start_date', name: 'start_date' },
	            { data: 'created_at', name: 'created_at' },
	            { data: 'status_name', name: 'status_name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
                        var status_name = full.status_name;
	            		var id = full.id;
	            		var employee_id = full.employee_id;
                        var return_html = '';
                        @if($a_g_and_module->read == 1)
	            		    return_html += '<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> ';
                        @endif
                        @if($a_g_and_module->update == 1)
                            if(status_name == "open"){
                                return_html += '<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                            }
                        @endif
                        @if($a_g_and_module->delete == 1)
                            if(status_name == "open" || status_name == "posted"){
                                return_html += '<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';

                            }
                        @endif

                        return return_html;
	            	}
	            }
	        ],
	        sorting:[[ 5, 'desc' ]]
	    });
        @endforeach
	});
    function checkInAction(id){
        document.CheckInForm.action = "{{ url($url) }}/check_in/"+id;
        $("#checkInModal").modal();
    }

    function deleteAction(id){
        reason_html =   '<div class="row">'+
                            '<div class="col-md-12">'+
                                '<div class="form-group bmd-form-group is-filled">'+
                                    '<label class="label-control">Cancel/Discard Reason</label>'+
                                    '<input type="text" class="form-control" name="discard_or_cancel_reason" required>'+
                                    '<span class="material-input"></span>'+
                                    '<span class="material-input"></span>'+
                                '</div>'+
                            '</div>'+
                        '</div>';
        document.getElementById('discard_or_cancel_reason').innerHTML = reason_html;
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
    function getSchedule(){
        var month = document.getElementById("selection_month").value;
        var location = document.getElementById("location").value;
        var year = document.getElementById("year").value;
        var link = "{{ url('get_schedule_room') }}";
        var url = link+"?month="+month+"&location="+location+"&year="+year+"&room_category=MR";
        date_list = "";
        if(month != '' && location != '' && year != ''){
            $.get(url, function (data){
                date_list += '<table  class="table table-striped table-bordered table-hover table-responsive" cellspacing="0" width="100%" style="width:100%" id="schedule_bookings">';
                    date_list += '<thead>';
                        date_list +='<tr>';
                            date_list += '<th rowspan="2">Room</th>';
                            date_list += '<th class="text-center" colspan="'+data.days.length+'">'+data.month_name+', '+year+'</th>';
                        date_list +='</tr>';
                        date_list +='<tr>';
                            for(var i=0; i < data.days.length; i++){
                                date_list += '<th>'+data.days[i]+'</th>';
                            }
                        date_list +='</tr>';
                    date_list += '</thead>';
                    date_list += '<tbody >';
                        for(var i=0; i < data.rooms.length; i++){
                            date_list += '<tr>';
                                date_list += '<td class="table-info">'+data.rooms[i]['package_name']+'</td>';

                                for(var j=0; j < data.days.length; j++){
                                    var detail = data.list[j][i];
                                    var detail_url = "{{ url('booking_package') }}";
                                    var detail_style = '';

                                    if(detail == null){
                                        date_list += '<td></td>';
                                    }else{
                                        if(detail.detail_price > 0){
                                            detail_style = 'style="background-color: yellow;"';
                                        }else{
                                            detail_style = 'style="background-color: green;"';
                                        }
                                        detail_url += '/'+detail.id;
                                        date_list += '<td '+detail_style+'><a target="_blank" href="'+detail_url+'">'+detail.customer_name+'</a></td>';
                                    }
                                }
                            date_list += '</tr>';
                        }
                    date_list += '</tbody>';
                date_list +='</table>';

                document.getElementById("daily_schedule").innerHTML = date_list;

            });
        }
    }
</script>
@endsection
