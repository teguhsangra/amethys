@extends('layouts.print')
@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-6 text-left">

        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid pull-right" src="{{asset('company-logo.png')}}" width="200">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
            <br>
            <br>
            <br>
            <p>{{ $invoice->customer->name }}</p>
            <p>
                @if($invoice->address_status == "customer")
                    {!! $invoice->customer->address !!}
                @else
                    {!! $invoice->location->address !!}
                @endif
            </p>
            <br>
            @if($invoice->reason != null)
                <h5><b>Reason</b></h5>
                <p>{{ $invoice->reason }}</p>
            @endif
        </div>
        <div class="col-md-6 text-right">
            <h3>&nbsp;</h3>
            <br>
            <table style="float: right;" width="100%">
                <tr>
                    <td colspan="2">{{ $company_name }}</td>
                </tr>
                <tr>
                    <td class="text-left">Invoice No:</td>
                    <td class="text-right">{{$invoice->code}}</td>
                </tr>
                @if($invoice->has_po == 'Y')
                <tr>
                    <td class="text-left">PO No:</td>
                    <td class="text-right">{{$invoice->po_number}}</td>
                </tr>
                @endif
                <tr>
                    <td class="text-left">Date : </td>
                    <td class="text-right">{{date("j F Y",strtotime($invoice->invoice_date))}}</td>
                </tr>
                <tr>
                    <td class="text-left">Due Date : </td>
                    <td class="text-right">{{date("j F Y",strtotime($invoice->due_date))}}</td>
                </tr>
            </table>
        </div>
    </div>
    <br>
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
                                    if($booking_detail->room->has_service_charge == "Y"){
                                        $detail = $detail;
                                    }
                                }else if($booking_detail->product_id != null){
                                    $detail = $booking_detail->product->name;
                                    if($booking_detail->product->has_service_charge == "Y"){
                                        $detail = $detail;
                                    }
                                }else if($booking_detail->package_id != null){
                                    $detail = $booking_detail->package->name;
                                    if($booking_detail->package->has_service_charge == "Y"){
                                        $detail = $detail;
                                    }
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
                                if($order_detail->product->has_service_charge == "Y"){
                                    $detail = $detail;
                                }

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
                                    @if($invoice->remarks != 'null')
                                     <br>
			                         {{ $invoice->remarks }}
			                         @endif
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
                                    IDR {{number_format($detail_price, 0, '.', ',') }} <br>
                                </td>
                                <td class="text-right">
                                    IDR {{number_format($invoice_detail->detail_price, 0, '.', ',')}} <br>
                                </td>
                            </tr>
                        @else
                            @php
                                $found = false;
                                for($i=0; $i < sizeof($detail_invoice); $i++){
                                    if($invoice_detail->booking_detail_id != null){
                                        $is_same = false;
                                        if($invoice_detail->booking_detail->booking->type == "product"){
                                            if($detail_invoice[$i]['detail_invoice_id'] == $invoice_detail->booking_detail->product_id){
                                                $is_same = true;
                                            }
                                        }else if($invoice_detail->booking_detail->booking->type == "room"){
                                            if($detail_invoice[$i]['detail_invoice_id'] == $invoice_detail->booking_detail->room_id){
                                                $is_same = true;
                                            }
                                        }else if($invoice_detail->booking_detail->booking->type == "package"){
                                            if($detail_invoice[$i]['detail_invoice_id'] == $invoice_detail->booking_detail->package_id){
                                                $is_same = true;
                                            }
                                        }else{
                                            // Do Nothing
                                        }
                                        if($is_same){
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
                                        if($detail_invoice[$i]['detail_invoice_id'] == $invoice_detail->order_detail->product_id){        
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
                                                    if($detail_invoice[$i]['detail_price'] == $detail_price){
                                                        $detail_invoice[$i]['quantity'] = $detail_invoice[$i]['quantity'] + $quantity;
                                                    }else{
                                                        $detail_invoice[$i]['detail_price'] = $detail_invoice[$i]['detail_price'] * $detail_invoice[$i]['quantity'];
                                                        $detail_invoice[$i]['quantity'] = 1;
                                                        $detail_invoice[$i]['detail_price'] = $detail_invoice[$i]['detail_price'] + ($detail_price * $quantity);
                                                    }
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
                                        $new_array['detail'] = " ".$detail;
                                    }else{
                                        $new_array['detail'] = $detail;
                                    }

                                    $new_array['start_periode'] = $start_periode;
                                    $new_array['end_periode'] = $end_periode;
                                    $new_array['quantity'] = $quantity;
                                    $new_array['detail_price'] = $detail_price;
                                    $new_array['detail_service_charge'] = $detail_service_charge;
                                    $new_array['detail_tax_price'] = $detail_tax_price;
                                    $new_array['detail_invoice_source_id'] = null;
									$new_array['remarks'] = $invoice_detail->remarks;

                                    if($invoice_detail->booking_detail_id != null){
                                        $new_array['detail_invoice_source_id'] = $invoice_detail->booking_detail_id;
                                        if($invoice_detail->booking_detail->booking->type == "product"){
                                            $new_array['detail_invoice_id'] = $invoice_detail->booking_detail->product_id;
                                        }else if($invoice_detail->booking_detail->booking->type == "room"){
                                            $new_array['detail_invoice_id'] = $invoice_detail->booking_detail->room_id;
                                        }else if($invoice_detail->booking_detail->booking->type == "package"){
                                            $new_array['detail_invoice_id'] = $invoice_detail->booking_detail->package_id;
                                        }else{

                                        }

                                        if($detail_price == 0){
                                            $new_array['detail_invoice_type'] = "free_booking";
                                        }else{
                                            $new_array['detail_invoice_type'] = "booking";
                                        }
                                    }elseif($invoice_detail->order_detail_id != null){
                                        $new_array['detail_invoice_source_id'] = $invoice_detail->order_detail_id;
                                        $new_array['detail_invoice_id'] = $invoice_detail->order_detail->product_id;

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
                                 @if($detail_invoice[$i]['remarks'] != 'null')
                                 <br>
                                {{$detail_invoice[$i]['remarks'] }}
                                @endif
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
                                IDR {{number_format($detail_invoice[$i]['detail_price'], 0, '.', ',') }} <br>
                            </td>
                            <td class="text-right">
                                IDR {{number_format($detail_invoice[$i]['quantity'] * ($detail_invoice[$i]['detail_price']), 0, '.', ',')}} <br>
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
                                IDR {{number_format($invoice->total_price, 0, '.', ',') }} <br>
                            </td>
                            <td class="text-right">
                                IDR {{number_format($invoice->total_price, 0, '.', ',')}} <br>
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
                                IDR {{number_format($invoice->total_price, 0, '.', ',') }} <br>
                            </td>
                            <td class="text-right">
                                IDR {{number_format($invoice->total_price, 0, '.', ',')}} <br>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="5"><b>Sub Total</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_price, 0, '.', ',')}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Service Charge</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_service_charge, 0, '.', ',')}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Total</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_price + $invoice->total_service_charge, 0, '.', ',')}}</b></td>
                    </tr>
                     <tr>
                        <td colspan="5"><b>Tax Base for VAT</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_price_on_tax, 0, '.', ',')}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>VAT</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_tax_price, 0, '.', ',')}}</b></td>
                    </tr>
                    <!-- <tr>
                        <td colspan="5"><b>Stamp Duty</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->stamp_duty, 0, '.', ',')}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5"><b>Round Price</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->round_price, 0, '.', ',')}}</b></td>
                    </tr> -->
                    <tr>
                        <td colspan="5"><b>Deposit</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_deposit, 0, '.', ',')}}</b></td>
                    </tr>
                    @if($invoice->has_deduction == 'Y')
                    <tr>
                        <td colspan="5"><b>Deduction Withholding Tax</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->deduction_price, 0, '.', ',')}}</b></td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="5"><b>Grand Total</b></td>
                        <td class="text-right"><b>IDR {{number_format($invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price + $invoice->total_deposit - $invoice->deduction_price, 0, '.', ',')}}</b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if($invoice->bank_account_id != null)
                <table width="100%">
                    <tr>
                        <td width="50%">
                            <h4><b>Account Details : </b></h4>
                            <p>Beneficiary Name: <b>{{$invoice->bank_account->account_name}}</b></p>
                        </td>
                        <td width="50%">
                        @if($invoice->customer->virtual_account_no != null)
                            <h4><b>Virtual Account : {{ $invoice->customer->virtual_account_no }} ({{ $invoice->customer->virtual_account_bank }})</b></h4>
                        @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>
                                {{$invoice->bank_account->bank_name}} (IDR) <br>
                                Acc No : {{$invoice->bank_account->account_no}} <br>
                                Branch : {{$invoice->bank_account->branch_code}} <br>
                                Swift Code : {{$invoice->bank_account->swift_code}}
                            </p>
                        </td>
                        <td>
                            &nbsp;
                        </td>
                    </tr>
                </table>
                <br>
            @endif
            <p>
                Please send proof of your payments, and include Invoice Number stated above.<br>
                Any dispute or correction must be informed to us within 5 days from this Invoice Date.
            </p>
            <p><b>This Invoice is automatically generated, no signature is required</b></p>
        </div>
    </div>
</div>
@endsection
