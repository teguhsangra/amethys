@extends('layouts.app')
@section('title')
Rakomsis Company
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Company</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="material-datatables">
                    <table id="company-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Phone</th>
                                <th>Fax</th>
                                <th>Booking Signatory</th>
                                <th>Proforma Signatory</th>
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
	    $('#company-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'name', name: 'name' },
	            { data: 'address', name: 'address' },
	            { data: 'phone', name: 'phone' },
	            { data: 'fax', name: 'fax' },
	            { data: 'booking_signatory', name: 'booking_signatory' },
	            { data: 'proforma_signatory', name: 'proforma_signatory' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){

	            		var url = '{{ url($url) }}';
	            		var id = full.id;
	            		return ''+
	            		'<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> '+
	            		'<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> '+
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
