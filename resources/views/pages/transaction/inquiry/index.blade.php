@extends('layouts.app')
@section('title')
Rakomsis Inquiry
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Inquiry</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
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
                            <a class="nav-link @if($no == 0) active @endif" data-toggle="tab" href="#inquiries_{{ $status->id }}" role="tablist">
                                {{ $status->action }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content tab-space">
                    @foreach($statuses as $no => $status)
                    <div class="tab-pane material-datatables table-responsive @if($no == 0) active @endif" id="inquiries_{{ $status->id }}">
                        <table id="inquiries_{{ $status->id }}-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Total Price</th>
                                    <th>Start Date</th>
                                    <th>Created By</th>
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
<div class="modal fade modal-mini modal-primary" id="createProforma" tabindex="-1" role="dialog" aria-labelledby="CheckInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            {{ Form::open(array('url' => url(''), 'method' => 'POST', 'id' => 'createProformaForm', 'name' => 'createProformaForm')) }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want create proforma?</p>
                    <br>
                    <div class="form-group col-md-12">
                        <label class="control-label">Company</label>
                        <select class="selectpicker form-control" name="company_id" id="company_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true" required>
                            <option value="" disabled selected>Select Your Option</option>
                            @foreach($companies as $detail)
                                <option value="{{ $detail->id }}" >{{ $detail->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Bank Account</label>
                        <select class="selectpicker form-control" name="bank_account_id" id="bank_account_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true" required>
                            <option value="" disabled selected>Select Your Option</option>
                            @foreach($bank_accounts as $detail)
                                <option value="{{ $detail->id }}" >{{ $detail->bank_name }} : {{$detail->account_no}} / {{$detail->account_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Due date</label>
                        <input type="text" name="due_date" id="due_date" class="form-control datepicker" required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="control-label">Stamp Duty</label>
                        <input type="text" class="form-control text-right" id="format_stamp_duty" onchange="changeToCurrencyFormat('format_stamp_duty','stamp_duty');" value="0" />
                        <input type="hidden" class="form-control" name="stamp_duty" id="stamp_duty" value="0" />
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                    <button type="submit" class="btn btn-success btn-link">Yes
                        <div class="ripple-container"></div>
                    </button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
<div class="modal fade" id="customerContactModel" tabindex="-1" role="dialog" aria-labelledby="customerContactModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            {{ Form::open(array('url' => url(''), 'method' => 'PUT', 'id' => 'edit_customer_contact', 'name' => 'edit_customer_contact','enctype' => 'multipart/form-data')) }}
            <div class="modal-header">
                <h4 class="modal-title">Edit Customer & Contact</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="material-icons">clear</i>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="back_url" value="serviced_office">
                <ul class="nav nav-pills nav-pills-info" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#customer_data" role="tablist">
                            Customer Data
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#contact_data" role="tablist">
                            Contact Data
                        </a>
                    </li>
                </ul>
                <div class="tab-content tab-space">
                    <div class="tab-pane active" id="customer_data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Nature Of Business</label>
                                    <div class="form-group bmd-form-group">
                                        <select class="selectpicker form-control" name="nature_of_business_id" id="nature_of_business_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                            <option disabled selected>Select Your Option</option>
                                            @foreach($nature_of_businesses as $detail)
                                                <option value="{{ $detail->id }}" >{{ $detail->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" name="customer_name" id="customer_name">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Customer Type</label>
                                    <div class="checkbox-radios">
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="customer_type" value="COM" id="COM"> Company
                                                <span class="circle">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="customer_type" value="IND" id="IND"> Individu
                                                <span class="circle">
                                                    <span class="check"></span>
                                                </span>
                                            </label>
                                        </div>
                                        <label class="error">{{ $errors->first('customer_type') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Email</label>
                                    <input type="text" class="form-control" name="customer_email" id="customer_email">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Fax</label>
                                    <input type="text" class="form-control" name="customer_fax" id="customer_fax">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Phone</label>
                                    <input type="text" class="form-control" name="customer_phone" id="customer_phone">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Mobile Phone</label>
                                    <input type="text" class="form-control" name="customer_mobile_phone" id="customer_mobile_phone">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Address</label>
                                    <textarea name="customer_address" id="customer_address" class="form-control"></textarea>
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Country</label>
                                    <input type="text" class="form-control" name="customer_country" id="customer_country">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">City</label>
                                    <input type="text" class="form-control" name="customer_city" id="customer_city">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Zipcode</label>
                                    <input type="text" class="form-control" name="customer_zipcode" id="customer_zipcode">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Tax Number (NPWP)</label>
                                    <input type="text" class="form-control" name="customer_tax_number" id="customer_tax_number">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="contact_data">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group bmd-form-group is-filled">
                                    <select class="form-control" name="contact_honorific" id="contact_honorific">
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Miss">Miss</option>
                                    </select>
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" name="contact_name" id="contact_name">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Email</label>
                                    <input type="text" class="form-control" name="contact_email" id="contact_email">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">ID Number (KTP / Passport)</label>
                                    <input type="text" class="form-control" name="contact_id_number" id="contact_id_number">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Phone</label>
                                    <input type="text" class="form-control" name="contact_phone" id="contact_phone">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Mobile Phone</label>
                                    <input type="text" class="form-control" name="contact_mobile_phone" id="contact_mobile_phone">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group bmd-form-group is-filled">
                                    <label class="label-control">Birth Date</label>
                                    <input type="text" class="form-control datepicker" name="contact_birth_date" id="contact_birth_date">
                                    <span class="material-input"></span>
                                    <span class="material-input"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-link pull-left" data-dismiss="modal">Close</button>
                <button type="button" onclick="continueTransaction('posted')" class="btn btn-success btn-link pull-right">Save</button>
            </div>
             {{ Form::close() }}
        </div>
    </div>
</div>
<div class="modal fade modal-mini modal-primary" id="continueTransactionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
            </div>
            <div class="modal-body text-center">
                <p id="modal_label">Are you sure you want to do continue ?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-link" data-dismiss="modal">Never mind</button>
                <button type="button" class="btn btn-success btn-link" onclick="submitForm('edit_customer_contact')">Yes
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
        @foreach($statuses as $no => $status)
	    $('#inquiries_{{ $status->id }}-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url.'?status_id='.$status->id) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'type', name: 'type' },
	            { data: 'grand_total', name: 'grand_total' },
	            { data: 'start_date', name: 'start_date' },
	            { data: 'created_by', name: 'created_by' },
	            { data: 'status_name', name: 'status_name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
	            		var booking_url = "{{ url('redirect_inquiry_to_booking') }}";
                        var proforma_url = "{{ url('proforma/create') }}";
                        var status_name = full.status_name;
                        var status = full.status;
	            		var id = full.id;
                        var customer_id = full.customer_id;
                        var contact_id = full.contact_id;
	            		var employee_id = full.employee_id;
                        var return_html = '';
                        @if($a_g_and_module->read == 1)
	            		    return_html += '<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> ';
                        @endif
                        @if($a_g_and_module->update == 1)
                            if(status_name == "open"){
                                return_html += '&nbsp;<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                            }
                        @endif
                        @if($a_g_and_module->delete == 1)
                            if(status_name == "open" || status_name == "posted"){
                                return_html += '&nbsp;<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';
                            }
                        @endif
                        @if($a_g_and_module->isExec == 1)
                            if(status_name == "posted"){
                                return_html += '&nbsp;<a href="'+url+'/create/?inquiry_id='+id+'" rel="tooltip"  class="btn btn-round btn-rose" title="Use This As Reference">Make A Revision</a> ';
                                return_html += '&nbsp;<a href="'+booking_url+'?inquiry_id='+id+'" rel="tooltip"  class="btn btn-round btn-primary" title="Closing">Confirm</a> ';
                                if(status != 2 ){
                                    return_html += '&nbsp;<a onclick="createProforma('+id+')" rel="tooltip"  class="btn btn-round btn-success" title="Closing">Create proforma</a> ';
                                }


                            }
                        @endif
                        return_html += '&nbsp;<a onclick="editCustomerContact(\''+customer_id+'\', \''+contact_id+'\')"  rel="tooltip"  class="btn btn-round btn-default" title="Edit Customer"><i class="material-icons">account_circle</i></a>';
                        return return_html;
	            	}
	            }
	        ],
	        sorting:[[ 5, 'desc' ]]
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
    function createProforma(id){
        document.createProformaForm.action = "{{ url('inquiry/create_proforma') }}/"+id;
        $("#createProforma").modal();
    }

    function editCustomerContact(customer_id, contact_id){
        var link = "{{ url('inquiry/customer_contact') }}";
        var url = link+"/"+customer_id+"/"+contact_id;
        $.get(url, function (data){
            document.edit_customer_contact.action = "{{ url('inquiry') }}/customer_contact/"+customer_id+"/"+contact_id;
            document.getElementById('nature_of_business_id').value = data['customer']['nature_of_business_id'];
            $('#nature_of_business_id').selectpicker('refresh');

            document.getElementById('customer_name').value = data['customer']['name'];
            if(data['customer']['customer_type'] == "IND"){
                $("#IND").prop("checked", true);
            }else{
                $("#COM").prop("checked", true);
            }
            document.getElementById('customer_email').value = data['customer']['email'];
            document.getElementById('customer_fax').value = data['customer']['fax'];
            document.getElementById('customer_phone').value = data['customer']['phone'];
            document.getElementById('customer_mobile_phone').value = data['customer']['mobile_phone'];
            document.getElementById('customer_address').value = data['customer']['address'];
            document.getElementById('customer_country').value = data['customer']['country'];
            document.getElementById('customer_city').value = data['customer']['city'];
            document.getElementById('customer_zipcode').value = data['customer']['zipcode'];
            document.getElementById('customer_tax_number').value = data['customer']['tax_number'];

            document.getElementById('contact_honorific').value = data['contact']['honorific'];
            document.getElementById('contact_name').value = data['contact']['name'];
            document.getElementById('contact_email').value = data['contact']['email'];
            document.getElementById('contact_id_number').value = data['contact']['id_number'];
            document.getElementById('contact_phone').value = data['contact']['phone'];
            document.getElementById('contact_mobile_phone').value = data['contact']['mobile_phone'];
            document.getElementById('contact_birth_date').value = data['contact']['birth_date'];

            $("#customerContactModel").modal();
        });
    }

    function continueTransaction(){
        $("#continueTransactionModal").modal();
    }
</script>
@endsection
