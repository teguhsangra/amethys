@extends('layouts.print')
@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-6 text-left">
            <h4>{{ $order->code }}</h4>
        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid pull-right" src="{{asset('company-logo.png')}}" width="200">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #007bff !important;color: #FFF;">
                    <td><h5>Customer Detail</h5></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" class="detail_booking table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="10%">Customer Name</td>
                                <td width="5%">:</td>
                                <td width="50%">{{$order->customer->name}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Address</td>
                                <td>:</td>
                                <td>{!! $order->customer->address !!}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #007bff !important;color: #FFF;">
                    <td colspan="5"><h5>FEES</h5></td>
                </tr>
                <tr>
                    <td width="20%"><b>Description</b></td>
                    <td width="20%"><b>Term</b></td>
                    <td width="15%"><b>Quantity</b></td>
                    <td width="20%"><b>Detail Price</b></td>
                    <td width="25%"><b>Total </b></td>
                </tr>
                @php
                    $total_price = 0;
                @endphp
                @foreach($order->order_detail as $order_detail)
                    @php
                        $detail_price = round($order_detail->detail_price);

                        if($order->tax_status == "include"){
                            $detail_price = $detail_price / (1 + $service_charge);
                        }
                        $sub_total = $detail_price * $order_detail->quantity * $order_detail->length_of_term;
                        $total_price = $total_price + $sub_total;
                    @endphp
                    <tr>
                        <td style="padding-top: 10px;padding-bottom: 10px;">
                            <b>@if($order_detail->product->has_service_charge == "Y") *) @endif</b> {{ $order_detail->product->name }}
                        </td>
                        <td style="padding-top: 10px;padding-bottom: 10px;" class="text-center">
                            @if($order_detail->product->price_type == 'single')
                                -
                            @else
                                {{ $order_detail->length_of_term }}
                                @if($order_detail->product->price_type == 'yearly')
                                    Year(s)
                                @elseif($order_detail->product->price_type == 'monthly')
                                    Month(s)
                                @elseif($order_detail->product->price_type == 'daily')
                                    Day(s)
                                @elseif($order_detail->product->price_type == 'hourly')
                                    Hours(s)
                                @endif
                            @endif
                            @if($order_detail->start_date != null && $order_detail->end_date != null)
                                <br>
                                {{ date('j F Y', strtotime($order_detail->start_date)).' - '.date('j F Y', strtotime($order_detail->end_date)) }}
                            @endif
                        </td>
                        <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">{{ $order_detail->quantity}}</td>
                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">Rp {{number_format($detail_price, 0, ',', '.')}}</td>
                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">Rp {{ number_format($sub_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    @php
                        $discount_price = $total_price - $order->total_price;
                    @endphp
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Discount @if($order->usable_discount == "percentage")<span class="text-right">({{number_format($order->discount_percentage, 0, ',', '.')}}%)</span>@endif</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        <b>(-) Rp {{ number_format($discount_price, 0, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Total After Discount</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Rp {{ number_format($order->total_price, 0, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Service Charge <span class="text-right">({{ number_format($service_charge * 100, 0, ',', '.') }}%)</span>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($order->total_service_charge, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Tax <span class="text-right">({{ number_format($tax_percentage * 100, 0, ',', '.') }}%)</span>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($order->total_tax_price, 0, ',', '.') }}
                    </td>
                </tr>
                <!-- <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Rounded Price
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($order->round_price, 0, ',', '.') }}
                    </td>
                </tr> -->
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3" class=""></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;" class="">
                        <b>Grand Total</b>
                    </td>

                    <td style="padding-top: 1px;padding-bottom: 1px;" class=" text-right"><b>
                        Rp {{ number_format($order->total_price + $order->total_service_charge + $order->total_tax_price + $order->round_price, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
            <p style="text-align: justify;">
                <b>*)</b> Item has service charge
            </p>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #007bff !important;color: #FFF;">
                    <td><h5>Remarks</h5></td>
                </tr>
                <tr>
                    <td>
                        {!! $order->remarks !!}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
