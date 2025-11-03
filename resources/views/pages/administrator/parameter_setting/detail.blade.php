@extends('layouts.app')
@section('title')
Rakomsis Parameter Setting - {{ $parameter_setting->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Parameter Setting</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Parameter Setting
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Name</td>
                                <td>{{ $parameter_setting->name }}</td>
                            </tr>
                            <tr>
                                <td>Int Value</td>
                                <td>{{ $parameter_setting->int_value }}</td>
                            </tr>
                            <tr>
                                <td>Double Value</td>
                                <td>{{ $parameter_setting->double_value }}</td>
                            </tr>
                            <tr>
                                <td>String Value</td>
                                <td>{{ $parameter_setting->string_value }}</td>
                            </tr>
                            <tr>
                                <td>Text Value</td>
                                <td>{!! $parameter_setting->text_value !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection