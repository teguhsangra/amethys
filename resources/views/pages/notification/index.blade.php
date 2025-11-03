@extends('layouts.app')
@section('title')
Rakomsis Notification
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Notification</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables table-responsive">
                    <table id="notifications-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Header</th>
                                <th>Url</th>
                                <th>Created At</th>
                                <th>Created By</th>
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
	    $('#notifications-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            { data: 'header', name: 'header' },
	            { data: 'url', name: 'url' },
	            { data: 'created_at', name: 'created_at' },
	            { data: 'updated_at', name: 'updated_at' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){

	            		var url = '{{ url($url) }}';
	            		var id = full.id;
	            		return ''+
	            		'<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> '+
	            		'<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';
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
</script>
@endsection
