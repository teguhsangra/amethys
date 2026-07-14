@extends('layouts.app')
@section('title')
Rakomsis Access Card
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Access Card</h4>
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

                            <li class="nav-item">
                                <a class="nav-link  active" data-toggle="tab" href="#activation" role="tablist">
                                    Active
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#deactivation" role="tablist">
                                    Non Active
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#missing" role="tablist">
                                    Lost
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " data-toggle="tab" href="#defective" role="tablist">
                                    Defective
                                </a>
                            </li>

                        </ul>
                    @endif
                    </div>
                    <div class="tab-content tab-space">

                        <div class="tab-pane material-datatables table-responsive active" id="activation">
                            <table id="activation-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Remarks</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive " id="deactivation">
                            <table id="deactivation-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Remarks</th>
                                        <th class="disabled-sorting text-right">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive " id="missing">
                            <table id="missing-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="tab-pane material-datatables table-responsive " id="defective">
                            <table id="defective-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Customer</th>
                                        <th>Contact</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="updateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Confirmation box</h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(array('url' => url(''), 'method' => 'PUT', 'id' => 'updateForm', 'name' => 'updateForm')) }}
                        <p id="modalRenewMessage">Modal Message</p>
                        <br>
                        <input type="hidden" name="activity" id="activity">

                        <div class="form-group col-md-12">
                            <label class="control-label">Remarks</label>
                            <textarea class="form-control" name="remarks" required></textarea>
                        </div>
                        {{ Form::close() }}
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                    <button type="submit" class="btn btn-success btn-link" onclick="submitForm('updateForm')">Yes
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
$(function() {
    $('#activation-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url('datatables/'.$url) }}'+'?activity=activation&is_lost=N&is_defective=N',
        columns: [
                { data: 'location', name: 'location' },
	            { data: 'customer', name: 'customer' },
	            { data: 'contact', name: 'contact' },
	            { data: 'code', name: 'code' },
                { data: 'type', name: 'type' },
                { data: 'remarks', name: 'remarks' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
                        var url = '{{ url($url) }}';
	            		var id = full.id;
                        var return_html = '';
                        return_html += '<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                        return_html +=  '<a onclick=updateAccess('+id+',"deactivation") class="btn btn-md btn-round btn-danger" title="deactivation"><i class="fa fa-times"></i></a><br>';
                        return_html +=  '<a onclick=updateAccess('+id+',"missing") class="btn btn-md btn-round  btn-warning" title="missing"><i class="fa fa-trash"></i></a><br>';
                        return return_html;
	            	}
	            }

        ],
        sorting:[[ 5, 'desc' ]]
    });

    $('#deactivation-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url('datatables/'.$url) }}'+'?activity=deactivation&is_lost=N&is_defective=N',
        columns: [
                { data: 'location', name: 'location' },
	            { data: 'customer', name: 'customer' },
	            { data: 'contact', name: 'contact' },
	            { data: 'code', name: 'code' },
                { data: 'type', name: 'type' },
                { data: 'remarks', name: 'remarks' },
                {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
                        var url = '{{ url($url) }}';
	            		var id = full.id;
                        var return_html = '';
                        return_html += '<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                        return return_html;
	            	}
	            }

        ],
        sorting:[[ 0, 'desc' ]]
    });
    $('#missing-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url('datatables/'.$url) }}'+'?activity=missing&is_lost=Y&is_defective=N',
        columns: [
                { data: 'location', name: 'location' },
	            { data: 'customer', name: 'customer' },
	            { data: 'contact', name: 'contact' },
	            { data: 'code', name: 'code' },
                { data: 'type', name: 'type' },
                { data: 'remarks', name: 'remarks' },

        ],
        sorting:[[ 0, 'desc' ]]
    });
    $('#defective-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ url('datatables/'.$url) }}'+'?activity=defective&is_lost=N&is_defective=Y',
        columns: [
                { data: 'location', name: 'location' },
	            { data: 'customer', name: 'customer' },
	            { data: 'contact', name: 'contact' },
	            { data: 'code', name: 'code' },
                { data: 'type', name: 'type' },
                { data: 'remarks', name: 'remarks' },

        ],
        sorting:[[ 0, 'desc' ]]
    });



});
function updateAccess(accessID, activity)
{
    var message = '';
    if(activity == "deactivation"){
        message = "Are you sure, do you want to deactivation this access card ?";
    }else{
        message = "Are you sure, do you want to missing this access card ?";
    }
    document.getElementById("modalRenewMessage").innerHTML = message;
    document.getElementById("activity").value = activity;
    document.updateForm.action = "{{ url($url) }}/"+accessID;
    $("#updateModal").modal();
}
</script>
@endsection
