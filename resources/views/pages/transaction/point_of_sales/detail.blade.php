@extends('layouts.app')
@section('title')
Rakomsis Point Of Sales - {{ $order->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>
                    <h4 class="card-title">Detail Point Of Sales</h4>
                </div>

            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Point Of Sales
                    </a>
                </div>
                <Br>
                <ul class="nav nav-pills nav-pills-warning" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active show" data-toggle="tab" href="#detail" role="tablist">
                        Detail Point Of Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#invoice" role="tablist">
                        Invoice Point Of Sales
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#payment" role="tablist">
                        Payment Point Of Sales
                        </a>
                    </li>
                </ul>
                <div class="tab-content tab-space">
                    <div class="tab-pane active show" id="detail">
                        <div class="row">
                            <div class="col-md-12">
                                <a onclick="print('{{ url($print_url) }}')" class="btn btn-primary pull-left text-white">
                                    <i class="fa fa-print"></i> Print
                                </a>
                            </div>
                        </div>
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

                    <div class="tab-pane" id="invoice">
                        @if($invoice != null)
                            <div class="row">
                                <div class="col-md-12">
                                    <a onclick="print('{{ url($invoice_url) }}')" class="btn btn-primary pull-left text-white">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h4 class="card-title">Customer Detail</h4>
                                        </div>
                                        <div class="card-body">
                                            <table>
                                                <tr>
                                                    <td><h5><b>Bill To</b></h5></td>
                                                </tr>
                                                <tr>
                                                    <td>{{$invoice->customer->name}}</td>
                                                </tr>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Invoice</h4>
                                        </div>
                                        <div class="card-body">
                                            <table style="float:right;">
                                                <tr class="text-right">
                                                    <td>Date</td>
                                                    <td>:</td>
                                                <td>{{date("j F Y",strtotime($invoice->created_at))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Due Date</td>
                                                    <td>:</td>
                                                    <td>{{date("j F Y",strtotime($invoice->due_date))}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-border">
                                        <thead>
                                            <th colspan="2">Description</th>
                                            <th class="text-center">Period</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Total</th>
                                        </thead>
                                        <tbody>
                                            @php
                                                $detail_invoice = array();
                                                $detail_invoice_id = null;
                                                $detail_invoice_type = null;
                                            @endphp
                                            @foreach($invoice->invoice_detail as $no => $invoice_detail)
                                                @php
                                                    $detail = "";
                                                    $start_periode = "";
                                                    $end_periode = "";
                                                    $quantity = 1;
                                                    $detail_price = $invoice_detail->detail_price;
                                                    $detail_service_charge = $invoice_detail->detail_service_charge;
                                                    $detail_tax_price = $invoice_detail->detail_tax_price;

                                                    if($invoice_detail->booking_detail_id != null){
                                                        $booking_detail = $invoice_detail->booking_detail;

                                                        if($booking_detail->room_id != null){
                                                            $detail = $booking_detail->room->room_number;
                                                        }else if($booking_detail->product_id != null){
                                                            $detail = $booking_detail->product->name;
                                                        }else if($booking_detail->package_id != null){
                                                            $detail = $booking_detail->package->name;
                                                        }
                                                        $start_periode = date('j M Y', strtotime($booking_detail->start_date));
                                                        $end_periode = date('j M Y', strtotime($booking_detail->end_date));

                                                        $detail_price = $booking_detail->detail_price;
                                                        $detail_service_charge = $booking_detail->detail_service_charge;
                                                        $detail_tax_price = $booking_detail->detail_tax_price;
                                                        $quantity = $booking_detail->quantity * $booking_detail->length_of_detail;
                                                    }else if($invoice_detail->order_detail_id != null){
                                                        $order_detail = $invoice_detail->order_detail;

                                                        $detail = $order_detail->product->name;
                                                        if($order_detail->start_date != null){
                                                            $start_periode = date('j M Y', strtotime($order_detail->start_date));
                                                        }

                                                        if($order_detail->end_date != null){
                                                            $end_periode = date('j M Y', strtotime($order_detail->end_date));
                                                        }

                                                        $detail_price = $order_detail->detail_price;
                                                        $detail_service_charge = $order_detail->detail_service_charge;
                                                        $detail_tax_price = $order_detail->detail_tax_price;
                                                        $quantity = $order_detail->quantity * $order_detail->length_of_term;
                                                    }else if($invoice_detail->booking_cancellation_id != null){
                                                        $detail = "Cancellation";
                                                    }
                                                @endphp

                                                @if($invoice->detail_status == "Y")
                                                    <tr>
                                                        <td colspan="2">
                                                            {{ $detail }}
                                                        </td>
                                                        <td class="text-center">
                                                            @if($end_periode == "")
                                                                {{ $start_periode }}
                                                            @else
                                                                {{ $start_periode }} - {{ $end_periode }}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $quantity }}
                                                        </td>
                                                        <td class="text-right">
                                                            IDR {{number_format($detail_price, 0, ',', '.') }} <br>
                                                        </td>
                                                        <td class="text-right">
                                                            IDR {{number_format($invoice_detail->detail_price, 0, ',', '.')}} <br>
                                                        </td>
                                                    </tr>
                                                @else
                                                    @php
                                                        $found = false;
                                                        for($i=0; $i < sizeof($detail_invoice); $i++){
                                                            if($invoice_detail->booking_detail_id != null){
                                                                if($detail_invoice[$i]['detail_invoice_id'] == $invoice_detail->booking_detail->booking_id){
                                                                    if($detail_price == 0){
                                                                        if($detail_invoice[$i]['detail_invoice_type'] == "free_booking"){
                                                                            $detail_invoice[$i]['end_periode'] = $end_periode;
                                                                            $detail_invoice[$i]['quantity'] = $detail_invoice[$i]['quantity'] + $quantity;
                                                                            $found = true;
                                                                            break;
                                                                        }
                                                                    }else{
                                                                        if($detail_invoice[$i]['detail_invoice_type'] == "booking"){
                                                                            $detail_invoice[$i]['end_periode'] = $end_periode;
                                                                            $detail_invoice[$i]['quantity'] = $detail_invoice[$i]['quantity'] + $quantity;
                                                                            $found = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }elseif($invoice_detail->order_detail_id != null){
                                                                if($detail_invoice[$i]['detail_invoice_id'] == $invoice_detail->order_detail->order_id){
                                                                    if($detail_price == 0){
                                                                        if($detail_invoice[$i]['detail_invoice_type'] == "free_order"){
                                                                            $detail_invoice[$i]['end_periode'] = $end_periode;
                                                                            $detail_invoice[$i]['quantity'] = $detail_invoice[$i]['quantity'] + $quantity;
                                                                            $found = true;
                                                                            break;
                                                                        }
                                                                    }else{
                                                                        if($detail_invoice[$i]['detail_invoice_type'] == "order"){
                                                                            $detail_invoice[$i]['end_periode'] = $end_periode;
                                                                            $detail_invoice[$i]['quantity'] = $detail_invoice[$i]['quantity'] + $quantity;
                                                                            $found = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        if(!$found){
                                                            $new_array = array();

                                                            if($detail_price == 0){
                                                                $new_array['detail'] = "**) ".$detail;
                                                            }else{
                                                                $new_array['detail'] = $detail;
                                                            }

                                                            $new_array['start_periode'] = $start_periode;
                                                            $new_array['end_periode'] = $end_periode;
                                                            $new_array['quantity'] = $quantity;
                                                            $new_array['detail_price'] = $detail_price;
                                                            $new_array['detail_service_charge'] = $detail_service_charge;
                                                            $new_array['detail_tax_price'] = $detail_tax_price;

                                                            if($invoice_detail->booking_detail_id != null){
                                                                $new_array['detail_invoice_id'] = $invoice_detail->booking_detail->booking_id;

                                                                if($detail_price == 0){
                                                                    $new_array['detail_invoice_type'] = "free_booking";
                                                                }else{
                                                                    $new_array['detail_invoice_type'] = "booking";
                                                                }
                                                            }elseif($invoice_detail->order_detail_id != null){
                                                                $new_array['detail_invoice_id'] = $invoice_detail->order_detail->order_id;

                                                                if($detail_price == 0){
                                                                    $new_array['detail_invoice_type'] = "free_order";
                                                                }else{
                                                                    $new_array['detail_invoice_type'] = "order";
                                                                }
                                                            }

                                                            array_push($detail_invoice, $new_array);
                                                        }
                                                    @endphp
                                                @endif
                                            @endforeach
                                            @for($i=0; $i < sizeof($detail_invoice); $i++)
                                                <tr>
                                                    <td colspan="2">
                                                        {{ $detail_invoice[$i]['detail'] }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($detail_invoice[$i]['end_periode'] == "")
                                                            {{ $detail_invoice[$i]['start_periode'] }}
                                                        @else
                                                            {{ $detail_invoice[$i]['start_periode'] }} - {{ $detail_invoice[$i]['end_periode'] }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $detail_invoice[$i]['quantity'] }}
                                                    </td>
                                                    <td class="text-right">
                                                        IDR {{number_format($detail_invoice[$i]['detail_price'], 0, ',', '.') }} <br>
                                                    </td>
                                                    <td class="text-right">
                                                        IDR {{number_format($detail_invoice[$i]['quantity'] * ($detail_invoice[$i]['detail_price']), 0, ',', '.')}} <br>
                                                    </td>
                                                </tr>
                                            @endfor
                                            @if($invoice->booking_id != null)
                                                <tr>
                                                    <td colspan="2">
                                                        @if($invoice->booking->type == "product")
                                                            {{ $invoice->booking->product->name }}
                                                        @elseif($invoice->booking->type == "room")
                                                            @foreach($invoice->booking->rooms as $room)
                                                                {{ $room->room_number }} <br>
                                                            @endforeach
                                                        @elseif($invoice->booking->type == "package")
                                                            @foreach($invoice->booking->packages as $package)
                                                                {{ $package->name }} <br>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{ date('j M Y', strtotime($invoice->booking->start_date)) }} - {{ date('j M Y', strtotime($invoice->booking->end_date)) }}
                                                    </td>
                                                    <td class="text-center">
                                                        1
                                                    </td>
                                                    <td class="text-right">
                                                        IDR {{number_format($invoice->total_price, 0, ',', '.') }} <br>
                                                    </td>
                                                    <td class="text-right">
                                                        IDR {{number_format($invoice->total_price, 0, ',', '.')}} <br>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if($invoice->order_id != null)
                                                <tr>
                                                    <td colspan="2">
                                                        @foreach($invoice->order->order_detail as $order_detail)
                                                            {{ $order_detail->product->name }} <br>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center">
                                                        {{ date('j M Y', strtotime($invoice->order->order_date)) }}
                                                    </td>
                                                    <td class="text-center">
                                                        1
                                                    </td>
                                                    <td class="text-right">
                                                        IDR {{number_format($invoice->total_price, 0, ',', '.') }} <br>
                                                    </td>
                                                    <td class="text-right">
                                                        IDR {{number_format($invoice->total_price, 0, ',', '.')}} <br>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="5"><b>Sub Total</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->total_price, 0, ',', '.')}}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>Service Charge</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->total_service_charge, 0, ',', '.')}}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>Total</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->total_price + $invoice->total_service_charge, 0, ',', '.')}}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>Tax</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->total_tax_price, 0, ',', '.')}}</b></td>
                                            </tr>
                                            <!-- <tr>
                                                <td colspan="5"><b>Stamp Duty</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->stamp_duty, 0, ',', '.')}}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"><b>Round Price</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->round_price, 0, ',', '.')}}</b></td>
                                            </tr> -->
                                            <tr>
                                                <td colspan="5"><b>Grand Total</b></td>
                                                <td class="text-right"><b>IDR {{number_format($invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty, 0, ',', '.')}}</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p style="text-align: justify;">
                                        <b>*)</b> Subject to service charge
                                    </p>
                                    <p style="text-align: justify;">
                                        <b>**)</b> Free
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                @if($invoice->bank_account_id != null)
                                <div class="col-md-12">
                                    <h4><b>Account Information : </b></h4>
                                    <p>Beneficiary Name: <b>{{$invoice->bank_account->account_name}}</b></p>
                                    <br>
                                    <table width="100%">
                                        <tr>
                                            <td>
                                                <p>
                                                    {{$invoice->bank_account->bank_name}} (IDR) <br>
                                                    Acc No : {{$invoice->bank_account->account_no}} <br>
                                                    Branch : {{$invoice->bank_account->branch_code}} <br>
                                                    Swift Code : {{$invoice->bank_account->swift_code}}
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <p>
                                        Please send proof of your payments, and include Invoice Number stated above.<br>
                                        <b>Payment should be made to the Exact Full Invoiced Amount and all bank charges will be borne by the Client.</b> <br>
                                        Any dispute or correction must be informed within 5 days from receipt.
                                    </p>
                                    <br>
                                    <br>
                                    <div class="col-md-6">
                                        &nbsp;
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-border pull-right" width="100%">
                                            <tr>
                                                <td width="100%">
                                                    <p>{{$invoice->company->name}}</p>
                                                    <br><br><br><br><br><br><br>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>{{$invoice->company->proforma_signatory}}</b></td>
                                            </tr>
                                            <tr>
                                                <td>Authorized Signature</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>This order doesn't has any invoice</h3>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane" id="payment">
                        @if($invoice != null)
                            <div class="row">
                                <div class="col-md-12">
                                    <a onclick="print('{{ url($payment_url) }}')" class="btn btn-primary pull-left text-white">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <div class="card card-info">
                                        <div class="card-header">
                                            <h4 class="card-title">Payment Summary</h4>
                                        </div>
                                        <div class="card-body">
                                            <table>

                                                <tr>
                                                    <td>Payment Date</td>
                                                    <td>:</td>
                                                    <td>{{date("j F Y",strtotime($payment->payment_date))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Payment Paid</td>
                                                    <td>:</td>
                                                    <td>{{$payment->code}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Payment from</td>
                                                    <td>:</td>
                                                    <td>{{$payment->customer->name}}</td>
                                                </tr>
                                            </table>
                                            <br>
                                            <strong>Total Amount Received</strong> <font style="text-align: justify; margin-left:6.5%">:&emsp;IDR {{number_format($payment->total_payment,0,',','.')}}</font>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title">Payment Receipt</h4>
                                        </div>
                                        <div class="card-body">
                                            <table style="float:right;">
                                                <tr>
                                                    <td>Date</td>
                                                    <td>:</td>
                                                    <td>{{date("j F Y",strtotime($payment->payment_date))}}</td>
                                                </tr>
                                                <tr>
                                                    <td>No.</td>
                                                    <td>:</td>
                                                    <td>{{$payment->code}}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <p>Detail Payment</p>
                                </div>
                                <br><br>
                                <div class="col-md-12">
                                    <div class="card  card-primary">
                                        <div class="card-header">
                                            Payment Summary
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <td class="text-center"><strong>Invoice/Deposit Date</strong></td>
                                                        <td class="text-center"><strong>Invoice/Deposit No.</strong></td>
                                                        <td class="text-center"><strong>Payment Date</strong></td>
                                                        <td class="text-center"><strong>Payment Type</strong></td>
                                                        <td class="text-center"><strong>Amount</strong></td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($payment->payment_allocation as $payment_details)
                                                    <tr>
                                                        <td class="text-center">
                                                            @if($payment_details->invoice_id != null)
                                                            {{date("j F Y",strtotime($payment_details->invoice->created_at))}}
                                                            @endif
                                                            @if($payment_details->deposit_id != null)
                                                            {{date("j F Y",strtotime($payment_details->deposit->created_at))}}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if($payment_details->invoice_id != null)
                                                                {{$payment_details->invoice->code}}
                                                            @else
                                                                {{$payment_details->deposit->code}}
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            {{date("j F Y",strtotime($payment_details->payment->payment_date))}}
                                                        </td>
                                                        <td class="text-center">
                                                            @foreach($payment->payment_detail as $payment_detail)
                                                            {{$payment_detail->payment_type}}
                                                            @endforeach
                                                        </td>


                                                        <td class="text-center">IDR
                                                                {{number_format($payment_details->total_need,0,',','.')}}
                                                        </td>

                                                    </tr>
                                                    @endforeach
                                                    <tr class="success">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-center"><b>Grand Total</b></td>
                                                        <td class="text-center"><b>IDR {{number_format($payment->total_payment,0,',','.')}}</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>This order doesn't has any payment</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function print(link){
        var printWindow = window.open(link);
        var printAndClose = function () {
            if (printWindow.document.readyState == 'complete') {
                clearInterval(sched);
                printWindow.print();
                printWindow.close();
            }
        }
        var sched = setInterval(printAndClose, 800);
    }
</script>
@endsection
