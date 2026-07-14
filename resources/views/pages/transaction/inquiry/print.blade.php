@extends('layouts.print')
@section('content')
@if($inquiry->status_id ==1)
<style>
#background{
    position:absolute;
    z-index:-10000;
    background:transparent;
    display:block;
    min-height:50%;
    min-width:50%;
    color:yellow;
}

#content{
    position:absolute;
    z-index: 1000;
}

#bg-text
{
    color:lightgrey;
    font-size:300px;
    transform:rotate(300deg);
    -webkit-transform:rotate(300deg);
}
#bg-image
{
    color:lightgrey;
}
</style>
@endif

<br><br>
<div class="container" style="font-family: Calibri;font-size:15px;">

    <div class="row">
        <div class="col-md-6 text-left">

        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid" src="{{ asset('company-logo.png') }}" width="150">
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12">
            <table width="100%">
                <tr>
                    <td>No</td>
                    <td>: {{ $inquiry->code }}</td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>: Inquiry</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>: {{ date("j F Y", strtotime($inquiry->created_at)) }}</td>
                </tr>
            </table>
            <br>
            <p>
                To <b>{{ $inquiry->contact->name }}</b>
                <br>
                {{ $inquiry->customer->email }}
                <br>
                {{ $inquiry->customer->phone }}
                <br>
                {!! $inquiry->customer->address !!}
            </p>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="4"><b>DETAILS</b></td>
                </tr>
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;" width="25%"> Product Interest </td>
                    <td style="padding-top: 1px;padding-bottom: 1px;" width="25%">
                    @if($inquiry->type == "package")
                    Package
                    @endif
                    @if($inquiry->type == "product")
                    Virtual Office
                    @endif
                    @if($inquiry->type == "room")
                    Serviced Office
                    <ol>
                    @foreach($inquiry->rooms as $no => $room)
                    <li>Room {{ $room->room_number}} ({{ $room->location->name}})</li>
                    @endforeach
                    </ol>
                    @endif
                    </td>
                </tr>
                @if($inquiry->status_id == 1)
                <div id="background">
                    <p id="bg-text">DRAFT</p>
                </div>
                @else
                <div id="background">
                    <img  src="{{asset('watermark.png')}}" width="500" style="position: absolute;z-index:-10000;opacity:0.3;left:25%;">
                </div>
                @endif
                @if($inquiry->price_type == 'hourly' && $inquiry->price_type == 'halfday')
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;">Start Time</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">{{date("j F Y",strtotime($inquiry->start_time))}}</td>
                </tr>
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;">End Time</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">{{date("j F Y",strtotime($inquiry->end_time))}}</td>
                </tr>
                @endif
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;">Start Date</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">{{date("j F Y",strtotime($inquiry->start_date))}}</td>
                </tr>
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;">Length Of Term</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">{{$inquiry->length_of_term}}
                        @if($inquiry->price_type == 'yearly')
                            Year(s)
                        @elseif($inquiry->price_type == 'monthly')
                            Month(s)
                        @elseif($inquiry->price_type == 'daily')
                            Day(s)

                        @elseif($inquiry->price_type == 'hourly')
                            Hours(s)
                        @elseif($inquiry->price_type == 'halfday')
                            Hours(s)
                        @endif
                </td>
                </tr>
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;">End Date</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">{{date("j F Y",strtotime($inquiry->end_date))}}</td>
                </tr>
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;"> Referal/Agent</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        @if($inquiry->referral_id != null)
                            {{ $inquiry->referral->name }}
                        @elseif($inquiry->agent_id != null)
                            {{ $inquiry->agent->name }}
                        @endif
                    </td>
                </tr>
                <tr style="vertical-align: top;">
                    <td style="padding-top: 1px;padding-bottom: 1px;">Sales Name</td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">{{ $inquiry->employee->name }}</td>
                </tr>
            </table>

            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="5"><b>FEES</b></td>
                </tr>
                <tr class="text-center">
                    <td width="20%"><b>Inquiries</b></td>
                    <td width="15%"><b>Quantity</b></td>
                    <td width="25%" colspan="2"><b>Detail Price</b></td>
                    <td width="25%"><b>Total</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;padding-bottom: 10px;">
                    @if($inquiry->type == 'package')
                        @foreach($inquiry->packages as $packages)
                            Room {{$packages->name}} <br>
                        @endforeach
                    @endif
                    @if($inquiry->type == 'product')
                        {{ $inquiry->product->name }}
                    @endif
                    @if($inquiry->type == 'room')
                        @foreach($inquiry->rooms as $rooms)
                            Room {{$rooms->room_number}} <br>
                        @endforeach
                    @endif
                    </td>
                    <td style="padding-top: 10px;padding-bottom: 10px;" class="text-center">
                        {{ $inquiry->term_of_payment }} @if($inquiry->free_term_booking != null) (-{{ $inquiry->free_term_booking }}) @endif
                        @if($inquiry->price_type == 'yearly')
                            Year(s)
                        @elseif($inquiry->price_type == 'monthly')
                            Month(s)
                        @elseif($inquiry->price_type == 'daily')
                            Day(s)
                        @elseif($inquiry->price_type == 'hourly')
                            Hours(s)
                        @endif
                    </td>
                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;" colspan="2">
                    @if($inquiry->type == 'package')
                        @foreach($inquiry->packages as $packages)
                            @if($inquiry->tax_status == "include")
                                @if($inquiry->service_charge_status == "Y")
                                    {{ $default_currency }} {{ number_format($packages->pivot->detail_price / ((1 + $service_charge) * (1 + $tax_percentage))) }} <br>
                                @else
                                    {{ $default_currency }} {{ number_format($packages->pivot->detail_price / (1 + $tax_percentage)) }} <br>
                                @endif
                            @else
                                {{ $default_currency }} {{ number_format($packages->pivot->detail_price) }} <br>
                            @endif
                        @endforeach
                    @endif
                    @if($inquiry->type == 'product')
                        @if($inquiry->tax_status == "include")
                            @if($inquiry->service_charge_status == "Y")
                                {{ $default_currency }} {{ number_format($inquiry->detail_price / ((1 + $service_charge) * (1 + $tax_percentage))) }}
                            @else
                                {{ $default_currency }} {{ number_format($inquiry->detail_price / (1 + $tax_percentage)) }}
                            @endif
                        @else
                            {{ $default_currency }} {{ number_format($inquiry->detail_price) }}
                        @endif
                    @endif
                    @if($inquiry->type == 'room')
                        @foreach($inquiry->rooms as $rooms)
                            @if($inquiry->tax_status == "include")
                                @if($inquiry->service_charge_status == "Y")
                                    {{ $default_currency }} {{ number_format($rooms->pivot->detail_price / ((1 + $service_charge) * (1 + $tax_percentage))) }} <br>
                                @else
                                    {{ $default_currency }} {{ number_format($rooms->pivot->detail_price / (1 + $tax_percentage)) }} <br>
                                @endif
                            @else
                                {{ $default_currency }} {{ number_format($rooms->pivot->detail_price) }} <br>
                            @endif
                        @endforeach
                    @endif
                    </td>
                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                        @php
                            $total_price= 0;
                            $sub_total_main =0;
                            $discount_price = $inquiry->discount_price;
                            if($inquiry->free_term_booking != null){
                                $length_of_term = $inquiry->length_of_term - $inquiry->free_term_booking;
                            }else{
                                $length_of_term = $inquiry->length_of_term;
                            }

                            if($inquiry->price_type == 'halfday'){
                                $sub_total_main = $sub_total_main + $inquiry->detail_price * $inquiry->quantity;
                            }else{
                                $sub_total_main = $sub_total_main + $inquiry->detail_price * $inquiry->quantity * $length_of_term;
                            }

                            $total_price = ($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment);
                            if($inquiry->tax_status == "include"){
                                if($inquiry->service_charge_status == "Y"){
                                    $total_price = $total_price / ((1 + $service_charge) * (1 + $tax_percentage));
                                }else{
                                    $total_price = $total_price / (1 + $tax_percentage);
                                }
                                $discount_price = $total_price - $inquiry->total_price;
                            }
                        @endphp

                    {{ $default_currency }} {{ number_format($total_price) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                    <b>Discount @if($inquiry->usable_discount == "percentage")<span class="text-right">({{number_format($inquiry->discount_percentage)}}%)</span>@endif</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        <b>{{ $default_currency }} {{ number_format($discount_price) }}</b>
                    </td>
                </tr>
                <tr>
                    @php
                        $total_price = $total_price - $discount_price;
                    @endphp
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                        <b>Total After Discount</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($total_price) }}</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                        <b>Service Charge<span class="text-right">({{ number_format($service_charge * 100) }}%)</span></b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($inquiry->total_service_charge) }}</b></td>
                </tr>
                <tr>
                    @php
                        $total_price = $total_price + $inquiry->total_service_charge;
                    @endphp
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                        <b>Total After Service Charge</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($total_price) }}</b></td>
                </tr>
                @php
                    $total_additional_charge = 0;
                    $total_service_charge_additional_charge = 0;
                    $total_tax_additional_charge = 0;
                @endphp
                @foreach($inquiry->products as $product)
                    @php
                        $detail_price = round($product->pivot->detail_price);
                        $sub_total = $detail_price * $product->pivot->quantity * $product->pivot->length_of_term;


                        if($inquiry->tax_status == "no_tax"){

                        }else if($inquiry->tax_status == "exclude"){
                            if($inquiry->service_charge_status == "Y"){
                                $total_service_charge_additional_charge = $total_additional_charge * $service_charge;
                                $total_tax_additional_charge = $total_service_charge_additional_charge * $tax_percentage;
                            }else{
                                $total_tax_additional_charge = $total_additional_charge * $tax_percentage;
                            }
                        }else if($inquiry->tax_status == "include"){
                            $total_service_charge_additional_charge = 0;
                            $total_tax_additional_charge = 0;
                        }else{
                            $total_service_charge_additional_charge = 0;
                            $total_tax_additional_charge = 0;
                        }

                        $total_additional_charge = $total_additional_charge + $sub_total;
                    @endphp
                <tr>
                    <td colspan="2"><b>Additional Charge</b> - {{ $product->name }}</td>
                    <td class="text-center">{{ number_format($product->pivot->quantity) }}</td>
                    <td class="text-right" class="text-center">{{ $default_currency }} {{number_format($detail_price)}}</td>
                    <td class="text-right"><b> {{ $default_currency }} {{ number_format($sub_total) }} </b></td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                        <b>VAT<span class="text-right">({{ number_format($tax_percentage * 100) }}%)</span></b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format(($inquiry->total_tax_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_tax_additional_charge) }}</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                    <b>Security Deposit</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($inquiry->security_deposit) }}</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="4">
                        <b>Grand Total</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format(($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_service_charge + ($inquiry->total_tax_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $total_additional_charge + $inquiry->total_service_charge_additional_charge + $inquiry->total_tax_additional_charge + $inquiry->security_deposit + $inquiry->stamp_duty + $inquiry->round_price) }}</b></td>
                </tr>
            </table>
            <p style="text-align: justify;">
                <b>*)</b> Item has service charge
            </p>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="2"><b>REMARKS</b></td>
                </tr>
                <tr>
                    <td colspan="2">*) {!! $inquiry->remarks !!}</td>
                </tr>
            </table>
            <h3><i>This is a computer generated Inquiry no signature is required.</i></h3>
        </div>
    </div>

</div>
@endsection
