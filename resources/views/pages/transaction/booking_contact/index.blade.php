@extends('layouts.app')
@section('title')
Rakomsis Serviced Office Customer Contact
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($back_url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To {{ $menu_name }}
                    </a>
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
                <h4 class="card-title">{{ $menu_name }} Customer Contact</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">

                <a href="{{ url($url.'/create?back_url='.$back_url.'&menu_name='.$menu_name.'&booking_id='.$booking_id) }}" class="btn btn-success btn-round">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="material-datatables table-responsive">
                    <table id="contacts-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th class="disabled-sorting text-right">Actions</th>
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
	    $('#contacts-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: "{{ url('datatables/'.$url.'?booking_id='.$booking_id) }}",
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'name', name: 'name' },
	            { data: 'email', name: 'email' },
	            { data: 'phone', name: 'phone' },
	            { data: 'department', name: 'department' },
	            { data: 'position', name: 'position' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var id = full.id;
                        var url = '{{ url($url) }}';
                        var url_edit = '{{ url($url) }}/'+id+'/edit?booking_id={{ $booking_id }}&back_url={{ $back_url }}&menu_name={{ $menu_name }}';
                        var return_html = '';

                        return_html += '<a href='+url_edit+' rel="tooltip" class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                        return_html += '<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';

                        return return_html;
	            	}
	            }
	        ],
	    });
	});
</script>
@endsection
