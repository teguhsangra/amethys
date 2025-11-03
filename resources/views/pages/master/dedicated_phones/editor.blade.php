@extends('layouts.app')
@section('title')
Rakomsis Dedicated Phone - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Dedicated Phone Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row" id="location">
                    <label class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                <select class="selectpicker form-control" name="location_id" id="location_id" onchange="getData()" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                    <option value="" disabled selected>Select Your Option</option>
                                    @foreach($locations as $detail)
                                        @php
                                            $selected = '';
                                            if(!empty($dedicated)){
                                                if($dedicated->location_id == $detail->id){
                                                    $selected = 'selected';
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
                <div class="row">
                    <label class="col-sm-2 col-form-label">Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('number') ? ' has-error' : '' }}">
                            <input type="text" name="number" class="form-control" @if(!empty($dedicated)) value="{{ $dedicated->number }}" @else value="{{ old('number') }}" @endif>
                            <label class="error">{{ $errors->first('number') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Type</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <select  name="type" id="type"  data-size="5" data-style="select-with-transition" data-show-subtext="true"
                                    class="selectpicker form-control ">

                                    <option value="">Please select one</option>
                                    <option value="VO" @if(!empty($dedicated)) @if($dedicated->type == "VO") selected @endif @endif>Virtual Office</option>
                                    <option value="SO" @if(!empty($dedicated)) @if($dedicated->type == "SO") selected @endif @endif>Serviced Office</option>
                                </select>
                            <label class="error">{{ $errors->first('type') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Availability</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('availability') ? ' has-error' : '' }}">
                            <select name="availability" id="availability"  data-size="5" data-style="select-with-transition" data-show-subtext="true"
                                    class="selectpicker form-control ">

                                    <option value="">Please select one</option>
                                    <option value="dedicated" @if(!empty($dedicated)) @if($dedicated->availability == "dedicated") selected @endif @endif>Dedicated</option>
                                    <option value="global" @if(!empty($dedicated)) @if($dedicated->availability == "global") selected @endif @endif>Global</option>
                                </select>
                            <label class="error">{{ $errors->first('availability') }}</label>
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
