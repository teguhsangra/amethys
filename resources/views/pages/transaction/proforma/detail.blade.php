@extends('layouts.app')
@section('title')
    Rakomsis Proforma - {{ $proforma->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Proforma</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Proforma
                    </a>
                    <a onclick="print('{{ url($print_url) }}')" class="btn btn-primary pull-right text-white">
                        <i class="fa fa-print"></i> Print
                    </a>
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
                                        <td>{{ $proforma->customer->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>{!! $proforma->customer->address !!}</td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 text-right">
                        <div class="card card-danger">
                            <div class="card-header">
                                <h4 class="card-title">Proforma</h4>
                            </div>
                            <div class="card-body">
                                <table style="float:right;">
                                    <tr>
                                        <td colspan="3">{{ $company_name }}</td>
                                    </tr>
                                    <tr class="text-right">
                                        <td>Date</td>
                                        <td>:</td>
                                        <td>{{date("j M Y",strtotime($proforma->proforma_date))}}</td>
                                    </tr>
                                    <tr>
                                        <td>Due Date</td>
                                        <td>:</td>
                                        <td>{{date("j M Y",strtotime($proforma->due_date))}}</td>
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
			                        $detail_proforma = array();
			                        $detail_proforma_id = null;
			                        $detail_proforma_type = null;
			                    @endphp
			                    @foreach($proforma->proforma_detail as $no => $proforma_detail)
			                        @php
			                            $detail = "";
			                            $start_periode = "";
			                            $end_periode = "";
			                            $quantity = 1;
			                            $detail_price = $proforma_detail->detail_price;
			                            $detail_service_charge = $proforma_detail->detail_service_charge;
			                            $detail_tax_price = $proforma_detail->detail_tax_price;

			                            if($proforma_detail->booking_detail_id != null){
			                                $booking_detail = $proforma_detail->booking_detail;

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
			                            }else if($proforma_detail->order_detail_id != null){
			                                $order_detail = $proforma_detail->order_detail;

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
			                            }else if($proforma_detail->booking_cancellation_id != null){
			                                $detail = "Cancellation";
			                            }
			                        @endphp

			                        @if($proforma->detail_status == "Y")
			                            <tr>
			                                <td colspan="2">
			                                    {{ $detail }}
			                                     @if($proforma_detail->remarks != 'null')
			                                     <br>
						                         {{ $proforma_detail->remarks }}
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
			                                    IDR {{number_format($proforma_detail->detail_price, 0, '.', ',')}} <br>
			                                </td>
			                            </tr>
			                        @else
			                            @php
			                                $found = false;
			                                for($i=0; $i < sizeof($detail_proforma); $i++){
			                                
			                                    if($proforma_detail->booking_detail_id != null){
			                                        $is_same = false;
			                                        if($proforma_detail->booking_detail->booking->type == "product"){
			                                            if($detail_proforma[$i]['detail_proforma_id'] == $proforma_detail->booking_detail->product_id){
			                                                $is_same = true;
			                                            }
			                                        }else if($proforma_detail->booking_detail->booking->type == "room"){
			                                            if($detail_proforma[$i]['detail_proforma_id'] == $proforma_detail->booking_detail->room_id){
			                                                $is_same = true;
			                                            }
			                                        }else if($proforma_detail->booking_detail->booking->type == "package"){
			                                            if($detail_proforma[$i]['detail_proforma_id'] == $proforma_detail->booking_detail->package_id){
			                                                $is_same = true;
			                                            }
			                                        }else{
			                                            // Do Nothing
			                                        }
			                                        if($is_same){
			                                            if($detail_price == 0){
			                                                if($detail_proforma[$i]['detail_proforma_type'] == "free_booking"){
			                                                    $detail_proforma[$i]['end_periode'] = $end_periode;
			                                                    $detail_proforma[$i]['quantity'] = $detail_proforma[$i]['quantity'] + $quantity;
			                                                    $found = true;
			                                                    break;
			                                                }
			                                            }else{
			                                                if($detail_proforma[$i]['detail_proforma_type'] == "booking"){
			                                                    $detail_proforma[$i]['end_periode'] = $end_periode;
			                                                    $detail_proforma[$i]['quantity'] = $detail_proforma[$i]['quantity'] + $quantity;
			                                                    $found = true;
			                                                    break;
			                                                }
			                                            }
			                                        }
			                                    }elseif($proforma_detail->order_detail_id != null){
			                                        if($detail_proforma[$i]['detail_proforma_id'] == $proforma_detail->order_detail->product_id){
			                                            if($detail_invoice[$i]['detail_invoice_source_id'] != $invoice_detail->order_detail->product_id){
			                                                $found = false;
			                                                break;
			                                            }

			                                            if($detail_price == 0){
			                                                if($detail_proforma[$i]['detail_proforma_type'] == "free_order"){
			                                                    $detail_proforma[$i]['end_periode'] = $end_periode;
			                                                    $detail_proforma[$i]['quantity'] = $detail_proforma[$i]['quantity'] + $quantity;
			                                                    $found = true;
			                                                    break;
			                                                }
			                                            }else{
			                                                if($detail_proforma[$i]['detail_proforma_type'] == "order"){
			                                                    $detail_proforma[$i]['end_periode'] = $end_periode;
			                                                    if($detail_proforma[$i]['detail_price'] == $detail_price){
			                                                        $detail_proforma[$i]['quantity'] = $detail_proforma[$i]['quantity'] + $quantity;
			                                                    }else{
			                                                        $detail_proforma[$i]['detail_price'] = $detail_proforma[$i]['detail_price'] * $detail_proforma[$i]['quantity'];
			                                                        $detail_proforma[$i]['quantity'] = 1;
			                                                        $detail_proforma[$i]['detail_price'] = $detail_proforma[$i]['detail_price'] + ($detail_price * $quantity);
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
			                                    $new_array['detail_proforma_source_id'] = null;
			                                    $new_array['remarks'] = $proforma_detail->remarks != null ? $proforma_detail->remarks : '';

			                                    if($proforma_detail->booking_detail_id != null){
			                                        $new_array['detail_proforma_source_id'] = $proforma_detail->booking_detail_id;
			                                        if($proforma_detail->booking_detail->booking->type == "product"){
			                                            $new_array['detail_proforma_id'] = $proforma_detail->booking_detail->product_id;
			                                        }else if($proforma_detail->booking_detail->booking->type == "room"){
			                                            $new_array['detail_proforma_id'] = $proforma_detail->booking_detail->room_id;
			                                        }else if($proforma_detail->booking_detail->booking->type == "package"){
			                                            $new_array['detail_proforma_id'] = $proforma_detail->booking_detail->package_id;
			                                        }else{

			                                        }

			                                        if($detail_price == 0){
			                                            $new_array['detail_proforma_type'] = "free_booking";
			                                        }else{
			                                            $new_array['detail_proforma_type'] = "booking";
			                                        }
			                                    }elseif($proforma_detail->order_detail_id != null){
			                                        $new_array['detail_proforma_source_id'] = $proforma_detail->order_detail_id;
			                                        $new_array['detail_proforma_id'] = $proforma_detail->order_detail->product_id;

			                                        if($detail_price == 0){
			                                            $new_array['detail_proforma_type'] = "free_order";
			                                        }else{
			                                            $new_array['detail_proforma_type'] = "order";
			                                        }
			                                    }

			                                    array_push($detail_proforma, $new_array);
			                                }
			                            @endphp
			                        @endif
			                    @endforeach
			                    @for($i=0; $i < sizeof($detail_proforma); $i++)
			                        <tr>
			                            <td colspan="2">
			                                {{ $detail_proforma[$i]['detail'] }}
			                               
			                                @if($detail_proforma[$i]['remarks'] != 'null')
			                                 <br>
			                                {{$detail_proforma[$i]['remarks'] }}
			                                @endif
			                            </td>
			                            <td class="text-center">
			                                @if($detail_proforma[$i]['end_periode'] == "")
			                                    {{ $detail_proforma[$i]['start_periode'] }}
			                                @else
			                                    {{ $detail_proforma[$i]['start_periode'] }} - {{ $detail_proforma[$i]['end_periode'] }}
			                                @endif
			                            </td>
			                            <td class="text-center">
			                                {{ $detail_proforma[$i]['quantity'] }}
			                            </td>
			                            <td class="text-right">
			                                IDR {{number_format($detail_proforma[$i]['detail_price'], 0, '.', ',') }} <br>
			                            </td>
			                            <td class="text-right">
			                                IDR {{number_format($detail_proforma[$i]['quantity'] * ($detail_proforma[$i]['detail_price']), 0, '.', ',')}} <br>
			                            </td>
			                        </tr>
			                    @endfor
			                    @if($proforma->booking_id != null)
			                        <tr>
			                            <td colspan="2">
			                                @if($proforma->booking->type == "product")
			                                    {{ $proforma->booking->product->name }}
			                                @elseif($proforma->booking->type == "room")
			                                    @foreach($proforma->booking->rooms as $room)
			                                        {{ $room->room_number }} <br>
			                                    @endforeach
			                                @elseif($proforma->booking->type == "package")
			                                    @foreach($proforma->booking->packages as $package)
			                                        {{ $package->name }} <br>
			                                    @endforeach
			                                @endif
			                            </td>
			                            <td class="text-center">
			                                {{ date('j M Y', strtotime($proforma->booking->start_date)) }} - {{ date('j M Y', strtotime($proforma->booking->end_date)) }}
			                            </td>
			                            <td class="text-center">
			                                1
			                            </td>
			                            <td class="text-right">
			                                IDR {{number_format($proforma->total_price, 0, '.', ',') }} <br>
			                            </td>
			                            <td class="text-right">
			                                IDR {{number_format($proforma->total_price, 0, '.', ',')}} <br>
			                            </td>
			                        </tr>
			                    @endif
			                    @if($proforma->order_id != null)
			                        <tr>
			                            <td colspan="2">
			                                @foreach($proforma->order->order_detail as $order_detail)
			                                    {{ $order_detail->product->name }}
			                                @endforeach
			                            </td>
			                            <td class="text-center">
			                                {{ date('j M Y', strtotime($proforma->order->order_date)) }}
			                            </td>
			                            <td class="text-center">
			                                1
			                            </td>
			                            <td class="text-right">
			                                IDR {{number_format($proforma->total_price, 0, '.', ',') }} <br>
			                            </td>
			                            <td class="text-right">
			                                IDR {{number_format($proforma->total_price, 0, '.', ',')}} <br>
			                            </td>
			                        </tr>
			                    @endif
			                    @if($proforma->inquiry_id != null)
			                        @if($proforma->inquiry->type == "product")
			                            <tr>
			                                <td colspan="2">
			                                    {{ $proforma->inquiry->product->name }}
			                                </td>
			                                <td class="text-center">
			                                    {{ date('j M Y', strtotime($proforma->inquiry->start_date)) }} -
			                                    @if($proforma->inquiry->free_term_booking != null)
			                                        @php
			                                            $end_date = $proforma->inquiry->end_date;

			                                            switch($proforma->inquiry->price_type){
			                                                case "monthly" :
			                                                    $end_date = date('Y-m-d', strtotime("-".$proforma->inquiry->free_term_booking." months", strtotime($end_date)));
			                                                break;
			                                            }
			                                        @endphp
			                                        {{ date('j M Y', strtotime($end_date)) }}
			                                    @else
			                                        {{ date('j M Y', strtotime($proforma->inquiry->end_date)) }}
			                                    @endif
			                                </td>
			                                <td class="text-center">
				                                @php
		                                            $length_of_term = $proforma->inquiry->length_of_term;

		                                            if($proforma->inquiry->free_term_booking != null){
		                                                $length_of_term = $proforma->inquiry->length_of_term - $proforma->inquiry->free_term_booking;
		                                            }
		                                        @endphp


			                                    {{ number_format($length_of_term, 0, ',', '.') }}
			                                </td>
			                                <td class="text-right">
			                                    IDR {{number_format($proforma->inquiry->detail_price, 0, ',', '.') }} <br>
			                                </td>
			                                <td class="text-right">
			                                    IDR {{number_format($proforma->inquiry->detail_price * $length_of_term, 0, ',', '.')}} <br>
			                                </td>
			                            </tr>
			                            @if($proforma->inquiry->free_term_booking > 0)
			                                <tr>
			                                    <td colspan="2">
			                                        {{ $proforma->inquiry->product->name }}
			                                    </td>
			                                    <td class="text-center">
			                                        {{ date('j M Y', strtotime($end_date)) }}
			                                        -
			                                        {{ date('j M Y', strtotime($proforma->inquiry->end_date)) }}
			                                    </td>
			                                    <td class="text-center">
			                                        {{ number_format($proforma->inquiry->free_term_booking, 0, ',', '.') }}
			                                    </td>
			                                    <td class="text-right">
			                                        IDR {{number_format(0, 0, ',', '.') }} <br>
			                                    </td>
			                                    <td class="text-right">
			                                        IDR {{number_format(0, 0, ',', '.')}} <br>
			                                    </td>
			                                </tr>
			                            @endif
			                        @elseif($proforma->inquiry->type == "package")
			                            @foreach($proforma->inquiry->packages as $package)
			                                <tr>
			                                    <td colspan="2">
			                                        {{ $package->name }}
			                                    </td>
			                                    <td class="text-center">
			                                        {{ date('j M Y', strtotime($package->pivot->start_date)) }} -
			                                        {{ date('j M Y', strtotime($package->pivot->end_date)) }}
			                                    </td>
			                                    <td class="text-center">
			                                        {{ number_format($package->pivot->length_of_term * $package->pivot->quantity, 0, ',', '.') }}
			                                    </td>
			                                    <td class="text-right">
			                                        IDR {{number_format($package->pibot->detail_price, 0, ',', '.') }} <br>
			                                    </td>
			                                    <td class="text-right">
			                                        IDR {{number_format($package->pibot->detail_price * $package->pivot->length_of_term * $package->pivot->quantity, 0, ',', '.')}} <br>
			                                    </td>
			                                </tr>
			                            @endforeach
			                        @elseif($proforma->inquiry->type == "room")
			                            @foreach($proforma->inquiry->rooms as $room)
			                                <tr>
			                                    <td colspan="2">
			                                        {{ $room->room_number }}
			                                    </td>
			                                    <td class="text-center">
			                                        {{ date('j M Y', strtotime($proforma->inquiry->start_date)) }} -
			                                        @if($proforma->inquiry->free_term_booking != null)
			                                            @php
			                                                $end_date = $proforma->inquiry->end_date;

			                                                switch($proforma->inquiry->price_type){
			                                                    case "monthly" :
			                                                        $end_date = date('Y-m-d', strtotime("-".$proforma->inquiry->free_term_booking." months", strtotime($end_date)));
			                                                    break;
			                                                }
			                                            @endphp
			                                            {{ date('j M Y', strtotime($end_date)) }}
			                                        @else
			                                            {{ date('j M Y', strtotime($proforma->inquiry->end_date)) }}
			                                        @endif
			                                    </td>
			                                    <td class="text-center">
			                                        @php
			                                            $length_of_term = $proforma->inquiry->length_of_term;

			                                            if($proforma->inquiry->term_of_payment != null){
			                                                $length_of_term = $proforma->inquiry->term_of_payment;
			                                            }
			                                        @endphp

			                                        {{ number_format($length_of_term, 0, ',', '.') }}
			                                    </td>
			                                    <td class="text-right">
			                                        IDR {{number_format($room->pivot->detail_price, 0, ',', '.') }} <br>
			                                    </td>
			                                    <td class="text-right">
			                                        IDR {{number_format($room->pivot->detail_price * $length_of_term, 0, ',', '.')}} <br>
			                                    </td>
			                                </tr>
			                                @if($proforma->inquiry->free_term_booking > 0)
			                                    <tr>
			                                        <td colspan="2">
			                                            {{ $room->room_number }}
			                                        </td>
			                                        <td class="text-center">
			                                            {{ date('j M Y', strtotime($end_date)) }}
			                                            -
			                                            {{ date('j M Y', strtotime($proforma->inquiry->end_date)) }}
			                                        </td>
			                                        <td class="text-center">
			                                            {{ number_format($proforma->inquiry->free_term_booking, 0, ',', '.') }}
			                                        </td>
			                                        <td class="text-right">
			                                            IDR {{number_format(0, 0, ',', '.') }} <br>
			                                        </td>
			                                        <td class="text-right">
			                                            IDR {{number_format(0, 0, ',', '.')}} <br>
			                                        </td>
			                                    </tr>
			                                @endif
			                            @endforeach
			                        @endif

			                        @foreach($proforma->inquiry->products as $product)
			                            <tr>
			                                <td colspan="2">
			                                    @if($product->has_service_charge == "Y") <b>*)</b> @endif {{ $product->name }}
			                                </td>
			                                <td class="text-center">
			                                    @if($product->pivot->end_date == null)
			                                        {{ date('j M Y', strtotime($product->pivot->start_date)) }}
			                                    @else
			                                        {{ date('j M Y', strtotime($product->pivot->start_date)) }} - {{ date('j M Y', strtotime($product->pivot->end_date)) }}
			                                    @endif
			                                </td>
			                                <td class="text-center">
			                                    {{ number_format($product->pivot->length_of_term * $product->pivot->quantity, 0, ',', '.') }}
			                                </td>
			                                <td class="text-right">
			                                    IDR {{number_format($product->pivot->detail_price, 0, ',', '.') }} <br>
			                                </td>
			                                <td class="text-right">
			                                    IDR {{number_format($product->pivot->detail_price * $product->pivot->length_of_term * $product->pivot->quantity, 0, ',', '.')}} <br>
			                                </td>
			                            </tr>
			                        @endforeach

			                    @endif
                                <tr>
                                    <td colspan="5"><b>Sub Total</b></td>
                                    <td class="text-right"><b>IDR
                                    @if($proforma->inquiry_id != null)
			                        {{ number_format($proforma->inquiry->total_price + $proforma->inquiry->total_additional_charge, 0, ',', '.') }}
			                        @else
			                        {{number_format($proforma->total_price, 0, '.', ',')}}
			                        @endif</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Service Charge</b></td>
                                    <td class="text-right"><b>IDR {{number_format($proforma->total_service_charge, 0, '.', ',')}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Total</b></td>
                                    <td class="text-right"><b>IDR
                                    @if($proforma->inquiry_id != null)
			                        {{ number_format($proforma->inquiry->total_price + $proforma->inquiry->total_additional_charge + $proforma->inquiry->total_service_charge_additional_charge, 0, ',', '.') }}
			                        @else
			                        {{number_format($proforma->total_price + $proforma->total_service_charge, 0, '.', ',')}}
			                        @endif

                                    </b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Tax Base for VAT</b></td>
                                    <td class="text-right"><b>IDR
                                    {{number_format($proforma->total_price_on_tax, 0, '.', ',')}}

                                    </b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Tax</b></td>
                                    <td class="text-right"><b>IDR
                                    @if($proforma->inquiry_id != null)
			                        {{ number_format($proforma->inquiry->total_tax_price + $proforma->inquiry->total_tax_additional_charge, 0, ',', '.') }}
			                        @else
			                        {{number_format($proforma->total_tax_price, 0, '.', ',')}}
			                        @endif

                                    </b></td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="5"><b>Stamp Duty</b></td>
                                    <td class="text-right"><b>IDR {{number_format($proforma->stamp_duty, 0, '.', ',')}}</b></td>
                                </tr>
                                <tr>
                                    <td colspan="5"><b>Round Price</b></td>
                                    <td class="text-right"><b>IDR {{number_format($proforma->round_price, 0, '.', ',')}}</b></td>
                                </tr> -->
                                <tr>
                                    <td colspan="5"><b>Deposit</b></td>
                                    <td class="text-right"><b>IDR {{number_format($proforma->total_deposit, 0, '.', ',')}}</b></td>
                                </tr>
                                  @if($proforma->has_deduction == 'Y')
                                <tr>
                                    <td colspan="5"><b>Deduction Withholding Tax</b></td>
                                    <td class="text-right"><b>IDR {{number_format($proforma->deduction_price, 0, '.', ',')}}</b></td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="5"><b>Grand Total</b></td>
                                    <td class="text-right"><b>IDR
                                    @if($proforma->inquiry_id != null)
			                        {{ number_format($proforma->inquiry->total_price + $proforma->inquiry->total_additional_charge + $proforma->inquiry->total_service_charge_additional_charge + $proforma->inquiry->total_tax_price + $proforma->inquiry->total_tax_additional_charge + $proforma->stamp_duty + $proforma->round_price + $proforma->total_deposit-$proforma->deduction_price, 0, ',', '.') }}
			                        @else
                                    {{number_format($proforma->total_price + $proforma->total_service_charge + $proforma->total_tax_price + $proforma->stamp_duty + $proforma->round_price + $proforma->total_deposit - $proforma->deduction_price, 0, '.', ',')}}
			                        @endif
                                    </b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        @if($proforma->bank_account_id != null)
                            <table width="100%">
                                <tr>
                                    <td width="50%">
                                        <h4><b>Account Details : </b></h4>
                                        <p>Beneficiary Name: <b>{{$proforma->bank_account->account_name}}</b></p>
                                    </td>
                                    <td width="50%">
                                        @if($proforma->customer->virtual_account_no != null)
                                            <h4><b>Virtual Account : {{ $proforma->customer->virtual_account_no }} ({{ $proforma->customer->virtual_account_bank }})</b></h4>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>
                                            {{$proforma->bank_account->bank_name}} (IDR) <br>
                                            Acc No : {{$proforma->bank_account->account_no}} <br>
                                            Branch : {{$proforma->bank_account->branch_code}} <br>
                                            Swift Code : {{$proforma->bank_account->swift_code}}
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
                            Please send proof of your payments, and include Proforma Number stated above.<br>
                            Any dispute or correction must be informed to us within 5 days from this Proforma Date.
                        </p>
                        <p><b>This Proforma is automatically generated, no signature is required</b></p>
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
