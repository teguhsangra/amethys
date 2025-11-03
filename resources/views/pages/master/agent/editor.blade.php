@extends('layouts.app')
@section('title')
Rakomsis Business Partner - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Business Partner Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Business Partner Company</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('agent_company_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="agent_company_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                @foreach($agent_companies as $detail)
                                    <option value="{{ $detail->id }}" @if(!empty($agent)) @if($agent->agent_company_id == $detail->id) selected @endif @endif>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('agent_company_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($agent)) value="{{ $agent->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input type="text" name="name" class="form-control" @if(!empty($agent)) value="{{ $agent->name }}" @else value="{{ old('name') }}" @endif>
                            <label class="error">{{ $errors->first('name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input type="text" name="email" class="form-control" @if(!empty($agent)) value="{{ $agent->email }}" @else value="{{ old('email') }}" @endif>
                            <label class="error">{{ $errors->first('email') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <input type="text" name="phone" class="form-control" @if(!empty($agent)) value="{{ $agent->phone }}" @else value="{{ old('phone') }}" @endif>
                            <label class="error">{{ $errors->first('phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Mobile Phone</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('mobile_phone') ? ' has-error' : '' }}">
                            <input type="text" name="mobile_phone" class="form-control" @if(!empty($agent)) value="{{ $agent->mobile_phone }}" @else value="{{ old('mobile_phone') }}" @endif>
                            <label class="error">{{ $errors->first('mobile_phone') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <textarea class="form-control" rows="9" name="address">@if(!empty($agent)){{ $agent->address }}@endif</textarea>
                            <label class="error">{{ $errors->first('address') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <input type="text" name="country" class="form-control" @if(!empty($agent)) value="{{ $agent->country }}" @else value="{{ old('country') }}" @endif>
                            <label class="error">{{ $errors->first('country') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">City</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                            <input type="text" name="city" class="form-control" @if(!empty($agent)) value="{{ $agent->city }}" @else value="{{ old('city') }}" @endif>
                            <label class="error">{{ $errors->first('city') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Zipcode</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                            <input type="text" name="zipcode" class="form-control" @if(!empty($agent)) value="{{ $agent->zipcode }}" @else value="{{ old('zipcode') }}" @endif>
                            <label class="error">{{ $errors->first('zipcode') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Tax Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('tax_number') ? ' has-error' : '' }}">
                            <input type="text" name="tax_number" class="form-control" @if(!empty($agent)) value="{{ $agent->tax_number }}" @else value="{{ old('tax_number') }}" @endif>
                            <label class="error">{{ $errors->first('tax_number') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bank Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bank_name') ? ' has-error' : '' }}">
                            <input type="text" name="bank_name" class="form-control" @if(!empty($agent)) value="{{ $agent->bank_name }}" @else value="{{ old('bank_name') }}" @endif>
                            <label class="error">{{ $errors->first('bank_name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Bank Account</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('bank_account') ? ' has-error' : '' }}">
                            <input type="text" name="bank_account" class="form-control" @if(!empty($agent)) value="{{ $agent->bank_account }}" @else value="{{ old('bank_account') }}" @endif>
                            <label class="error">{{ $errors->first('bank_account') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Account Name</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('account_name') ? ' has-error' : '' }}">
                            <input type="text" name="account_name" class="form-control" @if(!empty($agent)) value="{{ $agent->account_name }}" @else value="{{ old('account_name') }}" @endif>
                            <label class="error">{{ $errors->first('account_name') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Commission</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('daily_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_commission" name="format_commission" class="form-control" onchange="changeToCurrencyFormat('format_commission','commission')" @if(!empty($room)) value="{{ number_format($room->commission, 0,',','.') }}" @else value="0" @endif>
                            <input type="hidden" id="commission" name="commission" @if(!empty($room)) value="{{ $room->commission }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('commission') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">File</label>
                    <div class="col-sm-10">
                        <div class="fileupload fileupload-new" data-provides="fileupload" style="width:150px;">
                            <div class="fileupload-new thumbnail">
                                @if(!empty($agent))
                                    @if($agent->file != null)
                                        <img src="{{ asset($agent->file) }}" alt="file" width="500">
                                    @else
                                        <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="file" width="500">
                                    @endif
                                @else
                                    <img src="{{ asset('assets/img/image_placeholder.jpg') }}" alt="file" width="500">
                                @endif
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="width:500px;height:500px;"></div>
                            <div>
                                <span class="btn btn-file btn-primary"><span class="fileupload-new"><i class="fa fa-picture-o"></i> Select image</span><span class="fileupload-exists"><i class="fa fa-picture-o"></i> Change</span>
                                    <input type="file" id="file" name="file" />
                                </span>
                                <a href="#" class="btn fileupload-exists btn-danger" data-dismiss="fileupload">
                                    <i class="fa fa-times"></i> Remove
                                </a>
                            </div>
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
