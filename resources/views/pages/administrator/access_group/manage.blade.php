@extends('layouts.app')
@section('title')
Rakomsis Access Group - {{ $access_group->name }}
@endsection

@section('content')
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Assigned Modules</h4>
            </div>
            <div class="card-body">
				<input type="text" name="title_module" id="title_module" class="form-control" placeholder="Search Module">
				<table id="assignedModule-table" class="table table-bordered table-hover" width="100%" style="width:100%">
					<thead>
						<tr>
							<th></th>
							<th>Module</th>
							<th>Read</th>
							<th>Create</th>
							<th>Update</th>
							<th>Delete</th>
							<th>Exec</th>
							<th>Show Data</th>
						</tr>
					</thead>
				</table>
				<div class="modal fade" id="unassignModuleModal" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Unassign This Module From Access Group</h4>
							</div>
							<div class="modal-body">
								<form class="form" id="formUnassignModule" action="{{ url('/access_group/manage/module') }}/{{ $id }}" method="POST">
								    <input type="hidden" name="_method" value="DELETE">
								    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								    <input type="hidden" name="module_id" id="module_id" value="">
									<div class="form-group">
										<label class="control-label">Title</label> <br>
										<input type="text" name="title" name="module_title" id="module_title" class="form-control" value="" readonly >
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
								<button type="button" class="btn btn-success pull-right" onclick="submitForm('formUnassignModule')">Yes</button>
							</div>
						</div>
					</div>
				</div>
				<div class="modal fade" id="editAGM" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Edit this Access Group Module</h4>
							</div>
							<div class="modal-body">
								<form class="form" id="formEditAGM" action="{{ url('/access_group/manage/module') }}/{{ $id }}" method="POST">
								    <input type="hidden" name="_method" value="PUT">
								    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								    <input type="hidden" name="module_id" id="module_id" value="">
								    <div class="form-group">
										<label class="control-label">Title</label> <br>
										<input type="text" name="title" name="module_title" id="module_title" class="form-control" value="" readonly >
									</div>
								    <div class="form-group">
										<label class="control-label col-md-3">Read</label>
										<input type="checkbox" name="read" id="readAGM" value="1">
								    </div>
								    <div class="form-group">
										<label class="control-label col-md-3">Create</label>
										<input type="checkbox" name="create" id="createAGM" value="1">
								    </div>
								    <div class="form-group">
										<label class="control-label col-md-3">Update</label>
										<input type="checkbox" name="update" id="updateAGM" value="1">
								    </div>
								    <div class="form-group">
										<label class="control-label col-md-3">Delete</label>
										<input type="checkbox" name="delete" id="deleteAGM" value="1">
								    </div>
								    <div class="form-group">
										<label class="control-label col-md-3">Is Exec</label>
										<input type="checkbox" name="isExec" id="isExecAGM" value="1">
								    </div>
								    <div class="form-group">
										<label class="control-label col-md-3">Show data by structure</label>
										<input type="checkbox" name="showDataByStructure" id="showDataByStructure" value="1">
								    </div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
								<button type="button" class="btn btn-success pull-right" onclick="submitForm('formEditAGM')">Yes</button>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Not Assigned Modules</h4>
            </div>
            <div class="card-body">
				<table id="notAssignedModule-table" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Module</th>
						</tr>
					</thead>
				</table>
				<div class="modal fade" id="assignModuleModal" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Assign This User To Access Group</h4>
							</div>
							<div class="modal-body">
								<form class="form" id="formAssignModule" action="{{ url('/access_group/manage/module/') }}/{{ $id }}" method="POST">
								    <input type="hidden" name="_method" value="POST">
								    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								    <input type="hidden" name="module_id" id="module_id" value="">
									<div class="form-group">
										<label class="control-label">Title</label> <br>
										<input type="text" name="title" name="module_title" id="module_title" class="form-control" value="" readonly >
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
								<button type="button" class="btn btn-success pull-right" onclick="submitForm('formAssignModule')">Yes</button>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Assigned User</h4>
            </div>
            <div class="card-body">
				<table id="assignedUser-table" class="table table-bordered table-hover" width="100%" style="width:100%">
					<thead>
						<tr>
							<th></th>
							<th>Email</th>
							<th>Name</th>
						</tr>
					</thead>
				</table>
				<div class="modal fade" id="unassignUserModal" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Unassign This User From Access Group</h4>
							</div>
							<div class="modal-body">
								<form class="form" id="formUnassignUser" action="{{ url('/access_group/manage/user/') }}/{{ $id }}" method="POST">
								    <input type="hidden" name="_method" value="DELETE">
								    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								    <input type="hidden" name="user_id" id="user_id" value="">
									<div class="form-group">
										<label class="control-label">Email</label> <br>
										<input type="text" name="icon" name="user_email" id="user_email" class="form-control" value="" readonly >
									</div>
									<div class="form-group">
										<label class="control-label">Name</label> <br>
										<input type="text" name="icon" name="user_name" id="user_name" class="form-control" value="" readonly >
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
								<button type="button" class="btn btn-success pull-right" onclick="submitForm('formUnassignUser')">Yes</button>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Not Assigned Users</h4>
            </div>
            <div class="card-body">
				<table id="notAssignedUser-table" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Email</th>
							<th>Name</th>
						</tr>
					</thead>
				</table>
				<div class="modal fade" id="assignUserModal" role="dialog">
					<div class="modal-dialog">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Assign This User To Access Group</h4>
							</div>
							<div class="modal-body">
								<form class="form" id="formAssignUser" action="{{ url('/access_group/manage/user/') }}/{{ $id }}" method="POST">
								    <input type="hidden" name="_method" value="POST">
								    <input type="hidden" name="_token" value="{{ csrf_token() }}">
								    <input type="hidden" name="user_id" id="user_id" value="">
									<div class="form-group">
										<label class="control-label">Email</label> <br>
										<input type="text" name="icon" name="user_email" id="user_email" class="form-control" value="" readonly >
									</div>
									<div class="form-group">
										<label class="control-label">Name</label> <br>
										<input type="text" name="icon" name="user_name" id="user_name" class="form-control" value="" readonly >
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal">No</button>
								<button type="button" class="btn btn-success pull-right" onclick="submitForm('formAssignUser')">Yes</button>
							</div>
						</div>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(function() {
	    $('#notAssignedUser-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/access_group/manage/unassignUser/') }}/{{$id}}',
	        columns: [
	            {
	            	sortable:false,
	            	"render" : function(data, type, full, meta){
	            		var userID = full.id;
	            		var userEmail = full.email.split(" ");
	            		var userName = full.name.split(" ");
	            		return ''+
	            		'<button onclick=assignUser('+userID+',"'+userEmail[0]+'","'+userName[0]+'") class="btn btn-circle btn-success" title="Assign"><i class="fa fa-plus"> </i></button>';
	            	}
	            },
	            { data: 'name', name: 'name' },
	            { data: 'email', name: 'email' }
	        ],
	        sorting:[[ 1, 'asc' ]]
	    });
	    $('#assignedUser-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/access_group/manage/assignUser/') }}/{{$id}}',
	        columns: [
	            {
	            	sortable:false,
	            	"render" : function(data, type, full, meta){
	            		var userID = full.id;
	            		var userEmail = full.email.split(" ");
	            		var userName = full.name.split(" ");
	            		return ''+
	            		'<button onclick=unassignUser('+userID+',"'+userEmail[0]+'","'+userName[0]+'") class="btn btn-circle btn-danger" title="Unassign"><i class="fa fa-times"> </i></button>';
	            	}
	            },
	            { data: 'name', name: 'name' },
	            { data: 'email', name: 'email' }
	        ],
	        sorting:[[ 1, 'asc' ]]
	    });
	    $('#notAssignedModule-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/access_group/manage/unassignModule/') }}/{{$id}}',
	        columns: [
	            {
	            	sortable:false,
	            	"render" : function(data, type, full, meta){
	            		var moduleID = full.id;
	            		var moduleName = full.name;
	            		return ''+
	            		'<button onclick="assignModule('+moduleID+','+"'"+moduleName+"'"+')" class="btn btn-circle btn-success" title="Assign"><i class="fa fa-plus"> </i></button>';
	            	}
	            },
	            { data: 'name', name: 'name' }
	        ],
	        sorting:[[ 1, 'asc' ]]
	    });
	});
	var table = $('#assignedModule-table').DataTable({
    	"lengthMenu": [[5, 15, 25, 50], [5, 15, 25, 50]],
        processing: true,
        serverSide: true,
        "scrollX": true,
        "sDom": '<"top"i>rt<"bottom"lp><"clear">',
        ajax: '{{ url('datatables/access_group/manage/assignModule/') }}/{{$id}}',
        columns: [
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var moduleID = full.id;
            		var moduleName = full.name;
            		return ''+
            		'<button onclick="editAGM('+moduleID+','+"'"+moduleName+"'"+')" class="btn btn-block btn-info" title="Edit"><i class="fa fa-edit"> </i></button>'+
            		'<button onclick="unassignModule('+moduleID+','+"'"+moduleName+"'"+')" class="btn btn-block btn-danger" title="Unassign"><i class="fa fa-times"> </i></button>';
            	}
            },
            { data: 'name', name: 'name' },
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var checked = "";
            		var moduleID = full.module_id;
            		if(full.read != 0){
            			checked = 'checked';
					}
            		return ''+
            		'<div class="form-group">'+
            		'<input type="checkbox" disabled="disabled" id="readAGM'+moduleID+'" name="read" '+checked+'>'+
            		'</div>';
            	}
            },
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var checked = "";
            		var moduleID = full.module_id;
            		var action = full.create;
            		if(action != 0)
            			checked = 'checked="checked"';
            		return ''+
            		'<div class="form-group">'+
            		'<input type="checkbox" disabled="disabled" id="createAGM'+moduleID+'" name="read" '+checked+'>'+
            		'</div>';
            	}
            },
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var checked = "";
            		var moduleID = full.module_id;
            		var action = full.update;
            		if(action != 0)
            			checked = 'checked="checked"';
            		return ''+
            		'<div class="form-group">'+
            		'<input type="checkbox" disabled="disabled" id="updateAGM'+moduleID+'" name="read" '+checked+'>'+
            		'</div>';
            	}
            },
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var checked = "";
            		var moduleID = full.module_id;
            		var action = full.delete;
            		if(action != 0)
            			checked = 'checked="checked"';
            		return ''+
            		'<div class="form-group">'+
            		'<input type="checkbox" disabled="disabled" id="deleteAGM'+moduleID+'" name="read" '+checked+'>'+
            		'</div>';
            	}
            },
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var checked = "";
            		var moduleID = full.module_id;
            		var action = full.isExec;
            		if(action != 0)
            			checked = 'checked="checked"';
            		return ''+
            		'<div class="form-group">'+
            		'<input type="checkbox" disabled="disabled" id="isExecAGM'+moduleID+'" name="read" '+checked+'>'+
            		'</div>';
            	}
            },
            {
            	sortable:false,
            	"render" : function(data, type, full, meta){
            		var checked = "";
            		var moduleID = full.module_id;
            		var action = full.showDataByStructure;
            		if(action != 0)
            			checked = 'checked="checked"';
            		return ''+
            		'<div class="form-group">'+
            		'<input type="checkbox" disabled="disabled" id="showDataByStructure'+moduleID+'" name="showDataByStructure" '+checked+'>'+
            		'</div>';
            	}
            }
        ],
        sorting:[[ 1, 'asc' ]]
    });
	$('#title_module').on( 'keyup', function () {
	    table
	        .columns( 1 )
	        .search( this.value )
	        .draw();
	} );
	function assignUser(user_id,user_email,user_name){
		$("#assignUserModal .modal-body #user_id").val( user_id );
		$("#assignUserModal .modal-body #user_email").val( user_email );
		$("#assignUserModal .modal-body #user_name").val( user_name );
		$("#assignUserModal").modal();
	}
	function assignModule(module_id,module_title){
		$("#assignModuleModal .modal-body #module_id").val( module_id );
		$("#assignModuleModal .modal-body #module_title").val( module_title );
		$("#assignModuleModal").modal();
	}
	function unassignUser(user_id,user_email,user_name){
		$("#unassignUserModal .modal-body #user_id").val( user_id );
		$("#unassignUserModal .modal-body #user_email").val( user_email );
		$("#unassignUserModal .modal-body #user_name").val( user_name );
		$("#unassignUserModal").modal();
	}
	function unassignModule(module_id,module_title){
		$("#unassignModuleModal .modal-body #module_id").val( module_id );
		$("#unassignModuleModal .modal-body #module_title").val( module_title );
		$("#unassignModuleModal").modal();
	}
	function editAGM(module_id,module_title){
		$("#editAGM .modal-body #module_id").val( module_id );
		$("#editAGM .modal-body #module_title").val( module_title );

		if(document.getElementById("readAGM"+module_id).checked)
			$("#editAGM .modal-body #readAGM").prop("checked", true);
		else
			$("#editAGM .modal-body #readAGM").prop("checked", false);

		if(document.getElementById("createAGM"+module_id).checked)
			$("#editAGM .modal-body #createAGM").prop("checked", true);
		else
			$("#editAGM .modal-body #createAGM").prop("checked", false);

		if(document.getElementById("updateAGM"+module_id).checked)
			$("#editAGM .modal-body #updateAGM").prop("checked", true);
		else
			$("#editAGM .modal-body #updateAGM").prop("checked", false);

		if(document.getElementById("deleteAGM"+module_id).checked)
			$("#editAGM .modal-body #deleteAGM").prop("checked", true);
		else
			$("#editAGM .modal-body #deleteAGM").prop("checked", false);

		if(document.getElementById("isExecAGM"+module_id).checked)
			$("#editAGM .modal-body #isExecAGM").prop("checked", true);
		else
			$("#editAGM .modal-body #isExecAGM").prop("checked", false);

		if(document.getElementById("showDataByStructure"+module_id).checked)
			$("#editAGM .modal-body #showDataByStructure").prop("checked", true);
		else
			$("#editAGM .modal-body #showDataByStructure").prop("checked", false);
			
		$("#editAGM").modal();
	}
</script>
@endsection
