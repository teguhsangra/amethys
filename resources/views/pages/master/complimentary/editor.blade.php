@extends('layouts.app')
@section('title')
Rakomsis Complimentary - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Complimentary Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}      
                <div class="row">
                    <label class="col-sm-2 col-form-label">Room Category</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('room_category_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="room_category_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                @foreach($room_categories as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($complimentary)){
                                            if($complimentary->room_category_id == $detail->id){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('room_category_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($complimentary)) value="{{ $complimentary->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($complimentary)) value="{{ $complimentary->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Url</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('used_for_url') ? ' has-error' : '' }}">
                            <input type="text" name="used_for_url" class="form-control" @if(!empty($complimentary)) value="{{ $complimentary->used_for_url }}" @else value="{{ old('used_for_url') }}" @endif>
                            <label class="error">{{ $errors->first('used_for_url') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <label class="col-sm-2 col-form-label">Price Type</label>
                    <div class="col-sm-10 checkbox-radios" >
                        <div class="form-check" >
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="price_type" onclick="$('#price_type').val('hourly');" value="hourly" @if(!empty($complimentary)) @if($complimentary->price_type == 'hourly') checked @endif @endif> Hourly
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check" >
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="price_type" onclick="$('#price_type').val('daily');" value="daily" @if(!empty($complimentary)) @if($complimentary->price_type == 'daily') checked @endif @endif> Daily
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check" >
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="price_type" onclick="$('#price_type').val('monthly');" value="monthly" @if(!empty($complimentary)) @if($complimentary->price_type == 'monthly') checked @endif @else checked @endif> Monthly
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check" >
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="price_type" onclick="$('#price_type').val('yearly');" value="yearly" @if(!empty($complimentary)) @if($complimentary->price_type == 'yearly') checked @endif @else checked @endif> Yearly
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
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
