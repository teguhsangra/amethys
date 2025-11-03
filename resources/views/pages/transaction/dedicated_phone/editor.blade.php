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
            <div class="row">
                <label class="col-sm-2 col-form-label">Location</label>
                <div class="col-sm-10">
                    <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                        <select class="selectpicker form-control" name="location_id" id="location_id"  data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                            <option value="" disabled selected>Select Your Option</option>
                            @foreach($locations as $detail)
                                    @php
                                    $selected = '';
                                    if(!empty($dedicated_phone)){
                                        if($dedicated_phone->location_id == $detail->id){
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
                <label class="col-sm-2 col-form-label">Booking</label>
                <div class="col-sm-10">

                    <div class="form-group bmd-form-group{{ $errors->has('booking_id') ? ' has-error' : '' }}">
                        <select class="selectpicker form-control"  id="booking_id" name="booking_id" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true" onchange="getData();">
                            <option value="" disabled selected>Select Your Option</option>
                            @foreach($booking as $detail)
                                @php
                                    $selected = '';
                                    if(!empty($dedicated_phone)){
                                        if($dedicated_phone->booking_id == $detail->id){
                                            $selected = 'selected';
                                        }
                                    }
                                @endphp
                                <option value="{{ $detail->id }}" {{ $selected }}>{{ $detail->code }} - {{ optional($detail->customer)->name ?? '-' }}
</option>
                            @endforeach
                        </select>
                        <label class="error">{{ $errors->first('booking_id') }}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 col-form-label">Customer</label>
                <div class="col-sm-10">
                        <input type="text" class="form-control" id="customers"  readonly>
                        <input type="hidden" name="customer_id" id="customer_id" >
                </div>
            </div>
            <div class="row" id="start_to_end" style="display:none;">
                <label class="col-sm-2 col-form-label">Periode</label>
                <div class="col-sm-10">
                    <div class="row" id="datepicker">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="start_date" id="start_date" class="form-control datepicker text-center" placeholder="Start Date" readonly>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <input type="text" name="end_date" id="end_date" class="form-control datepicker text-center" placeholder="End Date"  readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="dedicated_phone_selector">
                <label class="col-sm-2 col-form-label">Dedicated Phones</label>
                <div class="col-sm-10">
                    <div class="input-group mb-3">
                        <select class="selectpicker form-control col-md-11" name="dedicated_phone" id="dedicated_phone_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                            <option value="">--- Select Phone Number ---</option>
                            @foreach ($dedicated_phones as $detail)
                                <option value="{{ $detail->id }}" {{ $selected}}>{{ $detail->number }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <a class="btn btn-success btn-round" style="color: #fff;" onclick="addDedicatedPhone()">
                                <i class="material-icons">add</i>
                            </a>
                        </div>
                        <label class="error">{{ $errors->first('dedicated_phone_id') }}</label>
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
            <div class="row" id="dedicated_table" style="display:none">
                <label class="col-sm-2 col-form-label">Dedicated Phone</label>
                <div class="col-sm-10">
                    <table class="table table-bordered">
                        <tbody id="dedicated_phone_lists" style="display:none">
                        </tbody>
                    </table>
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
    var link_dedicated = "{{ url('dedicated_phone/get_by_id') }}";
    var link_booking = "{{ url('dedicated_phone_transaction/booking/get_by_id') }}";
    var link_booking_dedicated = "{{ url('dedicated_phone_transaction/booking/get_by_booking') }}";
    var selected_dedicated_phone = new Array;
    var new_item = new Array;


    function getData(){
        var booking_id = document.getElementById("booking_id").value;

        var url = link_booking+"/"+booking_id;
        var url_dedicated = link_booking_dedicated+"/"+booking_id;
        $.get(url, function (data){
            document.getElementById("customers").value = data['name'];
            document.getElementById("customer_id").value = data['id'];
            document.getElementById("start_date").value = data['start_date'];
            document.getElementById("end_date").value = data['end_date'];
            $('#start_to_end').show();
        });
        getDedicatedPhone();
        // $.get(url_dedicated, function (data){
        //     for(var i=0; i < data.length; i++){
        //         var dedicated_phone_id = data[i]['dedicated_phone_id'];
        //         var url_dedicated_phone = link_dedicated+"/"+dedicated_phone_id;
        //         $.get(url_dedicated_phone, function (value){
        //             new_item = new Array;
        //             new_item['id'] =  value['id'];
        //             new_item['number'] = value['number'];

        //             selected_dedicated_phone.push(new_item);
        //             setDetailDedicatedPhone();
        //         });
        //     }
        // });

    }
    function getDedicatedPhone(){
        var booking_id = document.getElementById("booking_id").value;
        var link = "{{ url('getBookingDedicated') }}";
        if(booking_id != ''){
            var url = link+"?booking_id="+booking_id;
            $.get(url, function (data){
                selected_dedicated_phone = data.bookings;
                $('#booking_id').selectpicker('refresh');
                setDetailDedicatedPhone();
            });
        }
    }

    function addDedicatedPhone(id = null){
        var new_item = new Array;
        var dedicated_phone_id = document.getElementById("dedicated_phone_list").value;
        var link = "{{ url('dedicated_phone/get_by_id') }}";
        var availability = true;

        if(id != null){
            dedicated_phone_id = id;
        }

        if(dedicated_phone_id != ""){
            var url = link+"/"+dedicated_phone_id;

            for(var i=0; i < selected_dedicated_phone.length; i++){
                if(selected_dedicated_phone[i].id == dedicated_phone_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                $.get(url, function (data){

                    new_item['id'] = data['id'];
                    new_item['number'] = data['number'];

                    selected_dedicated_phone.push(new_item);
                    setDetailDedicatedPhone();
                    if(id == null) alert("New Dedicated Phone Added");
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }

    }

    function removeDedicatedPhone(index){
        selected_dedicated_phone.splice(index, 1);
        setDetailDedicatedPhone();
    }

    function setDetailDedicatedPhone(){
        var dedicated_phone = '';
        if(selected_dedicated_phone.length > 0){
            dedicated_phone += '<tr>';
                dedicated_phone += '<td colspan="6"><b>Dedicated Phone</b></td>';
            dedicated_phone += '</tr>';
            dedicated_phone += '<tr>';
                dedicated_phone += '<td class="text-center">#</td>';
                dedicated_phone += '<td class="text-center" colspan="5">Number</td>';
            dedicated_phone += '</tr>';
            for(var i=0; i < selected_dedicated_phone.length; i++){

                var dedicated_phone_id = selected_dedicated_phone[i].id;
                var dedicated_phone_number = selected_dedicated_phone[i].number;

                dedicated_phone += '<tr>';
                dedicated_phone += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeDedicatedPhone('+i+')"><i class="material-icons">remove</i></a></td>';
                dedicated_phone += '<td colspan="5" class="text-center"><input type="hidden" name="dedicated_phone_id[]" value="'+dedicated_phone_id+'">'+dedicated_phone_number+'</td>';


                if(i == selected_dedicated_phone.length - 1){
                    dedicated_phone += '';
                }else{
                    dedicated_phone += '</tr>';
                }

                $("#dedicated_table").show();
                $("#dedicated_phone_lists").show();
            }
        }else{
            $("#dedicated_table").hide();
            $("#dedicated_phone_lists").hide();
        }
        document.getElementById("dedicated_phone_lists").innerHTML = dedicated_phone;
    }


</script>
@endsection
