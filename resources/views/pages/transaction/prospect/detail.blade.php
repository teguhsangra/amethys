@extends('layouts.app')
@section('title')
Rakomsis Prospect - {{ $prospect->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Prospect</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Prospect
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Status</td>
                                <td>
                                    {{ $prospect->status->name }}
                                    @if($prospect->discard_or_cancel_reason != null)
                                        <br>{{ $prospect->discard_or_cancel_reason }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td>{{ $prospect->code }}</td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td>{{ $prospect->employee->name }}</td>
                            </tr>
                            <tr>
                                <td>Referral</td>
                                <td>
                                @if($prospect->referral_id != null)
                                    {{ $prospect->referral->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Agent</td>
                                <td>
                                @if($prospect->agent_id != null)
                                    {{ $prospect->agent->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Customer</td>
                                <td>{{ $prospect->customer->name }}</td>
                            </tr>
                            <tr>
                                <td>Contact</td>
                                <td>
                                @if($prospect->contact_id != null)
                                    {{ $prospect->contact->name }}
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Notes</td>
                                <td>{!! $prospect->notes !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection