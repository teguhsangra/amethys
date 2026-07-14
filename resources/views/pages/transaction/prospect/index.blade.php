@extends('layouts.app')
@section('title')
Rakomsis Prospect
@endsection


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group bmd-form-group">
                            <select class="selectpicker form-control" id="selection_employee" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" selected>Filter By Sales</option>
                                @foreach($employees as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty(Request::get('selection_employee'))){
                                            if(Request::get('selection_employee') == $detail->id){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group bmd-form-group">
                            <select id="selection_month" class="selectpicker form-control" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" selected>Filter By Month</option>
                                @foreach ($months as $item)
                                    @php
                                        $selected = '';
                                        if(!empty(Request::get('selection_month'))){
                                            if(Request::get('selection_month') == $item['number']){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{$item['number']}}" {{ $selected}}>{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="bmd-label-floating">Selected Year</label>
                            <input type="number" id="selection_year" class="form-control" @if(!empty(Request::get('selection_year'))) value="{{ Request::get('selection_year') }}" @else value="{{ date('Y') }}" @endif>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <a onclick="filter()" class="col-md-12 btn btn-info text-white">Filter</a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="clearFilter()" class="col-md-12 btn btn-warning text-white">Clear Filter</a>
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
                <h4 class="card-title">Prospect</h4>
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
                        @foreach($statuses as $no => $status)
                        <li class="nav-item">
                            <a class="nav-link @if($no == 0) active @endif" data-toggle="tab" href="#prospect_{{ $status->id }}" role="tablist">
                                {{ $status->action }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @endif

                </div>
                <br>
                <div class="tab-content tab-space">
                    @foreach($statuses as $no => $status)
                    <div class="tab-pane material-datatables table-responsive @if($no == 0) active @endif" id="prospect_{{ $status->id }}">
                        <table id="prospects_{{ $status->id }}-table" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Employee</th>
                                    <th>Referral</th>
                                    <th>Agent</th>
                                    <th>Customer</th>
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
@endsection

@section('js')
<script>
    var selection_employee = document.getElementById("selection_employee").value;
    var selection_month = document.getElementById("selection_month").value;
    var selection_year = document.getElementById("selection_year").value;

    $(function() {
        @foreach($statuses as $no => $status)
	    $('#prospects_{{ $status->id }}-table').DataTable({
	        processing: true,
	        serverSide: true,
	        ajax: '{{ url('datatables/'.$url) }}?selection_employee='+selection_employee+'&selection_month='+selection_month+'&selection_year='+selection_year+"&status_id={{$status->id}}",
	        columns: [
	            { data: 'code', name: 'code' },
	            { data: 'employee_name', name: 'employee_name' },
	            { data: 'referral_name', name: 'referral_name' },
	            { data: 'agent_name', name: 'agent_name' },
	            { data: 'customer_name', name: 'customer_name' },
	            { data: 'status_name', name: 'status_name' },
	            {
	            	sortable:false,
                    className: "td-actions text-right",
	            	"render" : function(row, data, full){
	            		var url = '{{ url($url) }}';
	            		var inquiry_url = '{{ url('inquiry') }}';
                        var status_name = full.status_name;
	            		var id = full.id;
	            		var employee_id = full.employee_id;
                        var return_html = '';
                        @if($a_g_and_module->read == 1)
	            		    return_html += '<a href='+url+'/'+id+' rel="tooltip"  class="btn btn-round btn-info" title="Detail"><i class="material-icons">zoom_in</i></a> ';
                        @endif
                        @if($a_g_and_module->update == 1)
                            if(status_name == "open" ){
                                return_html += '<a href='+url+'/'+id+'/edit/ rel="tooltip"  class="btn btn-round btn-primary" title="Edit"><i class="material-icons">edit</i></a> ';
                            }
                            if(status_name == "posted" ){
                                return_html += '<a href="'+inquiry_url+'/create/?prospect_id='+id+'" rel="tooltip"  class="btn btn-round btn-rose" title="Create inquiry">Create Inquiry</a> ';
                            }
                        @endif
                        @if($a_g_and_module->delete == 1)
                            if(status_name == "open" || status_name == "posted"){
                                if(employee_id == {{ $employee->id }}){
                                    return_html += '<a onclick="deleteAction('+id+')" rel="tooltip"  class="btn btn-round btn-danger" title="Delete"><i class="material-icons">remove</i></a>';
                                }
                            }
                        @endif
                        return return_html;
	            	}
	            }
	        ],
	        sorting:[[ 0, 'asc' ]]
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

    function filter(){
        var selection_employee = document.getElementById("selection_employee").value;
        var selection_month = document.getElementById("selection_month").value;
        var selection_year = document.getElementById("selection_year").value;

        if(selection_month != "" && selection_year == ""){
            alert("You have to select year to filtering");
        }else{
            window.location.href = "{{ $url }}?selection_employee="+selection_employee+"&selection_month="+selection_month+"&selection_year="+selection_year;
        }
    }

    function clearFilter(){
        window.location.href = "{{ $url }}";
    }
</script>
@endsection
