@extends('layouts.app')
@section('title')
Rakomsis Furniture - {{ $furniture->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Furniture</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Furniture
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $furniture->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $furniture->name }}</td>
                            </tr>
                            <tr>
                                <td>Default Picture</td>
                                <td>
                                    @if($furniture->default_photo != null)
                                        <img src="{{ asset($furniture->default_photo) }}" width="300">
                                    @else
                                        <img src="{{ asset('assets/img/image_placeholder.jpg') }}" width="300">
                                    @endif
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