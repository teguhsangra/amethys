@extends('layouts.app')
@section('title')
Rakomsis Access Card - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Access Card Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
            <div class="row">
                <label class="col-sm-2 col-form-label">Location</label>
                <div class="col-sm-10">
                    <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                        <select class="selectpicker form-control" name="location_id" id="location_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                            <option value="" disabled selected>Select Your Option</option>
                            @foreach($locations as $detail)
                                    @php
                                    $selected = '';
                                    if(!empty($access_card)){
                                        if($access_card->location_id == $detail->id){
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
            <div class="row" id="customer_selector">
                <label class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-10">

                    <div class="form-group bmd-form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                        <select class="selectpicker form-control" onchange="getContact('{{ url('contact/get_by_customer') }}', this.value);getComplimentary()" id="customer_id" name="customer_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                            <option value="" disabled selected>Select Your Option</option>
                            @foreach($customers as $detail)
                                @php
                                    $selected = '';
                                    if(!empty($access_card)){
                                        if($access_card->customer_id == $detail->id){
                                            $selected = 'selected';
                                        }
                                    }
                                @endphp
                                <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->name }}</option>
                            @endforeach
                        </select>
                        <label class="error">{{ $errors->first('customer_id') }}</label>
                    </div>
                </div>
            </div>
            <div class="row" id="contact_selector">
                <label class="col-sm-2 col-form-label">Select Contact</label>
                <div class="col-sm-10">
                    <div  id="contact_list">

                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 col-form-label">Access Card</label>
                <div class="col-sm-10">
                    @if($method == "PUT")
                        <input type="text" class="form-control" value="{{$access_card->code}}" readonly>
                        <input type="hidden" name="access_card_id" id="access_card_id" value="{{$access_card->id}}">
                    @else
                        <div class="form-group bmd-form-group{{ $errors->has('access_card_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="access_card_id" id="access_card_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="" disabled selected>Select Your Option</option>
                                    @foreach($card as $detail)
                                        @php
                                        $selected = '';
                                        if(!empty($access_card)){
                                            if($access_card->id == $detail->id){
                                                $selected = 'selected';
                                            }
                                        }
                                    @endphp
                                    <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->code }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('access_card_id') }}</label>
                        </div>
                    @endif
                </div>

            </div>
            <div class="row">
                <label class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10 checkbox-radios" >
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="activity" id="activity"  value="activation"  @if(!empty($access_card)) @if($access_card->activity == 'activation') checked @endif @endif checked> Active
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="activity" id="activity"  value="deactivation"  @if(!empty($access_card)) @if($access_card->activity == 'deactivation') checked @endif @endif> Non Active
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="activity" id="activity" value="missing"  @if(!empty($access_card)) @if($access_card->activity == 'missing') checked @endif @endif> Lost
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="activity" id="activity" value="defective"  @if(!empty($access_card)) @if($access_card->activity == 'defective') checked @endif @endif> Defective
                            <span class="circle">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 col-form-label">Remarks</label>
                <div class="col-sm-10">
                    <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                        <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..."> @if(!empty($access_card)){{ $access_card->remarks }}@endif</textarea>
                        <label class="error">{{ $errors->first('remarks') }}</label>
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
@if(!empty($access_card))
getContact('{{ url('contact/get_by_customer') }}', {{ $access_card->customer_id }});
@endif
function getContact(link, customer_id){
    var url = link+"/"+customer_id;

    var contact_list = "";

    $.get(url, function (data){
        contact_list += '<select class="form-control selectpicker" name="contact_id" id="contact_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">';

        for(var i=0; i < data.length; i++){
            contact_list += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
        }

        contact_list += '</select>';

        document.getElementById("contact_list").innerHTML = contact_list;

        $('#contact_id').selectpicker('refresh');
    });

}
</script>
@endsection
