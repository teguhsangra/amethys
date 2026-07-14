@extends('layouts.app')
@section('title')
    Rakomsis Dedicated Phones
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Filter</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group bmd-form-group">
                            <label class="col-sm-2"><b>Location</b></label><br>
                            <select class="selectpicker form-control" name="location_id" id="location_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Select Your Option</option>
                                @foreach ($location as $item)
                                    @php
                                        $selected = '';
                                        if(!empty($location_id)){
                                            if($location_id == $item->id){
                                                $selected = 'selected';
                                            }
                                        }

                                    @endphp
                                    <option value="{{$item->id}}" {{$selected}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="toolbar">
                            <button class="btn btn-success" id="filter">Filter </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Dedicated Phones</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                @if($a_g_and_module->create == 1)
                    <a href="{{ url($url) }}/create" class="btn btn-success btn-round">
                        <i class="fa fa-plus"></i>
                    </a>
                @endif
                </div>
                <div class="material-datatables table-responsive">
                    <table id="areas-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Number</th>
                                <th>Type</th>
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
	    $('#areas-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}'+'?location_id={{$location_id}}',
	        columns: [
                { data: 'location', name: 'location' },
	            { data: 'number', name: 'number' },
	            { data: 'type', name: 'type' },
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

        $("#filter").on('click', function(){
        var location_id = $("#location_id").val();

        window.location.href = '{{ url('dedicated_phone')}}'+"?location_id="+location_id;
    });
	});

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
</script>
@endsection
