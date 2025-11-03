@extends('layouts.app')
@section('title')
Rakomsis Dedicated Phone
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Dedicated Phone</h4>
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

                            <a class="nav-link active" data-toggle="tab" href="#deactivation" role="tablist">
                                Non Active
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link  " data-toggle="tab" href="#activation" role="tablist">
                                Active
                            </a>
                        </li>

                    </ul>
                @endif
                </div>
                <div class="tab-content tab-space">

                    <div class="tab-pane material-datatables table-responsive active" id="deactivation">
                        <table id="deactivation-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Booking</th>
                                    <th>Customer</th>
                                    <th>Number</th>
                                    <th>Type</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="tab-pane material-datatables table-responsive " id="activation">
                        <table id="activation-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Booking</th>
                                    <th>Customer</th>
                                    <th>Number</th>
                                    <th>Type</th>
                                    <th>Extention No</th>
                                    <th>Forward To</th>
                                    <th>Display Name</th>
                                    <th class="disabled-sorting text-right">Actions</th>
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
                    <p id="modalDeactivate">Modal Message</p>
                    <br>
                    <input type="hidden" name="activity" value="deactivation">

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
<div id="activeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation box</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => url(''), 'method' => 'POST', 'id' => 'activeForms', 'name' => 'activeForm')) }}
                    <p id="modalActive">Modal Message</p>
                    <br>
                    <input type="hidden" name="dedicated_phone_id" id="dedicated_phone_id">
                    <input type="hidden" name="customer_id" id="customer_id">
                    <input type="hidden" name="booking_id" id="booking_id">
                    <input type="hidden" name="activity" value="activation">

                    <div class="form-group col-md-12">
                        <label class="control-label">Extention No</label>
                        <textarea class="form-control" rows="5" name="extension_no" id="remarks" placeholder="Ext No..."></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Forward To</label>
                        <textarea class="form-control" rows="5" name="forward_to" id="remarks" placeholder="Forward No..."></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Display Name</label>
                        <textarea class="form-control" rows="5" name="display_name" id="remarks" placeholder="Display Name..."></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" required></textarea>
                    </div>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                <button type="submit" class="btn btn-success btn-link" onclick="submitForm('activeForms')">Yes
                    <div class="ripple-container"></div>
                </button>
            </div>
        </div>
    </div>
</div>


<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation box</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('url' => url(''), 'method' => 'PUT', 'id' => 'editForms', 'name' => 'editForms')) }}
                    <input type="hidden" name="edit" value="edit">
                    <p id="modalEdit">Modal Message</p>
                    <br>

                    <div class="form-group col-md-12">
                        <label class="control-label">Extention No</label>
                        <textarea class="form-control" rows="5" name="extension_no" id="extension_no" placeholder="Ext No..."></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Forward To</label>
                        <textarea class="form-control" rows="5" name="forward_to" id="forward_to" placeholder="Forward No..."></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Display Name</label>
                        <textarea class="form-control" rows="5" name="display_name" id="display_name" placeholder="Display Name..."></textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label class="control-label">Remarks</label>
                        <textarea class="form-control" name="remarks" id="remarks" required></textarea>
                    </div>
                    {{ Form::close() }}
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                <button type="submit" class="btn btn-success btn-link" onclick="submitForm('editForms')">Yes
                    <div class="ripple-container"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="viewModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Confirmation box</h4>
            </div>
            <div class="modal-body">

                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Number</td>
                                <td id="number"></td>
                            </tr>
                            <tr>
                                <td>Extension No</td>
                                <td id="extension_nos"></td>
                            </tr>
                            <tr>
                                <td>Forward To</td>
                                <td id="forward_tos"></td>
                            </tr>
                            <tr>
                                <td>Display Name</td>
                                <td id="display_names"></td>
                            </tr>
                            <tr>
                                <td>Remarks</td>
                                <td id="remarkss"></td>
                            </tr>
                        </tbody>
                    </table>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
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
            ajax: '{{ url('datatables/'.$url) }}'+'?activity=activation',
            columns: [
                { data: 'location_name', name: 'location_name' },
                { data: 'booking_code', name: 'booking_code' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'number', name: 'number' },
                { data: 'type', name: 'type' },
                { data: 'extension_no', name: 'extension_no' },
                { data: 'forward_to', name: 'forward_to' },
                { data: 'display_name', name: 'display_name' },
                {
                    sortable:false,
                    className: "td-actions text-right",
                    "render" : function(row, data, full){
                        var dedicated_phone_id = full.dedicated_phone_id;
                        var extension_no = full.extension_no;
                        var number = full.number;
                        var forward_to = full.forward_to;
                        var display_name = full.display_name;
                        var remarks = full.remarks;
                        var return_html = '';
                        return_html +=  '<a onclick=deactivate('+dedicated_phone_id+') class="btn btn-md btn-round btn-danger" title="Deactivate"><i class="fa fa-times"></i></a><br>';
                        return_html +=  '<a onclick=editForm('+dedicated_phone_id+') class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a><br>';
                        return_html +=  '<a onclick=viewForm('+dedicated_phone_id+') class="btn btn-round btn-info" title="View"><i class="material-icons">zoom_in</i></a><br>';
                        return return_html;
                    }
                }


            ],
            sorting:[[ 5, 'desc' ]]
        });

        $('#deactivation-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ url('datatables/'.$url) }}'+'?activity=deactivation',
            columns: [
                { data: 'location_name', name: 'location_name' },
                { data: 'booking_code', name: 'booking_code' },
                { data: 'customer_name', name: 'customer_name' },
                { data: 'number', name: 'number' },
                { data: 'type', name: 'type' },
                {
                    sortable:false,
                    className: "td-actions text-right",
                    "render" : function(row, data, full){
                        var dedicated_phone_id = full.dedicated_phone_id;
                        var customer_id = full.customer_id;
                        var booking_id = full.booking_id;
                        var return_html = '';
                        return_html +=  '<a onclick=activation('+dedicated_phone_id+','+customer_id+','+booking_id+') class="btn btn-md btn-round btn-success" title="Activation"><i class="fa fa-check"></i>Active</a><br>';
                        return return_html;
                    }
                }
            ],
            sorting:[[ 0, 'desc' ]]
        });
    });
    function deactivate(dedicated_phone_id)
    {
        var message = "Are you sure, do you want to deactivate this phone number ?";

        document.getElementById("modalDeactivate").innerHTML = message;
        document.updateForm.action = "{{ url($url) }}/"+dedicated_phone_id;

        $("#updateModal").modal();
    }

    function activation(dedicated_phone_id,customer_id,booking_id)
    {
        var message = "Are you sure, do you want to activation this phone number ?";
        document.getElementById("dedicated_phone_id").value = dedicated_phone_id;
        document.getElementById("customer_id").value = customer_id;
        document.getElementById("booking_id").value = booking_id;
        document.getElementById("modalActive").innerHTML = message;
        document.activeForm.action = "dedicated_phone_transaction";
        $("#activeModal").modal();
    }

    function editForm(dedicated_phone_id)
    {
        var message = "Are you sure, do you want to edit this phone number ?";

        var link = "{{ url('dedicated_phone_transaction/getDedicated') }}";
        var url = link+"/"+dedicated_phone_id;
        $.get(url, function (data){
            document.getElementById("extension_no").value = data['extension_no'];
            document.getElementById("forward_to").value = data['forward_to'];
            document.getElementById("display_name").value = data['display_name'];
            document.getElementById("remarks").value = data['remarks'];

            document.getElementById("modalEdit").innerHTML = message;
            document.editForms.action = "{{ url($url) }}/"+dedicated_phone_id;

            $("#editModal").modal();
        });




    }

    function viewForm(dedicated_phone_id)
    {
        var link = "{{ url('dedicated_phone_transaction/getDedicated') }}";
        var url = link+"/"+dedicated_phone_id;
        $.get(url, function (data){
            document.getElementById("number").innerHTML = data['number'];
            document.getElementById("extension_nos").innerHTML = data['extension_no'];
            document.getElementById("forward_tos").innerHTML = data['forward_to'];
            document.getElementById("display_names").innerHTML = data['display_name'];
            document.getElementById("remarkss").innerHTML = data['remarks'];

            $("#viewModal").modal();
        });



    }
</script>
@endsection
