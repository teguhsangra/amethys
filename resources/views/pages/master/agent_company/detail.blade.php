@extends('layouts.app')
@section('title')
Rakomsis Agent Company - {{ $agent_company->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Agent Company</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Agent Company
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $agent_company->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $agent_company->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $agent_company->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $agent_company->phone }}</td>
                            </tr>
                            <tr>
                                <td>Fax</td>
                                <td>{{ $agent_company->fax }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $agent_company->address }}</td>
                            </tr>
                            <tr>
                                <td>Country</td>
                                <td>{{ $agent_company->country }}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>{{ $agent_company->city }}</td>
                            </tr>
                            <tr>
                                <td>Zipcode</td>
                                <td>{{ $agent_company->zipcode }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection