@extends('layouts.app')
@section('title')
Rakomsis Photo Room - {{ $room->room_number }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Photo Room</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Room
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">{{ $room->name }}'s Photo</h4>
            </div>
            <div class="card-body">
                <div class="material-datatables">
                    <table id="rooms-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Default Status</th>
                                <th class="disabled-sorting text-right">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header card-header-default card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">New Room Photo</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                        <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                            <div class="fileupload-new thumbnail">
                                <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="with_holding_tax" width="150">
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="width:150px;height:150px;"></div>
                            <div>
                                <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                    <input type="file" name="photo" />
                                </span>
                                <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i> Remove
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            {{ Form::close() }}
            </div>
            <div class="card-footer">
                <button type="button" class="col-md-12 btn-block btn-lg btn btn-primary" data-toggle="modal" data-target="#accessGroupModal">{{ $button_name }}</button>

                <div class="modal fade modal-mini modal-primary" id="accessGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-small">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to do continue ?</p>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                                <button type="button" class="btn btn-success btn-link" onclick="submitForm('{{ $form_id }}')">Yes
                                    <div class="ripple-container"></div>
                                </button>
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
	    $('#rooms-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url.'/photo/'.$room->id) }}',
	        columns: [
	            {
	            	sortable:false,
	            	"render" : function(row, data, full){
	            		var photo = full.photo;
                        var url = "{{ url('') }}"+photo;
	            		return ''+'<a href="'+url+'" target="_blank"><img class="img img-responsive" width="200" src="'+url+'"></a>'+
                        '';
	            	}
	            },
	            {
	            	sortable:false,
	            	"render" : function(row, data, full){
	            		var url = "{{ url('room/photo/change_status') }}";
	            		var id = full.id;
	            		var default_status = full.default;

                        var n_checked = '';
                        var y_checked = '';

                        if(default_status == 'Y'){
                            y_checked = 'selected';
                        }else{
                            n_checked = 'selected';
                        }
	            		return ''+
                            '<select class="form-control" id="default_status_'+id+'" onchange="changeStatus('+"'"+url+"'"+','+id+',this.value)">'+
                                '<option value="N" '+n_checked+'>No</option>'+
                                '<option value="Y" '+y_checked+'>Yes</option>'+
                            '</select>'+
                        '';
	            	}
	            },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var id = full.id;
	            		return ''+'<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>'+
                        '';
	            	}
	            }
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });
	});

    function changeStatus(link, id, default_status){
        var url = link+"/"+id+"?default_status="+default_status;

        $.get(url, function (data){
            $('#rooms-table').DataTable().ajax.reload();
        });
    }

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/photo/"+id;
        $("#deleteModal").modal();
    }
</script>
@endsection