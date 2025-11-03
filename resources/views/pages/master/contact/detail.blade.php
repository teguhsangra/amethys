@extends('layouts.app')
@section('title')
Rakomsis Contact - {{ $contact->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Contact</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Contact
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $contact->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $contact->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $contact->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $contact->phone }}</td>
                            </tr>
                            <tr>
                                <td>Mobile Phone</td>
                                <td>{{ $contact->mobile_phone }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $contact->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection