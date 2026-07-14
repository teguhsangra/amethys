@extends('layouts.app')
@section('title')
Rakomsis Bank Account - {{ $bank_account->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Bank Account</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Bank Account
                    </a>
                </div>
                <div class="material-datatables">
                    <table class="table table-bordered" width="100%" style="width:100%">
                        <tbody>
                            <tr>
                                <td>Account No</td>
                                <td>{{ $bank_account->account_no }}</td>
                            </tr>
                            <tr>
                                <td>Account Name</td>
                                <td>{{ $bank_account->account_name }}</td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td>{{ $bank_account->bank_name }}</td>
                            </tr>
                            <tr>
                                <td>Branch Code</td>
                                <td>{{ $bank_account->branch_code }}</td>
                            </tr>
                            <tr>
                                <td>Swift Code</td>
                                <td>{{ $bank_account->swift_code }}</td>
                            </tr>
                            <tr>
                                <td>Currency Code</td>
                                <td>{{ $bank_account->currency_code }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection