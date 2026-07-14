@extends('layouts.app')
@section('title')
Rakomsis Marketing Material - {{ $marketing_material->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Marketing Material</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Marketing Material
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $marketing_material->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $marketing_material->name }}</td>
                            </tr>
                            <tr>
                                <td>desc</td>
                                <td>{!! $marketing_material->desc !!}</td>
                            </tr>
                            <tr>
                                <td>Default Picture</td>
                                <td>
                                    @if($marketing_material->file_path != null)
                                        <img src="{{ asset($marketing_material->file_path) }}" width="300">
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
