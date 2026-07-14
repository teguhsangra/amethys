@extends('layouts.app')
@section('title')
Rakomsis Package - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Package Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="location_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true" onchange="get_by_location(this.value)">
                                <option disabled selected>Select Your Option</option>
                                @foreach($locations as $detail)
                                    @php
                                        $selected = '';
                                        if($detail->id == Request::get('location_id')){
                                            $selected = 'selected';
                                        }

                                        if(!empty($package)){
                                            if($package->location_id == $detail->id){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('location_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Room</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('room_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="room_id[]" id="room_id" data-size="5" multiple="multiple" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                @foreach($rooms as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($package)){
                                            foreach($package->room as $room){
                                                if($room->id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->room_number }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('room_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Product</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="product_id[]" data-size="5" multiple="multiple" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                @foreach($products as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($package)){
                                            foreach($package->product as $product){
                                                if($product->id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('product_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($package)) value="{{ $package->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($package)) value="{{ $package->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Price Type</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('price_type') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="price_type" data-size="6" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                <option value="yearly" @if(!empty($package)) @if($package->price_type == "yearly") selected @endif @endif>Yearly</option>
                                <option value="monthly" @if(!empty($package)) @if($package->price_type == "monthly") selected @endif @endif>Monthly</option>
                                <option value="daily" @if(!empty($package)) @if($package->price_type == "daily") selected @endif @endif>Daily</option>
                                <option value="hourly" @if(!empty($package)) @if($package->price_type == "hourly") selected @endif @endif>Hourly</option>
                                <option value="single" @if(!empty($package)) @if($package->price_type == "single") selected @endif @endif>Single</option>
                            </select>
                            <label class="error">{{ $errors->first('price_type') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                            <input type="text" id="format_price" name="format_price" class="form-control" onchange="changeToCurrencyFormat('format_price','price')" @if(!empty($package)) value="{{ number_format($package->price, 0,',','.') }}" @else value="{{ number_format(old('price'), 0,',','.') }}" @endif>
                            <input type="hidden" id="price" name="price" @if(!empty($package)) value="{{ $package->price }}" @else value="{{ old('price') }}" @endif>
                            <label class="error">{{ $errors->first('price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Total Term</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('total_term') ? ' has-error' : '' }}">
                            <input type="number" name="total_term" class="form-control" @if(!empty($package)) value="{{ $package->total_term }}" @else value="{{ old('total_term') }}" @endif>
                            <label class="error">{{ $errors->first('total_term') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Main Status</label>
                    <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="main_status" value="N" @if(!empty($package)) @if($package->main_status == "N") checked @endif @else checked @endif> N
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="main_status" value="Y" @if(!empty($package)) @if($package->main_status == "Y") checked @endif @endif> Y
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Quantity Status</label>
                    <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="quantity_status" value="N" @if(!empty($package)) @if($package->quantity_status == "N") checked @endif @else checked @endif> N
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="quantity_status" value="Y" @if(!empty($package)) @if($package->quantity_status == "Y") checked @endif @endif> Y
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Service Charge Status</label>
                    <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="has_service_charge" value="N" @if(!empty($package)) @if($package->has_service_charge == "N") checked @endif @else checked @endif> N
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="has_service_charge" value="Y" @if(!empty($package)) @if($package->has_service_charge == "Y") checked @endif @endif> Y
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('desc') ? ' has-error' : '' }}">
                            <textarea id="mytextarea" class="form-control" rows="5" name="desc" placeholder="desc..." @if(!empty(Request::get('action_status'))) readonly  @endif>@if(!empty($package)){{ $package->desc }}@endif</textarea>
                            <label class="error">{{ $errors->first('desc') }}</label>
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

@section('js')
<script>
    function get_by_location(location_id){
        @if(!empty($product))
        window.location.href = "{{ url($url.'/'.$package->id.'/edit?location_id=') }}"+location_id;
        @else
        window.location.href = "{{ url($url.'/create?location_id=') }}"+location_id;
        @endif
    }
</script>
@endsection
