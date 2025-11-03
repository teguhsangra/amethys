@extends('layouts.app')
@section('title')
Rakomsis Module - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Module Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($module)) value="{{ $module->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Icon</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                            <input type="text" name="icon" class="form-control" @if(!empty($module)) value="{{ $module->icon }}" @else value="{{ old('icon') }}" @endif>
                            <label class="error">{{ $errors->first('icon') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Link</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                            <input type="text" name="link" class="form-control" @if(!empty($module)) value="{{ $module->link }}" @else value="{{ old('link') }}" @endif>
                            <label class="error">{{ $errors->first('link') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Parent Module</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="parent_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                @foreach($modules as $detail)
                                    <option value="{{ $detail->id }}" @if(!empty($module)) @if($module->parent_id == $detail->id) selected @endif @endif>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('parent_id') }}</label>
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