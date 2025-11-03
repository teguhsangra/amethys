@extends('layouts.app')
@section('title')
Rakomsis Bank Account - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Bank Account Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Account Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('account_no') ? ' has-error' : '' }}">
                            <input type="text" name="account_no" class="form-control" @if(!empty($bank_account)) value="{{ $bank_account->account_no }}" @else value="{{ old('account_no') }}" @endif>
                            <label class="error">{{ $errors->first('account_no') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Account Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('account_name') ? ' has-error' : '' }}">
                            <input type="text" name="account_name" class="form-control" @if(!empty($bank_account)) value="{{ $bank_account->account_name }}" @else value="{{ old('account_name') }}" @endif>
                            <label class="error">{{ $errors->first('account_name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bank Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                            <input type="text" name="bank_name" class="form-control" @if(!empty($bank_account)) value="{{ $bank_account->bank_name }}" @else value="{{ old('bank_name') }}" @endif>
                            <label class="error">{{ $errors->first('bank_name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Branch Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('branch_code') ? ' has-error' : '' }}">
                            <input type="text" name="branch_code" class="form-control" @if(!empty($bank_account)) value="{{ $bank_account->branch_code }}" @else value="{{ old('branch_code') }}" @endif>
                            <label class="error">{{ $errors->first('branch_code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Swift Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('swift_code') ? ' has-error' : '' }}">
                            <input type="text" name="swift_code" class="form-control" @if(!empty($bank_account)) value="{{ $bank_account->swift_code }}" @else value="{{ old('swift_code') }}" @endif>
                            <label class="error">{{ $errors->first('swift_code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Currency Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('currency_code') ? ' has-error' : '' }}">
                            <input type="text" name="currency_code" class="form-control" @if(!empty($bank_account)) value="{{ $bank_account->currency_code }}" @else value="{{ old('currency_code') }}" @endif>
                            <label class="error">{{ $errors->first('currency_code') }}</label>
                        </div>
                    </div>
                </div>
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