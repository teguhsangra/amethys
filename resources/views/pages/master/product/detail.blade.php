@extends('layouts.app')
@section('title')
Rakomsis Product - {{ $product->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Product</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Product
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $product->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <td>Price Type</td>
                                <td>{{ $product->price_type }}</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td>{{ number_format($product->price, 0, ',', ',') }}</td>
                            </tr>
                            <tr>
                                <td>Type</td>
                                <td>{{ $product->type }}</td>
                            </tr>
                            <tr>
                                <td>Main Status</td>
                                <td>{{ $product->main_status }}</td>
                            </tr>
                            <tr>
                                <td>Main Status</td>
                                <td>{{ $product->has_service_charge }}</td>
                            </tr>
                            <tr>
                                <td>Quantity Status</td>
                                <td>{{ $product->quantity_status }}</td>
                            </tr>
                            <tr>
                                <td>Default Picture</td>
                                <td>
                                    @if($product->default_photo != null)
                                        <img src="{{ asset($product->default_photo) }}" width="300">
                                    @else
                                        <img src="{{ asset('assets/img/image_placeholder.jpg') }}" width="300">
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Product Category</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                        @foreach($product->product_category as $product_category)
                                            <tr>
                                                <td>{{ $product_category->name }}</td>
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
