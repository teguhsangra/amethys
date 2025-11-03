@extends('layouts.app')
@section('title')
Rakomsis User - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">User Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bio</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bio') ? ' has-error' : '' }}">
                            <textarea class="form-control" rows="9" name="bio">@if(!empty($location)){{ $location->bio }}@endif</textarea>
                            <label class="error">{{ $errors->first('bio') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <select class="selectpicker" name="type" data-size="5" data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                <option value="employee" @if(!empty($user)) @if($user->type == "employee") selected @else @endif @endif>Employee</option>
                                <option value="admin" @if(!empty($user)) @if($user->type == "admin") selected @else @endif @endif>Admin</option>
                            </select>
                            <label class="error">{{ $errors->first('type') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                            <select class="selectpicker" name="location_id[]" data-size="5" multiple="multiple" data-style="btn btn-primary btn-round" data-show-subtext="true" data-live-search="true">
                                @foreach($locations as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($user)){
	                                        foreach($user->location as $location){
	                                            if($location->id == $detail->id){
	                                                $selected = 'selected';
	                                            }
	                                        }
                                        }
                                        
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('location_id') }}</label>
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