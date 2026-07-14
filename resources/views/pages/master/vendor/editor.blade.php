@extends('layouts.app')
@section('title')
Rakomsis Vendor - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Vendor Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                
            <div class="row">
                    <label class="col-sm-2 col-form-label">Vendor Category</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('vendor_category_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="vendor_category_id[]" data-size="5" multiple="multiple" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                @foreach($vendor_categories as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($vendor)){
                                            foreach($vendor->vendor_category as $vendor_category){
                                                if($vendor_category->id == $detail->id){
                                                    $selected = 'selected';
                                                }
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('vendor_category_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($vendor)) value="{{ $vendor->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($vendor)) value="{{ $vendor->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" name="email" class="form-control" @if(!empty($vendor)) value="{{ $vendor->email }}" @else value="{{ old('email') }}" @endif>
                            <label class="error">{{ $errors->first('email') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="form-control" @if(!empty($vendor)) value="{{ $vendor->phone }}" @else value="{{ old('phone') }}" @endif>
                            <label class="error">{{ $errors->first('phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Mobile Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                            <input type="text" name="mobile_phone" class="form-control" @if(!empty($vendor)) value="{{ $vendor->mobile_phone }}" @else value="{{ old('mobile_phone') }}" @endif>
                            <label class="error">{{ $errors->first('mobile_phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <textarea class="form-control" rows="9" name="address">@if(!empty($vendor)){{ $vendor->address }}@endif</textarea>
                            <label class="error">{{ $errors->first('address') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <input type="text" name="country" class="form-control" @if(!empty($vendor)) value="{{ $vendor->country }}" @else value="{{ old('country') }}" @endif>
                            <label class="error">{{ $errors->first('country') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">City</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <input type="text" name="city" class="form-control" @if(!empty($vendor)) value="{{ $vendor->city }}" @else value="{{ old('city') }}" @endif>
                            <label class="error">{{ $errors->first('city') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Zipcode</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <input type="text" name="zipcode" class="form-control" @if(!empty($vendor)) value="{{ $vendor->zipcode }}" @else value="{{ old('zipcode') }}" @endif>
                            <label class="error">{{ $errors->first('zipcode') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Tax Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <input type="text" name="tax_number" class="form-control" @if(!empty($vendor_company)) value="{{ $vendor_company->tax_number }}" @else value="{{ old('tax_number') }}" @endif>
                            <label class="error">{{ $errors->first('tax_number') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bank Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                            <input type="text" name="bank_name" class="form-control" @if(!empty($vendor_company)) value="{{ $vendor_company->bank_name }}" @else value="{{ old('bank_name') }}" @endif>
                            <label class="error">{{ $errors->first('bank_name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bank Account</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bank_account') ? ' has-error' : '' }}">
                            <input type="text" name="bank_account" class="form-control" @if(!empty($vendor_company)) value="{{ $vendor_company->bank_account }}" @else value="{{ old('bank_account') }}" @endif>
                            <label class="error">{{ $errors->first('bank_account') }}</label>
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