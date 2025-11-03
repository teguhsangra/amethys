@extends('layouts.app')
@section('title')
Rakomsis User
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">User</h4>
            </div>
            <div class="card-body">
                <!--<div class="toolbar">-->
                <!--    <a href="{{ url($url) }}/create" class="btn btn-success btn-round">-->
                <!--        <i class="fa fa-plus"></i>-->
                <!--    </a>-->
                <!--</div>-->
                <div class="material-datatables">
                    <table id="users-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Access Group</th>
                                <th class="disabled-sorting text-right">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="formResetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Reset Password Box</h4>
                    </div>
                    <div class="modal-body">
                        Are you sure, do you want to reset password for this user ?
                        <br>
                        His/Her password will same with His/Her ID Number
                        <form id="resetPassword" action="{{ url('/reset_password/user/') }}" method="POST">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" id="user_id">
                            <div class="form-group">
                                <label class="control-label">New Password</label>
                                <input type="password" class="form-control" name="password" require="required">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>&nbsp;
                        <button type="button" class="btn btn-primary" onclick="submitForm('resetPassword')">Yes</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {
	    $('#users-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}',
	        columns: [
	            { data: 'name', name: 'users.name' },
	            { data: 'email', name: 'users.email' },
	            { data: 'created_at', name: 'users.created_at' },
	            { data: 'updated_at', name: 'users.updated_at' },
	            { data: 'ac_name', name: 'access_groups.name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
                        
	            		var url = '{{ url($url) }}';
	            		var id = full.id;
	            		return ''+
	            		'<a href="#" class="btn btn-round btn-default" title="Reset Password" onclick=resetPassword('+id+')>Reset Password</a> '+
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

    function resetPassword(userID)
    {
        document.getElementById('user_id').value = userID;
        $("#formResetPasswordModal").modal();
    }
</script>
@endsection