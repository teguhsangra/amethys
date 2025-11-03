@extends('layouts.app')
@section('title')
Rakomsis Package - {{ $package->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Package</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Package
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $package->code }}</td>
                            </tr>
                            <tr>
                                <td>Location</td>
                                <td>
                                @if($package->location_id)
                                    {{ $package->location->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $package->name }}</td>
                            </tr>
                            <tr>
                                <td>Price Type</td>
                                <td>{{ $package->price_type }}</td>
                            </tr>
                            <tr>
                                <td>Room</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                            @foreach($package->room as $room)
                                                <tr>
                                                    <td>{{ $room->room_number }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>Product</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                            @foreach($package->product as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td>{{ number_format($package->price, 0, ',', ',') }}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{ $package->type }}</td>
                            </tr>
                            <tr>
                                <td>Main Status</td>
                                <td>{{ $package->main_status }}</td>
                            </tr>
                            <tr>
                                <td>Quantity Status</td>
                                <td>{{ $package->quantity_status }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection