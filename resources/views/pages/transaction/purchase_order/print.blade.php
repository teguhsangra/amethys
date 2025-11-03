@extends('layouts.print')
@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-6 text-left">
            <h4>Purchase Order</h4>
        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid pull-right" src="{{asset('company-logo.png')}}" width="200">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" width="100%" style="width:100%">
                <tbody>
                    <tr>
                        <td>Status</td>
                        <td>
                            {{ $purchase_order->status->name }}
                            @if($purchase_order->discard_or_cancel_reason != null)
                                <br>{{ $purchase_order->discard_or_cancel_reason }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Code</td>
                        <td>{{ $purchase_order->code }}</td>
                    </tr>
                    <tr>
                        <td>Booking</td>
                        <td>{{ $purchase_order->booking->code }}</td>
                    </tr>
                    <tr>
                        <td>Customer</td>
                        <td>{{ $purchase_order->booking->customer->name }}</td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td>{!! $purchase_order->notes !!}</td>
                    </tr>
                    <tr>
                        <td>Detail</td>
                        <td>
                            <table class="table table-bordered">
                                <thead>
                                    <th>Name</th>
                                    <th>Detail Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                @foreach($purchase_order->purchase_order_detail as $purchase_order_detail)
                                    <tr>
                                        <td>{{ $purchase_order_detail->name }}</td>
                                        <td>{{ number_format($purchase_order_detail->detail_price, 0,',','.') }}</td>
                                        <td>{{ number_format($purchase_order_detail->quantity, 0,',','.') }}</td>
                                        <td class="text-right">{{ number_format($purchase_order_detail->detail_price * $purchase_order_detail->quantity, 0,',','.') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <td class="text-right">{{ number_format($purchase_order->total_price, 0,',','.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>Payment Receipt</td>
                        <td>
                            <img class="img img-responsive" src="{{ asset($purchase_order->payment_receipt) }}" alt="">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
