@extends('layouts.app')
@section('title')
Rakomsis Vendor - {{ $vendor->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Vendor</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Vendor
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $vendor->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $vendor->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $vendor->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $vendor->phone }}</td>
                            </tr>
                            <tr>
                                <td>Mobile Phone</td>
                                <td>{{ $vendor->mobile_phone }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $vendor->address }}</td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>{{ $vendor->country }}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>{{ $vendor->city }}</td>
                            </tr>
                            <tr>
                                <td>Zipcode</td>
                                <td>{{ $vendor->zipcode }}</td>
                            </tr>
                            <tr>
                                <td>Tax Number</td>
                                <td>{{ $vendor->tax_number }}</td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td>{{ $vendor->bank_name }}</td>
                            </tr>
                            <tr>
                                <td>Bank Account</td>
                                <td>{{ $vendor->bank_account }}</td>
                            </tr>
                            <tr>
                                <td>Vendor Category</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                        @foreach($vendor->vendor_category as $vendor_category)
                                            <tr>
                                                <td>{{ $vendor_category->name }}</td>
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