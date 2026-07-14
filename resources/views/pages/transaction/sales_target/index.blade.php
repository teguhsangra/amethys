@extends('layouts.app')
@section('title')
Rakomsis Sales Target
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Sales Target</h4>
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
                            <a class="nav-link @if($no == 0) active @endif" data-toggle="tab" href="#sales_target_{{ $status->id }}" role="tablist">
                                {{ $status->action }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content tab-space">
                    @foreach($statuses as $no => $status)
                    <div class="tab-pane material-datatables table-responsive @if($no == 0) active @endif" id="sales_target_{{ $status->id }}">
                        <table id="sales_targets_{{ $status->id }}-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Employee</th>
                                    <th>Year</th>
                                    <th>Month</th>
                                    <th>Total Target</th>
                                    <th>Total VO</th>
                                    <th>Total SO</th>
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
    $(function() {
        @foreach($statuses as $no => $status)
	    $('#sales_targets_{{ $status->id }}-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url.'?status_id='.$status->id) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'employee_name', name: 'employee_name' },
	            { data: 'year', name: 'year' },
	            { data: 'month', name: 'month' },
	            { data: 'total_target', name: 'total_target' },
	            { data: 'total_target_vo', name: 'total_target_vo' },
	            { data: 'total_target_so', name: 'total_target_so' },
	            { data: 'status_name', name: 'status_name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
                        var status_name = full.status_name;
	            		var id = full.id;
                        var return_html = '';
                        @if($a_g_and_module->isExec == 1)
                            if(status_name == "posted"){
	            		        return_html += '<a href="'+url+'/'+id+'/edit/?action_status=complete" rel="tooltip"  class="btn btn-round btn-success" title="Complete">Complete </a> ';
                            }
                        @endif
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
	        sorting:[[ 0, 'asc' ]]
	    });
        @endforeach
	});

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
</script>
@endsection
