@extends('layouts.app')
@section('title')
Rakomsis Room - {{ $room->room_number }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Room</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Room
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $room->code }}</td>
                            </tr>
                            <tr>
                                <td>Room Number</td>
                                <td>{{ $room->room_number }}</td>
                            </tr>
                            <tr>
                                <td>Monthly Price</td>
                                <td>{{ number_format($room->monthly_price, 0,',','.') }}</td>
                            </tr>
                            <tr>
                                <td>Daily Price</td>
                                <td>{{ number_format($room->daily_price, 0,',','.') }}</td>
                            </tr>
                            <!-- <tr>
                                <td>Daily Price (Exclude Breakfast)</td>
                                <td>{{ number_format($room->daily_exclude_breakfast_price, 0,',','.') }}</td>
                            </tr> -->
                            <tr>
                                <td>Hourly Price</td>
                                <td>{{ number_format($room->hourly_price, 0,',','.') }}</td>
                            </tr>
                            <tr>
                                <td>Hourly Price (After Office Hour)</td>
                                <td>{{ number_format($room->after_office_hourly_price, 0,',','.') }}</td>
                            </tr>
                            <tr>
                                <td>Hourly Price (Holiday Price)</td>
                                <td>{{ number_format($room->holiday_hourly_price, 0,',','.') }}</td>
                            </tr>
                            <tr>
                                <td>Sqm</td>
                                <td>{{ $room->sqm }}</td>
                            </tr>
                            <tr>
                                <td>Number of workstation</td>
                                <td>{{ $room->number_of_workstation }}</td>
                            </tr>
                            <tr>
                                <td>Default Picture</td>
                                <td>
                                    @if($room->default_photo != null)
                                        <img src="{{ asset($room->default_photo) }}" width="300">
                                    @else
                                        <img src="{{ asset('assets/img/image_placeholder.jpg') }}" width="300">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>{{ $room->location->name }}</td>
                            </tr>
                            <tr>
                                <td>Room Type</td>
                                <td>
                                @if($room->room_type_id != null)
                                    {{ $room->room_type->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Room Category</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                        @foreach($room->room_category as $room_category)
                                            <tr>
                                                <td>{{ $room_category->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection