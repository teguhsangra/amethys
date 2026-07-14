@extends('layouts.app')
@section('title')
Rakomsis Contact - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Contact Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
            <input type="hidden" name="contact_id" @if(!empty($contact)) value="{{$contact->id}}" @endif>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($contact)) value="{{ $contact->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($contact)) value="{{ $contact->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">ID Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('id_number') ? ' has-error' : '' }}">
                            <input type="text" name="id_number" class="form-control" @if(!empty($contact)) value="{{ $contact->id_number }}" @else value="{{ old('id_number') }}" @endif>
                            <label class="error">{{ $errors->first('id_number') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" name="email" class="form-control" @if(!empty($contact)) value="{{ $contact->email }}" @else value="{{ old('email') }}" @endif>
                            <label class="error">{{ $errors->first('email') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="form-control" @if(!empty($contact)) value="{{ $contact->phone }}" @else value="{{ old('phone') }}" @endif>
                            <label class="error">{{ $errors->first('phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Mobile Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                            <input type="text" name="mobile_phone" class="form-control" @if(!empty($contact)) value="{{ $contact->mobile_phone }}" @else value="{{ old('mobile_phone') }}" @endif>
                            <label class="error">{{ $errors->first('mobile_phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Birth Date</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('birth_date') ? ' has-error' : '' }}">
                            <input type="text" name="birth_date" class="form-control datepicker" @if(!empty($contact)) value="{{ date('m/d/Y', strtotime($contact->birth_date)) }}" @endif>
                            <label class="error">{{ $errors->first('birth_date') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <textarea class="form-control" rows="9" name="address">@if(!empty($contact)){{ $contact->address }}@endif</textarea>
                            <label class="error">{{ $errors->first('address') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Position</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                            <input type="text" name="position" class="form-control" @if(!empty($contact)) value="{{ $contact->position }}" @else value="{{ old('position') }}" @endif>
                            <label class="error">{{ $errors->first('position') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Department</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                            <input type="text" name="department" class="form-control" @if(!empty($contact)) value="{{ $contact->department }}" @else value="{{ old('department') }}" @endif>
                            <label class="error">{{ $errors->first('department') }}</label>
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
