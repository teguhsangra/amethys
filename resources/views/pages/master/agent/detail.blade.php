@extends('layouts.app')
@section('title')
Rakomsis Business Partner - {{ $agent->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Business Partner</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Business Partner
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Business Partner Company</td>
                                <td>
                                @if($agent->agent_company_id != null)
                                    {{ $agent->agent_company->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>{{ $agent->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $agent->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $agent->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $agent->phone }}</td>
                            </tr>
                            <tr>
                                <td>Mobile Phone</td>
                                <td>{{ $agent->mobile_phone }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $agent->address }}</td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>{{ $agent->country }}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>{{ $agent->city }}</td>
                            </tr>
                            <tr>
                                <td>Zipcode</td>
                                <td>{{ $agent->zipcode }}</td>
                            </tr>
                            <tr>
                                <td>Tax Number</td>
                                <td>{{ $agent->tax_number }}</td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td>{{ $agent->bank_name }}</td>
                            </tr>
                            <tr>
                                <td>Bank Account</td>
                                <td>{{ $agent->bank_account }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection