@extends('layouts.app')
@section('title')
Rakomsis Room - Editor
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Room Form</h4>
            </div>
            <div class="card-body">
            {{ Form::open(array('url' => $form_url, 'method' => $method, 'id' => $form_id, 'enctype' => 'multipart/form-data')) }}
                <div class="row">
                    <label class="col-sm-2 col-form-label">Location</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="location_id" onchange="getDataFromLocation('{{ url('room/get_by_location_id') }}', this.value)" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                @foreach($locations as $detail)
                                    <option value="{{ $detail->id }}" @if(!empty($room)) @if($room->location_id == $detail->id) selected @endif @endif>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('location_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Room Category</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('room_category_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="room_category_id[]" data-size="5" multiple="multiple" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                @foreach($room_categories as $detail)
                                    @php
                                        $selected = '';
                                        if(!empty($room)){
                                            foreach($room->room_category as $room_category){
                                                if($room_category->id == $detail->id){
                                                    $selected = 'selected';
                                                }
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
                    <label class="col-sm-2 col-form-label">Room Type</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('room_type_id') ? ' has-error' : '' }}">
                            <select class="selectpicker form-control" name="room_type_id" data-size="5" data-style="select-with-transition" title="Single Select" data-show-subtext="true" data-live-search="true">
                                <option disabled selected>Select Your Option</option>
                                @foreach($room_types as $detail)
                                    <option value="{{ $detail->id }}" @if(!empty($room)) @if($room->room_type_id == $detail->id) selected @endif @endif>{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <label class="error">{{ $errors->first('room_type_id') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Parent Room</label>
                    <div class="col-sm-10" id="parent_room">
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Code</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('code') ? ' has-error' : '' }}">
                            <input type="text" name="code" class="form-control" @if(!empty($room)) value="{{ $room->code }}" @else value="{{ $code }}" @endif>
                            <label class="error">{{ $errors->first('code') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Room Number</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('room_number') ? ' has-error' : '' }}">
                            <input type="text" name="room_number" class="form-control" @if(!empty($room)) value="{{ $room->room_number }}" @else value="{{ old('room_number') }}" @endif>
                            <label class="error">{{ $errors->first('room_number') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Monthly Price</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('monthly_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_monthly_price" name="format_monthly_price" class="form-control" onchange="changeToCurrencyFormat('format_monthly_price','monthly_price')" @if(!empty($room)) value="{{ number_format($room->monthly_price, 0,',','.') }}" @endif>
                            <input type="hidden" id="monthly_price" name="monthly_price" @if(!empty($room)) value="{{ $room->monthly_price }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('monthly_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Daily Price</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('daily_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_daily_price" name="format_daily_price" class="form-control" onchange="changeToCurrencyFormat('format_daily_price','daily_price')" @if(!empty($room)) value="{{ number_format($room->daily_price, 0,',','.') }}" @endif>
                            <input type="hidden" id="daily_price" name="daily_price" @if(!empty($room)) value="{{ $room->daily_price }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('daily_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row" style="display:none">
                    <label class="col-sm-2 col-form-label">Daily Price (Exclude Breakfast)</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('daily_exclude_breakfast_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_daily_price" name="format_daily_exclude_breakfast_price" class="form-control" onchange="changeToCurrencyFormat('format_daily_exclude_breakfast_price','daily_exclude_breakfast_price')" @if(!empty($room)) value="{{ number_format($room->daily_exclude_breakfast_price, 0,',','.') }}" @endif>
                            <input type="hidden" id="daily_exclude_breakfast_price" name="daily_exclude_breakfast_price" @if(!empty($room)) value="{{ $room->daily_exclude_breakfast_price }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('daily_exclude_breakfast_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Hourly Price</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('hourly_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_hourly_price" name="format_hourly_price" class="form-control" onchange="changeToCurrencyFormat('format_hourly_price','hourly_price')" @if(!empty($room)) value="{{ number_format($room->hourly_price, 0,',','.') }}" @endif>
                            <input type="hidden" id="hourly_price" name="hourly_price" @if(!empty($room)) value="{{ $room->hourly_price }}" @else value="{{ old('hourly_price') }}" @endif>
                            <label class="error">{{ $errors->first('hourly_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Halfday Price</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('halfday_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_halfday_price" name="format_halfday_price" class="form-control" onchange="changeToCurrencyFormat('format_halfday_price','halfday_price')" @if(!empty($room)) value="{{ number_format($room->halfday_price, 0,',','.') }}" @endif>
                            <input type="hidden" id="halfday_price" name="halfday_price" @if(!empty($room)) value="{{ $room->halfday_price }}" @else value="{{ old('halfday_price') }}" @endif>
                            <label class="error">{{ $errors->first('halfday_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Hourly Price (After Office Hour)</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('after_office_hourly_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_after_office_hourly_price" name="format_after_office_hourly_price" class="form-control" onchange="changeToCurrencyFormat('format_after_office_hourly_price','after_office_hourly_price')" @if(!empty($room)) value="{{ number_format($room->after_office_hourly_price, 0,',','.') }}" @else value="{{ number_format(old('after_office_hourly_price'), 0,',','.') }}" @endif>
                            <input type="hidden" id="after_office_hourly_price" name="after_office_hourly_price" @if(!empty($room)) value="{{ $room->after_office_hourly_price }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('after_office_hourly_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Hourly Price (Holiday Price)</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('holiday_hourly_price') ? ' has-error' : '' }}">
                            <input type="text" id="format_holiday_hourly_price" name="format_holiday_hourly_price" class="form-control" onchange="changeToCurrencyFormat('format_holiday_hourly_price','holiday_hourly_price')" @if(!empty($room)) value="{{ number_format($room->holiday_hourly_price, 0,',','.') }}" @endif>
                            <input type="hidden" id="holiday_hourly_price" name="holiday_hourly_price" @if(!empty($room)) value="{{ $room->holiday_hourly_price }}" @else value="0" @endif>
                            <label class="error">{{ $errors->first('holiday_hourly_price') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Sqm</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('sqm') ? ' has-error' : '' }}">
                            <input type="number" name="sqm" class="form-control" @if(!empty($room)) value="{{ $room->sqm }}" @else value="{{ old('sqm') }}" @endif>
                            <label class="error">{{ $errors->first('sqm') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Number of workstation</label>
                    <div class="col-sm-10">
                        <div class="form-group bmd-form-group{{ $errors->has('number_of_workstation') ? ' has-error' : '' }}">
                            <input type="number" name="number_of_workstation" class="form-control" @if(!empty($room)) value="{{ $room->number_of_workstation }}" @else value="{{ old('number_of_workstation') }}" @endif>
                            <label class="error">{{ $errors->first('number_of_workstation') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Service Charge Status</label>
                    <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="has_service_charge" value="N" @if(!empty($room)) @if($room->has_service_charge == "N") checked @endif @else checked @endif> N
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="has_service_charge" value="Y" @if(!empty($room)) @if($room->has_service_charge == "Y") checked @endif @endif> Y
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row" id="other_funiture">
                    <label class="col-sm-2 col-form-label">Furniture</label>
                    <div class="col-sm-10">
                        <div class="input-group mb-3">
                            <select class="selectpicker form-control col-md-11" id="furniture_list" data-size="5" data-style="select-with-transition" data-show-subtext="true" data-live-search="true">
                                <option value="">-- Select Furniture --</option>
                                @foreach($furniture as $detail)
                                    <option value="{{ $detail->id }}">{{ $detail->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <a class="btn btn-success btn-round" style="color: #fff;" onclick="addFurniture()">
                                    <i class="material-icons">add</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="furniture_table" style="display:none">
                    <label class="col-sm-2 col-form-label">Detail Furniture</label>
                    <div class="col-sm-10">
                        <table class="table table-bordered">
                            <tbody id="furnitures" style="display:none">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <label class="col-sm-2 col-form-label">Editable Price</label>
                    <div class="col-sm-10 checkbox-radios">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="is_editable_price" value="N" @if(!empty($room)) @if($room->is_editable_price == "N") checked @endif @else checked @endif> N
                                <span class="circle">
                                    <span class="check"></span>
                                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="is_editable_price" value="Y" @if(!empty($room)) @if($room->is_editable_price == "Y") checked @endif @endif> Y
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

@section('js')
<script>
    var link_furnitures = "{{ url('furniture/get_by_id') }}";
    var selected_furniture = new Array;
    var new_item = new Array;

    @if(!empty($room))
        getDataFromLocation('{{ url('room/get_by_location_id/') }}', {{ $room->location_id }});

        @foreach($room->furniture as $no => $furniture)
            var furniture_id = '{{ $furniture->id }}';
            var url_additional_charge = link_furnitures+"/"+furniture_id;
            $.get(url_additional_charge, function (data){
                item_furniture = new Array;
                item_furniture['id'] = '{{ $furniture->id }}';
                item_furniture['name'] = '{{ $furniture->name }}';
                item_furniture['qty'] = '{{ $furniture->pivot->quantity }}';

                selected_furniture.push(item_furniture);
                @if(sizeof($room->furniture) == $no +1)
                    setFurniture();
                @endif
            });
        @endforeach
    @endif

    function addFurniture(furniture_id=null){
        var item_furniture = new Array;

        if(furniture_id == null){
            furniture_id = document.getElementById("furniture_list").value;
        }
        var url_furnitures = link_furnitures+"/"+furniture_id;
        var availability = true;

        if(furniture_id != ""){
            for(var i=0; i < selected_furniture.length; i++){
                if(selected_furniture[i].id == furniture_id){
                    availability = false;
                    break;
                }
            }
            if(availability){
                $.get(url_furnitures, function (data){
                    item_furniture['id'] = data['id'];
                    item_furniture['name'] = data['name'];
                    item_furniture['qty'] = 1;

                    selected_furniture.push(item_furniture);
                    alert("New Furniture Added");
                    setFurniture();
                });
            }else{
                alert("You already select this item");
            }
        }else{
            alert("You have to select one of the item");
        }
    }

    function setFurniture(){
        var furniture = '';
        if(selected_furniture.length > 0){
            furniture += '<tr>';
                furniture += '<td colspan="6"><b>Furniture</b></td>';
            furniture += '</tr>';
            furniture += '<tr>';
                furniture += '<td class="text-center">#</td>';
                furniture += '<td class="text-center">Item Name</td>';
                furniture += '<td class="text-center" colspan="4">Qty</td>';
            furniture += '</tr>';

            for(var i=0; i < selected_furniture.length; i++){
                var furniture_id = selected_furniture[i].id;
                var furniture_name = selected_furniture[i].name;
                var furniture_quantity = selected_furniture[i].qty;

                furniture += '<tr>';
                furniture += '<td class="text-center"><a class="btn btn-danger btn-round" onclick="removeFurniture('+i+')"><i class="material-icons">remove</i></a></td>';
                furniture += '<td><input type="hidden" name="furniture_id[]" value="'+furniture_id+'">'+furniture_name+'</td>';
                furniture += '<td class="text-center" colspan="4"><input type="number" class="form-control text-center" name="fu_quantity[]" id="fu_quantity'+furniture_id+'" min="1" value="'+furniture_quantity+'" oninput="countQuantityFurniture()"></td>';

                if(i == selected_furniture.length - 1){
                    furniture += '';
                }else{
                    furniture += '</tr>';
                }

                $("#furniture_table").show();
                $("#furnitures").show();
            }
        }else{
            $("#furniture_table").hide();
            $("#furnitures").hide();
        }
        document.getElementById("furnitures").innerHTML = furniture;
    }

    function removeFurniture(index){
        selected_furniture.splice(index, 1);
        setFurniture();
    }

    function countQuantityFurniture(){
        for(var i=0; i < selected_furniture.length; i++){
            var furniture_id = selected_furniture[i].id;
            var fu_quantity = parseFloat(document.getElementById("fu_quantity"+furniture_id).value);

            selected_furniture[i].qty = fu_quantity;
        }
    }

    function getDataFromLocation(link, location_id){
        var url = link+"/"+location_id;

        var room_parent_list = "";
        var parent_id = null;
        var this_id = null;

        @if(!empty($room))
            @if($room->parent_id != null)
                parent_id = {{ $room->parent_id }};
            @endif
            this_id = {{ $room->id }};
        @endif

        $.get(url, function (data){
            room_parent_list += '<select class="form-control" name="parent_id">';
            room_parent_list += '<option disabled selected>Select Your Option</option>';

            for(var i=0; i < data.length; i++){
                var selected = '';

                if(data[i]['id'] == parent_id){
                    selected = 'selected';
                }
                if(this_id == null){
                    room_parent_list += '<option value="'+data[i]['id']+'" '+selected+'>'+data[i]['room_number']+'</option>';
                }else{
                    if(data[i]['id'] != this_id){
                        room_parent_list += '<option value="'+data[i]['id']+'" '+selected+'>'+data[i]['room_number']+'</option>';
                    }
                }
            }

            room_parent_list += '</select>';

            document.getElementById("parent_room").innerHTML = room_parent_list;
        });
    }
</script>
@endsection
