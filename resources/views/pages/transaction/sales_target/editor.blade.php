@extends('layouts.app')
@section('title')
Rakomsis Sales Target - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">
                    Sales Target Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Sales Target
                    </a>
                </h4>
            </div>
            <div class="card-body">
                {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }} 
                    <input type="hidden" name="status_name" id="status_name">
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Sales</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{$sales_target->employee->name}}" readonly>
                                    <input type="hidden" name="employee_id" value="{{$sales_target->employee_id}}">
                                @else
                                <select class="selectpicker form-control" name="employee_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">      
                                    <option disabled selected>Select Your Option</option>
                                    @foreach($employees as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($sales_target)){
                                                if($sales_target->employee_id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('employee_id') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Year</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                                <input type="number" name="year" class="form-control" @if(!empty($sales_target)) value="{{ $sales_target->year }}" @else value="{{ date('Y') }}" @endif  @if(!empty(Request::get('action_status'))) readonly @endif>
                                <label class="error">{{ $errors->first('year') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Month</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('month') ? ' has-error' : '' }}">
                                @if(!empty(Request::get('action_status')))
                                    <input type="text" class="form-control" value="{{ date('F', strtotime(date('Y').'-'.$sales_target->month.'-01')) }}" readonly>
                                    <input type="hidden" name="month" value="{{$sales_target->month}}">
                                @else
                                <select class="selectpicker form-control" name="month" data-size="6" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                    <option disabled selected>Select Your Option</option>
                                    @foreach($months as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($sales_target)){
                                                if($sales_target->month == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label class="error">{{ $errors->first('month') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Total Target</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('total_target') ? ' has-error' : '' }}">
                                <input type="text" id="format_total_target" name="format_total_target" class="form-control" onchange="changeToCurrencyFormat('format_total_target','total_target')" @if(!empty($sales_target)) value="{{ number_format($sales_target->total_target, 0,',','.') }}" @else value="{{ number_format(old('total_target'), 0,',','.') }}" @endif  @if(!empty(Request::get('action_status'))) readonly @endif>
                                <input type="hidden" id="total_target" name="total_target" @if(!empty($sales_target)) value="{{ $sales_target->total_target }}" @else value="0" @endif>
                                <label class="error">{{ $errors->first('total_target') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Total Target Virtual Office</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('total_target_vo') ? ' has-error' : '' }}">
                                <input type="text" id="format_total_target_vo" name="format_total_target_vo" class="form-control" onchange="changeToCurrencyFormat('format_total_target_vo','total_target_vo')" @if(!empty($sales_target)) value="{{ number_format($sales_target->total_target_vo, 0,',','.') }}" @else value="{{ number_format(old('total_target_vo'), 0,',','.') }}" @endif  @if(!empty(Request::get('action_status'))) readonly @endif>
                                <input type="hidden" id="total_target_vo" name="total_target_vo" @if(!empty($sales_target)) value="{{ $sales_target->total_target_vo }}" @else value="0" @endif>
                                <label class="error">{{ $errors->first('total_target_vo') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Total Target Serviced Office</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('total_target_so') ? ' has-error' : '' }}">
                                <input type="text" id="format_total_target_so" name="format_total_target_so" class="form-control" onchange="changeToCurrencyFormat('format_total_target_so','total_target_so')" @if(!empty($sales_target)) value="{{ number_format($sales_target->total_target_so, 0,',','.') }}" @else value="{{ number_format(old('total_target_so'), 0,',','.') }}" @endif  @if(!empty(Request::get('action_status'))) readonly @endif>
                                <input type="hidden" id="total_target_so" name="total_target_so" @if(!empty($sales_target)) value="{{ $sales_target->total_target_so }}" @else value="0" @endif>
                                <label class="error">{{ $errors->first('total_target_so') }}</label>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <div class="card-footer">
                @if(!empty(Request::get('action_status')))
                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->update == 1 && $a_g_and_module->isExec == 1)
                    <a onclick="continueTransaction('complete')" class="col-md-12 btn-lg btn btn-success">Complete</a>
                    @endif
                @else
                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1)
                    <a onclick="continueTransaction('open')" class="col-md-3 col-sm-offset-3 btn-lg btn btn-info">Save To Draft</a>
                    @endif


                    @if($a_g_and_module->read == 1 && $a_g_and_module->create == 1 && $a_g_and_module->isExec == 1)
                    <a onclick="continueTransaction('posted')" class="col-md-4 col-sm-offset-1 btn-lg btn btn-default">Posting</a>
                    @endif
                @endif

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
    function continueTransaction(status_name){
        if(status_name == "open"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to save to draft this form, and you can edit this form further. <br> Are you sure want to continue ?";
        }else if(status_name == "posted"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to posting this form, and you can't edit this form anymore. <br> Are you sure want to continue ?";
        }else if(status_name == "complete"){
            document.getElementById("status_name").value = status_name;
            document.getElementById("modal_label").innerHTML = "You are going to complete this form. <br> Are you sure want to continue ?";
        }else{

        }
        $("#continueTransactionModal").modal();
    }

    function deleteAction(id){
        document.deleteForm.action = "{{ url($url) }}/"+id;
        $("#deleteModal").modal();
    }
</script>
@endsection