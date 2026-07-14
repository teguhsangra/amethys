@extends('layouts.app')
@section('title')
Rakomsis Sales Target - {{ $sales_target->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Sales Target</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Sales Target
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Status</td>
                                <td>
                                    {{ $sales_target->status->name }}
                                    @if($sales_target->discard_or_cancel_reason != null)
                                        <br>{{ $sales_target->discard_or_cancel_reason }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>{{ $sales_target->code }}</td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td>{{ $sales_target->employee->name }}</td>
                            </tr>
                            <tr>
                                <td>Year & Month</td>
                                <td>
                                    {{ $sales_target->year }}, {{ date("F", strtotime(date('Y').'-'.$sales_target->month.'-01')) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Total Target</td>
                                <td>{{ number_format($sales_target->total_target, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection