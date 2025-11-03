@extends('layouts.app')

@section('title')
Rakomsis Ticketing - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">
                    Ticketing Form
                    <a href="{{ url($url) }}" class="btn btn-rose pull-right">
                        <i class="fa fa-arrow-left"></i> Back To Ticketing
                    </a>
                </h4>
            </div>
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
            <div class="card-body">


                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Location</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="location_id" id="location_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($locations as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('location_id') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="customer">
                        <label class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('customer_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="customer_id" id="customer_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                    onchange="getContact('{{ url('contact/get_by_customer') }}', this.value);">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($customers as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('customer_id') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="contact_selector" style="display:none;">
                        <label class="col-sm-2 col-form-label">Select Contact</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                                <div id="contact_list">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="location">
                        <label class="col-sm-2 col-form-label">Employee</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="employee_id" id="employee_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($employees as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('employee_id') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="type">
                        <label class="col-sm-2 col-form-label">Type Ticketing</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                                <select class="selectpicker form-control" name="type" id="type_ticket"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                onchange="getData(this.value);">
                                    <option value="" disabled selected>Select Your Option</option>
                                    <option value="room">Room</option>
                                    <option value="product">Product</option>
                                    <option value="package">Package</option>
                                    <option value="booking">Booking</option>
                                    <option value="order">Order</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="room" style="display:none;">
                        <label class="col-sm-2 col-form-label">Room</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('room_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="room_id" id="room_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($room as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->room_number }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('room_id') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="product" style="display:none;">
                        <label class="col-sm-2 col-form-label">Product</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="product_id" id="product_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($products as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('product_id') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="package" style="display:none;">
                        <label class="col-sm-2 col-form-label">Package</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('package_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="package_id" id="package_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($package as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('package_id') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="booking" style="display:none;">
                        <label class="col-sm-2 col-form-label">Booking</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                                <div id="booking_list">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="order" style="display:none;">
                        <label class="col-sm-2 col-form-label">Order</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                                <div id="order_list">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="template">
                        <label class="col-sm-2 col-form-label">Template Subject</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group">
                                <select class="selectpicker form-control" name="template" id="templates"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                >
                                    <option value="" disabled selected>Select Your Option</option>
                                    <option value="Y">With Template Subject</option>
                                    <option value="N">Custom Subject</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="template_subject" style="display:none;">
                        <label class="col-sm-2 col-form-label">Ticketing Subject</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('ticketing_subject_id') ? ' has-error' : '' }}">
                                    <select class="selectpicker form-control" name="ticketing_subject_id" id="ticketing_subject_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true"
                                    >
                                        <option value="" disabled selected>Select Your Option</option>
                                        @foreach($subjects as $detail)
                                            <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                        @endforeach
                                    </select>
                                <label class="error">{{ $errors->first('ticketing_subject_id') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="row" id="subjects" style="display:none;">
                        <label class="col-sm-2 col-form-label">Subject</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('subject') ? ' has-error' : '' }}">
                                <input type="text" name="subject" class="form-control" value="{{old('subject')}}">
                                <label class="error">{{ $errors->first('subject') }}</label>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <label class="col-sm-2 col-form-label">Remarks</label>
                        <div class="col-sm-10">
                            <div class="form-group bmd-form-group{{ $errors->has('remarks') ? ' has-error' : '' }}">
                                <textarea class="form-control" rows="5" name="remarks" id="remarks" placeholder="Remarks..." >{!! old('remarks') !!}</textarea>
                                <label class="error">{{ $errors->first('remarks') }}</label>
                            </div>
                        </div>
                    </div>


            </div>
            <div class="card-footer">
                <div class="col-md-12 text-center">
                    <a href="{{ url('ticketing') }}" class="col-md-4  btn-lg btn btn-warning">Back</a>
                    <button type="submit" class="col-md-6 btn btn-lg btn-primary">Send</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@section('js')
<script>

var link_booking = "{{ url('booking/get_by_customer_id') }}";
var link_order = "{{ url('order/get_by_customer_id') }}";

function getContact(link, customer_id){
    var url = link+"/"+customer_id;

    var contact_list = "";

    $.get(url, function (data){
        contact_list += '<select class="form-control  selectpicker" name="contact_id" id="contact_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">';
                contact_list += '<option value="">Select Contact</option>';
        for(var i=0; i < data.length; i++){
            contact_list += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
        }

        contact_list += '</select>';
        document.getElementById("contact_list").innerHTML = contact_list;

        $('#contact_id').selectpicker('refresh');
        $('#contact_selector').show();
    });

}

function getData(type){


    if(type == ""){
        $('#room').hide();
        $('#product').hide();
        $('#package').hide();
        $('#booking').hide();
        $('#order').hide();
    }else if(type == "room"){
        $('#room').show();
        $('#product').hide();
        $('#package').hide();
        $('#booking').hide();
        $('#order').hide();
    }else if(type == "product"){
        $('#room').hide();
        $('#product').show();
        $('#package').hide();
        $('#booking').hide();
        $('#order').hide();
    }else if(type == "package"){
        $('#room').hide();
        $('#product').hide();
        $('#package').show();
        $('#booking').hide();
        $('#order').hide();
    }
    else if(type == "booking"){
        $('#room').hide();
        $('#product').hide();
        $('#package').hide();
        $('#booking').hide();
        $('#order').hide();
        getBooking();
    }
    else if(type == "order"){
        $('#room').hide();
        $('#product').hide();
        $('#package').hide();
        $('#booking').hide();
        $('#order').hide();
        getOrder();
    }else{
        $('#room').hide();
        $('#product').hide();
        $('#package').hide();
        $('#booking').hide();
        $('#order').hide();
    }
}
function getBooking(){
    var customer_id = document.getElementById("customer_id").value;

    var url = link_booking+"/"+customer_id;

    var booking_list = "";
    if(customer_id != ''){
        $.get(url, function (data){
            booking_list += '<select class="form-control  selectpicker" name="booking_id" id="booking_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">';

            for(var i=0; i < data.length; i++){
                booking_list += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
            }

            booking_list += '</select>';
            document.getElementById("booking_list").innerHTML = booking_list;

            $('#booking_id').selectpicker('refresh');
            $('#booking').show();
        });
    }

}
function getOrder(){
    var customer_id = document.getElementById("customer_id").value;

    var url = link_order+"/"+customer_id;

    var order_list = "";
    if(customer_id != ''){
        $.get(url, function (data){
            order_list += '<select class="form-control  selectpicker" name="order_id" id="order_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">';

            for(var i=0; i < data.length; i++){
                order_list += '<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
            }

            order_list += '</select>';
            document.getElementById("order_list").innerHTML = order_list;

            $('#order_id').selectpicker('refresh');
            $('#order').show();
        });
    }

}
$(function() {
    $("#templates").change(function() {

        formSubject();
    });
});
function formSubject(){
    var type = document.getElementById("templates").value;

    if(type == ''){
        $('#template_subject').hide();
        $('#subjects').hide();
    }else if(type == "Y"){
        $('#template_subject').show();
        $('#subjects').hide();
    }else if(type == "N"){
        $('#template_subject').hide();
        $('#subjects').show();
    }else{
        $('#template_subject').hide();
        $('#subjects').hide();
    }
}
</script>
@endsection
