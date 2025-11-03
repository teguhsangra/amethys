@extends('layouts.app')
@section('title')
Rakomsis Notification - {{ $notification->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Notification</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Notification
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Url</td>
                                <td><a href="{{ url($notification->url) }}">Go To Url</a></td>
                            </tr>
                            <tr>
                                <td>Header</td>
                                <td>{{ $notification->header }}</td>
                            </tr>
                            <tr>
                                <td>Body</td>
                                <td>{!! $notification->body !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection