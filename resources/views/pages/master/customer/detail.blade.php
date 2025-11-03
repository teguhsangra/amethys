@extends('layouts.app')
@section('title')
Rakomsis Customer - {{ $customer->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Customer</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Customer
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Total Deposit</td>
                                <td>{{ number_format($customer->total_security_deposit, 0,',','.') }}</td>
                            </tr>
                            <tr>
                                <td>Nature Of Business</td>
                                <td>@if($customer->nature_of_business_id != null) {{ $customer->nature_of_business->name }} @endif</td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>{{ $customer->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $customer->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $customer->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $customer->phone }}</td>
                            </tr>
                            <tr>
                                <td>Mobile Phone</td>
                                <td>{{ $customer->mobile_phone }}</td>
                            </tr>
                            <tr>
                                <td>Fax</td>
                                <td>{{ $customer->fax }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $customer->address }}</td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>{{ $customer->country }}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>{{ $customer->city }}</td>
                            </tr>
                            <tr>
                                <td>Zipcode</td>
                                <td>{{ $customer->zipcode }}</td>
                            </tr>
                            <tr>
                                <td>Tax Number</td>
                                <td>{{ $customer->tax_number }}</td>
                            </tr>
                            <tr>
                                <td>Virtual Account No</td>
                                <td>{{ $customer->virtual_account_no }}</td>
                            </tr>
                            <tr>
                                <td>Virtual Account Bank</td>
                                <td>{{ $customer->virtual_account_bank }}</td>
                            </tr>
                            <tr>
                                <td>Contact</td>
                                <td>
                                    <table class="table" width="100%" style="width:100%">
                                        <thead>
                                            <th>Contact Name</th>
                                            <th>Default Status</th>
                                            <th>Position</th>
                                            <th>Department</th>
                                        </thead>
                                        <tbody>
                                        @foreach($customer->contact as $contact)
                                            <tr>
                                                <td>{{ $contact->name }}</td>
                                                <td>{{ $contact->pivot->default_status }}</td>
                                                <td>{{ $contact->pivot->position }}</td>
                                                <td>{{ $contact->pivot->department }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>File</td>
                                <td>
                                    <table class="table" style="width:100%">
                                        <thead>
                                            <th>File Name</th>
                                        </thead>
                                        <tbody>
                                        @foreach($customer->customer_file as $customer_file)
                                            <tr>
                                                <td>
                                                    <a href="{{ $customer_file->file }}" target="_blank">{{ $customer_file->name }}</a>
                                                </td>
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