@extends('layouts.app')
@section('title')
Rakomsis Customer - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Customer Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Nature Of Business</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('nature_of_business') ? ' has-error' : '' }}">
                            <input type="text" name="nature_of_business" class="form-control" @if(!empty($customer)) value="{{ $customer->nature_of_business }}" @else value="{{ old('nature_of_business') }}" @endif>
                            <label class="error">{{ $errors->first('nature_of_business') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Customer Type</label>
                    <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="customer_type" value="COM" @if(!empty($customer)) @if($customer->customer_type == "COM") checked @endif @else checked @endif> Company
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="customer_type" value="IND" @if(!empty($customer)) @if($customer->customer_type == "IND") checked @endif @endif> Individu
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <label class="error">{{ $errors->first('customer_type') }}</label>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($customer)) value="{{ $customer->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($customer)) value="{{ $customer->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" name="email" class="form-control" @if(!empty($customer)) value="{{ $customer->email }}" @else value="{{ old('email') }}" @endif>
                            <label class="error">{{ $errors->first('email') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="form-control" @if(!empty($customer)) value="{{ $customer->phone }}" @else value="{{ old('phone') }}" @endif>
                            <label class="error">{{ $errors->first('phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Mobile Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                            <input type="text" name="mobile_phone" class="form-control" @if(!empty($customer)) value="{{ $customer->mobile_phone }}" @else value="{{ old('mobile_phone') }}" @endif>
                            <label class="error">{{ $errors->first('mobile_phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Fax</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('fax') ? ' has-error' : '' }}">
                            <input type="text" name="fax" class="form-control" @if(!empty($customer)) value="{{ $customer->fax }}" @else value="{{ old('fax') }}" @endif>
                            <label class="error">{{ $errors->first('fax') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <textarea id="mytextarea" class="form-control" rows="9" name="address">@if(!empty($customer)){{ $customer->address }}@endif</textarea>
                            <label class="error">{{ $errors->first('address') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <input type="text" name="country" class="form-control" @if(!empty($customer)) value="{{ $customer->country }}" @else value="{{ old('country') }}" @endif>
                            <label class="error">{{ $errors->first('country') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">City</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <input type="text" name="city" class="form-control" @if(!empty($customer)) value="{{ $customer->city }}" @else value="{{ old('city') }}" @endif>
                            <label class="error">{{ $errors->first('city') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Zipcode</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <input type="text" name="zipcode" class="form-control" @if(!empty($customer)) value="{{ $customer->zipcode }}" @else value="{{ old('zipcode') }}" @endif>
                            <label class="error">{{ $errors->first('zipcode') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Tax Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <input type="text" name="tax_number" class="form-control" @if(!empty($customer)) value="{{ $customer->tax_number }}" @else value="{{ old('tax_number') }}" @endif>
                            <label class="error">{{ $errors->first('tax_number') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Virtual Account No</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('virtual_account_no') ? ' has-error' : '' }}">
                            <input type="text" name="virtual_account_no" class="form-control" @if(!empty($customer)) value="{{ $customer->virtual_account_no }}" @else value="{{ old('virtual_account_no') }}" @endif>
                            <label class="error">{{ $errors->first('virtual_account_no') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Virtual Account Bank</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('virtual_account_bank') ? ' has-error' : '' }}">
                            <input type="text" name="virtual_account_bank" class="form-control" @if(!empty($customer)) value="{{ $customer->virtual_account_bank }}" @else value="{{ old('virtual_account_bank') }}" @endif>
                            <label class="error">{{ $errors->first('virtual_account_bank') }}</label>
                        </div>
                    </div>
                </div>
                @if(!empty($customer))
                <div class="row">
                    <div class="col-md-6">
                        <label class="col-form-label">This Customer Contact</label>
                        <table id="customer_contact-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Contact Name</th>
                                    <th>Default Status</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label">Other Contact</label>
                        <table id="contacts-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Contact Code</th>
                                    <th>Contact Name</th>
                                    <th>Contact Email</th>
                                    <th>Contact Phone</th>
                                    <th class="disabled-sorting text-right">Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                @endif
            {{ Form::close() }}
            </div>
            <div class="card-footer">
                <a href="{{ url($url)}}" class="col-md-2 col-sm-offset-3 btn-lg btn btn-warning">Back</a>
                <button type="button" class="col-md-4 col-sm-offset-1 btn-lg btn btn-primary" data-toggle="modal" data-target="#accessGroupModal">{{ $button_name }}</button>

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
@if(!empty($customer))
    $(function() {
	    $('#customer_contact-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url.'/customer_contact/'.$customer->id) }}',
	        columns: [
	            { data: 'contact_name', name: 'contact_name' },
	            {
	            	sortable:false,
	            	"render" : function(row, data, full){
	            		var customer_id = full.customer_id;
	            		var contact_id = full.contact_id;
	            		var default_status = full.default_status;

                        var n_checked = '';
                        var y_checked = '';

                        if(default_status == 'Y'){
                            y_checked = 'selected';
                        }else{
                            n_checked = 'selected';
                        }
	            		return ''+
                            '<select class="form-control" id="default_status_'+customer_id+'_'+contact_id+'">'+
                                '<option value="N" '+n_checked+'>No</option>'+
                                '<option value="Y" '+y_checked+'>Yes</option>'+
                            '</select>'+
                        '';
	            	}
	            },
	            {
	            	sortable:false,
	            	"render" : function(row, data, full){
	            		var customer_id = full.customer_id;
	            		var contact_id = full.contact_id;
	            		var position = full.position;
                        if(position == null) position = '';
	            		return ''+
                            '<input type="text" id="position_'+customer_id+'_'+contact_id+'" value="'+position+'" class="form-control">'+
                        '';
	            	}
	            },
	            {
	            	sortable:false,
	            	"render" : function(row, data, full){
	            		var customer_id = full.customer_id;
	            		var contact_id = full.contact_id;
	            		var department = full.department;
                        if(department == null) department = '';
	            		return ''+
                            '<input type="text" id="department_'+customer_id+'_'+contact_id+'" value="'+department+'" class="form-control">'+
                        '';
	            	}
	            },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var edit_url = "{{ url('editCustomerContact') }}";
	            		var delete_url = "{{ url('deleteCustomerContact') }}";
	            		var customer_id = full.customer_id;
	            		var contact_id = full.contact_id;
	            		return ''+
	            		    '<a onclick="editCustomerContact('+"'"+edit_url+"'"+','+customer_id+','+contact_id+')" rel="tooltip"  class="btn btn-round btn-info" title="Edit"><i class="material-icons">edit</i></a>'+
	            		    '<a onclick="deleteCustomerContact('+"'"+delete_url+"'"+','+customer_id+','+contact_id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>'+
                        '';
	            	}
	            }
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });
	    $('#contacts-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url.'/contact/'.$customer->id) }}',
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'name', name: 'name' },
	            { data: 'email', name: 'email' },
	            { data: 'phone', name: 'phone' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = "{{ url('addCustomerContact') }}";
	            		var id = full.id;
                        var customer_id = {{ $customer->id }};
	            		return ''+
	            		    '<a onclick="addCustomerContact('+"'"+url+"'"+','+customer_id+','+id+')" rel="tooltip"  class="btn btn-round btn-success" title="Add"><i class="material-icons">add</i></a>'+
                        '';
	            	}
	            }
	        ],
	        sorting:[[ 0, 'asc' ]]
	    });
	});

    function deleteCustomerContact(link, customer_id, contact_id){
        var url = link+"/"+customer_id+"/"+contact_id;

        $.get(url, function (data){
            $('#customer_contact-table').DataTable().ajax.reload();
            $('#contacts-table').DataTable().ajax.reload();
        });
    }

    function addCustomerContact(link, customer_id, contact_id){
        var url = link+"/"+customer_id+"/"+contact_id;

        $.get(url, function (data){
            $('#customer_contact-table').DataTable().ajax.reload();
            $('#contacts-table').DataTable().ajax.reload();
        });
    }

    function editCustomerContact(link, customer_id, contact_id){
        var default_status = document.getElementById("default_status_"+customer_id+"_"+contact_id).value;
        var position = document.getElementById("position_"+customer_id+"_"+contact_id).value;
        var department = document.getElementById("department_"+customer_id+"_"+contact_id).value;
        var url = link+"/"+customer_id+"/"+contact_id+"?default_status="+default_status+"&position="+position+"&department="+department;

        $.get(url, function (data){
            $('#customer_contact-table').DataTable().ajax.reload();
            $('#contacts-table').DataTable().ajax.reload();
            alert('Data Edited');
        });
    }
@endif
</script>
@endsection