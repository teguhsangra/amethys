@extends('layouts.app')
@section('title')
Rakomsis Customer
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Customer</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        @if($a_g_and_module->create == 1)
                            <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                                <i class="fa fa-plus"></i>
                            </a>
                        @endif
                        &nbsp;
                        <button onclick="exportExcel()" class="btn btn-success">Export Customer</button>
                    </div>
                </div>
                <div class="material-datatables table-responsive">
                    <table id="customers-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
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
	    $('#customers-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'name', name: 'name' },
	            { data: 'email', name: 'email' },
	            { data: 'phone', name: 'phone' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){

	            		var url = '{{ url($url) }}';
	            		var id = full.id;
	            		return ''+
                        @if($a_g_and_module->read == 1)
	            		'<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> '+
                        @endif
                        @if($a_g_and_module->update == 1)
	            		'<a href='+url+'/file/'+id+' rel="tooltip"  class="btn btn-round btn-default" title="File"><i class="material-icons">file_copy</i></a> '+
	            		'<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> '+
                        @endif
                        @if($a_g_and_module->delete == 1)
	            		'<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>'+
                        @endif
                        '';
	            	}
	            }
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });
	});

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
    function exportExcel(){

        var url = "{{ url('exportCustomer') }}";

        window.location =url;
        return false;
    }
</script>
@endsection
