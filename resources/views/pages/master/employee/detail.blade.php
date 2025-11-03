@extends('layouts.app')
@section('title')
Rakomsis Employee - {{ $employee->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Employee</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Employee
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Code</td>
                                <td>{{ $employee->code }}</td>
                            </tr>
                            <tr>
                                <td>Name</td>
                                <td>{{ $employee->name }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $employee->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $employee->phone }}</td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td>{{ $employee->role }}</td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td>{{ $employee->department }}</td>
                            </tr>
                            <tr>
                                <td>Head</td>
                                <td>
                                @if($employee->parent_id != null)
                                    {{ $employee->this_parent->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Subordinate</td>
                                <td>
                                    <table class="table" style="width:100%">
                                        <thead>
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                        </thead>
                                        <tbody>
                                        @foreach($employee->this_child as $child)
                                            <tr>
                                                <td>{{ $child->code }}</td>
                                                <td>{{ $child->name }}</td>
                                                <td>{{ $child->email }}</td>
                                                <td>{{ $child->phone }}</td>
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