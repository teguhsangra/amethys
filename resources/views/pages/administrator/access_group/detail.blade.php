@extends('layouts.app')
@section('title')
Rakomsis Access Group - {{ $access_group->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Access Group</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Access Group
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td colspan="2">Code</td>
                                <td colspan="2">{{ $access_group->code }}</td>
                            </tr>
                            <tr>
                                <td colspan="2">Name</td>
                                <td colspan="2">{{ $access_group->name }}</td>
                            </tr>
                            <tr>
                                <td>Modules</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                        @foreach($access_group->module as $module)
                                            <tr>
                                                <td>{{ $module->name }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                                <td>Users</td>
                                <td>
                                    <table class="table">
                                        <tbody>
                                        @foreach($access_group->user as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
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
