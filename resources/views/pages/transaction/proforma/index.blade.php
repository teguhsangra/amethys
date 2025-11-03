@extends('layouts.app')
@section('title')
Rakomsis Proforma
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Proformas</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                @if($a_g_and_module->create == 1)
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
                            <a class="nav-link @if($no == 0) active @endif" data-toggle="tab" href="#proformas_{{ $status->id }}" role="tablist">
                                {{ $status->action }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endif
                </div>
                <div class="tab-content tab-space">
                        @foreach($statuses as $no => $status)
                        <div class="tab-pane material-datatables table-responsive @if($no == 0) active @endif" id="proformas_{{ $status->id }}">
                        <table id="proformas_{{ $status->id }}-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Location</th>
                                    <th>Customer</th>
                                    <th>Total Proforma</th>
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
    $(function() {
        @foreach($statuses as $no => $status)
	    $('#proformas_{{ $status->id }}-table').DataTable({
	        processing: true,
	        serverSide: true,
            responsive: true,
            autoWidth: false,
	        ajax: '{{ url('datatables/'.$url.'?status_id='.$status->id) }}',
	        columns: [
	            { data: 'code', name: 'code' },
                { data: 'location_name', name: 'location_name' },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'total_proforma', name: 'total_proforma' },
	            { data: 'created_at', name: 'created_at' },
	            { data: 'status_name', name: 'status_name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
	            		var url_invoice = "{{ url('invoice') }}";
                        var status_name = full.status_name;
                        var inquiry_id = full.inquiry_id;
	            		var id = full.id;
                        var return_html = '';
                        @if($a_g_and_module->isExec == 1)
                            if(status_name == "posted" && inquiry_id != null){
	            		        return_html += '<a href="'+url+'/'+id+'/edit/?action_status=complete" rel="tooltip"  class="btn btn-round btn-success" title="Complete">Complete </a> ';
                            }
                            if(status_name == "posted" && inquiry_id == null){
                                return_html += '<a href="'+url_invoice+'/create/?proforma_id='+id+'" rel="tooltip"  class="btn btn-round btn-primary" title="Closing">Create Invoice</a> ';
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
	        sorting:[[ 4, 'desc' ]]
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
