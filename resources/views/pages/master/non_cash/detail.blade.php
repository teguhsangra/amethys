@extends('layouts.app')
@section('title')
Rakomsis Non Cash - {{ $non_cash->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Non Cash</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Non Cash
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $non_cash->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $non_cash->name }}</td>
                            </tr>
                            <tr>
                                <td>Has Bank</td>
                                <td>{{ $non_cash->has_bank }}</td>
                            </tr>
                            <tr>
                                <td>Has Card</td>
                                <td>{{ $non_cash->has_card }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection