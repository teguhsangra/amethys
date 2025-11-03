@extends('layouts.print')
@section('content')
@if($booking->status_id ==1)
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
</style>
@endif
<br><br>
<div class="container" style="font-family: Calibri;font-size:15px;">
    <div class="row">
        <div class="col-md-6 text-left">
            <table width="100%">
                <tr>
                    <td>Date</td>
                    <td>: <u>{{ Carbon\Carbon::now()->format('l, d F Y')}}&nbsp;&nbsp;&nbsp;</u></td>
                </tr>
                <tr>
                    <td>Reference No</td>
                    <td>: <u>{{$booking->code}}&nbsp;&nbsp;&nbsp;</u></td>
                </tr>
            </table>
        </div>
        <div class="col-md-6 text-right">
            <img class="img-fluid" src="{{ asset('company-logo.png') }}" width="200">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td width="50%"><b>1. LICENSOR</b></td>
                    <td width="50%"><b>2. LICENSEE</b></td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" class="table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="50%">Company Name</td>
                                <td width="50%">{{$company_name}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Address</td>
                                <td>{!! $booking->location->address !!}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Telephone</td>
                                <td>{{$booking->location->phone}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Fax</td>
                                <td>{{$booking->location->fax}}</td>
                            </tr>
                            @if(!empty($booking->bank_account))
                                <tr style="vertical-align: top;">
                                    <td>Bank</td>
                                    <td>{{ $booking->bank_account->bank_name }}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td>Account No</td>
                                    <td>{{ $booking->bank_account->account_no }}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td>Currency Code</td>
                                    <td>{{ $booking->bank_account->currency_code }}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td>Branch Code</td>
                                    <td>{{ $booking->bank_account->branch_code }}</td>
                                </tr>
                                <tr style="vertical-align: top;">
                                    <td>SWIFT Code</td>
                                    <td>{{ $booking->bank_account->swift_code }}</td>
                                </tr>
                            @endif
                        </table>
                    </td>
                    <td>
                        <table width="100%" class="table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="50%">Company Name</td>
                                <td width="50%">{{$booking->customer->name}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Tax Number (NPWP)</td>
                                <td>{{$booking->customer->tax_number}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Nature Of Business</td>
                                <td>{{$booking->customer->nature_of_business}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Address</td>
                                <td>
                                    @if($booking->address_status == "customer")
                                        {!! $booking->customer->address !!}
                                    @else
                                        {!! $booking->location->address !!}
                                    @endif
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>E-mail Address</td>
                                <td>{{$booking->customer->email}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Contact Name</td>
                                <td>{{$booking->contact->name}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Title</td>
                                <td>
                                    @if(!empty($booking->customer->contact))
                                        @foreach($booking->customer->contact as $contact)
                                            {{$contact->pivot->position}}
                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>ID Card Number</td>
                                <td>{{$booking->contact->id_number}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Telephone</td>
                                <td>
                                    {{ $booking->customer->phone }}
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Mobile</td>
                                <td>{{$booking->contact->mobile_phone}}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Fax</td>
                                <td>
                                    {{ $booking->customer->fax_no }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @if($booking->status_id == 1)
            <div id="background">
                <p id="bg-text">DRAFT</p>
            </div>
            @else
            <div id="background">
                <img  src="{{asset('watermark.png')}}" width="500" style="position: absolute;z-index:-10000;opacity:0.3;left:25%;">
            </div>
            @endif
            <table width="100%" class="table table-bordered" style="vertical-align: center;">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="4">3. DETAILS</td>
                </tr>
                <tr>
                    <!-- Service Info -->
                    <td width="25%">
                        Service Type<br>
                        Details<br>
                        Start Date <br>
                        End Date <br>
                        Length of Term <br>
                        Term Notice Period <br>
                        Term of payment <br>
                        @if($booking->type == "room")
                        Number of Workstation <br>
                        @endif

                    </td>
                    <td width="25%">
                        : Virtual Office <br>
                        : {{$booking->product->name}} <br>
                        : {{date("j F Y",strtotime($booking->start_date))}}<br>
                        : {{date("j F Y",strtotime($booking->end_date))}}<br>
                        : {{$booking->length_of_term}} Months<br>
                        : {{$booking->term_notice_period}}-Month Notification<br>
                        :
                        @if($booking->term_of_payment == 1) Monthly
                        @elseif($booking->term_of_payment == 3) Quarterly
                        @elseif($booking->term_of_payment == 6) Semi-Annually
                        @elseif($booking->term_of_payment == 12) Annually
                        @else {{ $booking->term_of_payment }} Months @endif
                            in advance

                    </td>
                    <!-- Service Info -->

                    <!-- Furniture/Phone Info -->
                    <td width="25%" style="top: 50%;">

                        @if(sizeof($booking->dedicated_phones) > 0)
                            <table class="table" style="background: none !important;">
                                <thead>
                                    <tr>
                                        <th style="border:none !important;"><b>Dedicated Phone Number</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($booking->dedicated_phones as $dedicated_phone)
                                    <tr>
                                        <td style="border:none !important;">{{ $dedicated_phone->number }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </td>

                    <td width="25%" class="text-left" style="top: 50%;">
                        @if(sizeof($booking->complimentarys) > 0)
                            <table class="table" style="background: none !important;">
                                <thead>
                                    <tr>
                                        <th style="border:none !important;"><b>Complimentary</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($booking->complimentarys as $complimentary)
                                    <tr>
                                        <td style="border:none !important;"> {{ $complimentary->pivot->total_complimentary.' '.$complimentary->name }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </td>
                    <!-- Furniture/Phone Info -->
                </tr>
            </table>
            <table width="100%" class="table table-bordered" style="vertical-align: center;">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="5">4. FEES</td>
                </tr>
                <tr>
                    <td width="20%" style="text-align: center;"<b>Service Type</b></td>
                    <td width="15%" style="text-align: center;"><b>Length of Term</b></td>
                    <td width="15%" style="text-align: center;"><b>Quantity</b></td>
                    <td width="25%" style="text-align: center;"><b>Detail Price</b></td>
                    <td width="25%" style="text-align: center;"><b>Total</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;padding-bottom: 10px;">
                        {{ $booking->product->name }}
                    </td>
                    <td style="padding-top: 10px;padding-bottom: 10px;" class="text-center">
                        @php
                            $length_of_term = $booking->length_of_term;

                            if($booking->free_term_booking != null){
                                $length_of_term = $booking->length_of_term - $booking->free_term_booking;
                            }
                        @endphp

                        {{ $length_of_term }}

                        @if($booking->price_type == 'yearly')
                            Year(s)
                        @elseif($booking->price_type == 'monthly')
                            Month(s)
                        @elseif($booking->price_type == 'daily')
                            Day(s)
                        @elseif($booking->price_type == 'hourly')
                            Hours(s)
                        @elseif($booking->price_type == 'halfday')
                            Hours(s)
                        @endif
                    </td>
                    <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">
                        {{ $length_of_term}}
                    </td>
                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                        Rp {{ number_format($booking->detail_price, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                        @php
                            $total_price= 0;
                            $sub_total_main =0;
                            $discount_price = $booking->discount_price;

                            if($booking->price_type == 'halfday'){
                                $sub_total_main = $sub_total_main+$booking->detail_price*$booking->quantity;
                            }else{
                                $sub_total_main = $sub_total_main+$booking->detail_price*$booking->quantity* ($booking->length_of_term - $booking->free_term_booking);
                            }
                            $total_price = $total_price + $sub_total_main;
                        @endphp

                        Rp {{ number_format($total_price, 0, ',', '.') }}
                    </td>
                </tr>
                @if($booking->free_term_booking > 0)
                    <tr>
                        <td style="padding-top: 10px;padding-bottom: 10px;">
                            <b>**)</b> {{ $booking->product->name }}
                        </td>
                        <td style="padding-top: 10px;padding-bottom: 10px;" class="text-center">
                            {{ $booking->free_term_booking }}
                            @if($booking->price_type == 'yearly')
                                Year(s)
                            @elseif($booking->price_type == 'monthly')
                                Month(s)
                            @elseif($booking->price_type == 'daily')
                                Day(s)
                            @elseif($booking->price_type == 'hourly')
                                Hours(s)
                            @elseif($booking->price_type == 'halfday')
                                Hours(s)
                            @endif
                        </td>
                        <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">
                            {{ $booking->free_term_booking }}
                        </td>
                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                            Rp {{ number_format(0, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                            Rp {{ number_format(0, 0, ',', '.') }}
                        </td>
                    </tr>
                @endif
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Discount @if($booking->usable_discount == "percentage")<span class="text-right">({{number_format($booking->discount_percentage, 0, ',', '.')}}%)</span>@endif</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        <b>(-) Rp {{ number_format($booking->discount_price, 0, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    @php
                        $total_price = $total_price - $discount_price;
                    @endphp
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Total After Discount</b>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Rp {{ number_format($total_price, 0, ',', '.') }}</b>
                    </td>
                </tr>
                @php
                    $total_additional_charge = 0;
                    $total_tax_additional_charge = 0;
                    if($booking->tax_status == 'include'){
                        $total_price = $total_price / (1 + $tax_percentage);
                    }
                @endphp
                @foreach($booking->products as $product)
                    @php
                        $detail_price = round($product->pivot->detail_price);

                        if($booking->tax_status == "include"){
                            $detail_price = $total_additional_charge / (1 + $service_charge);
                        }
                        $sub_total = $detail_price * $product->pivot->quantity * $product->pivot->length_of_term;
                    @endphp
                <tr>
                    <td style="padding-top: 10px;padding-bottom: 1px;">
                        <b>@if($product->has_service_charge == "Y") *) @endif</b> {{ $product->name }}
                    </td>
                    <td style="padding-top: 10px;padding-bottom: 1px;" class="text-center">
                        @if($product->price_type == 'single')
                            -
                        @else
                            {{ $product->pivot->length_of_term }}
                            @if($product->price_type == 'yearly')
                                Year(s)
                            @elseif($product->price_type == 'monthly')
                                Month(s)
                            @elseif($product->price_type == 'daily')
                                Day(s)
                            @elseif($product->price_type == 'hourly')
                                Hours(s)
                            @endif
                        @endif
                    </td>
                    <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">{{ number_format($product->pivot->quantity, 0, ',', '.') }}</td>
                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">Rp {{number_format($detail_price, 0, ',', '.')}}</td>
                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">Rp {{ number_format($sub_total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Service Charge <span class="text-right">({{ number_format($service_charge * 100, 0, ',', '.') }}%)</span>
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($booking->total_service_charge + $booking->total_service_charge_additional_charge, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        VAT 
                        <!--<span class="text-right">({{ number_format($tax_percentage * 100, 0, ',', '.') }}%)</span>-->
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($booking->total_tax_price + $booking->total_tax_additional_charge, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;"><b>Sub Total</b></td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        <b>Rp {{ number_format($booking->total_price + $booking->total_service_charge + $booking->total_tax_price + $booking->total_additional_charge + $booking->total_service_charge_additional_charge + $booking->total_tax_additional_charge, 0, ',', '.') }}</b>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Security Deposit
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($booking->security_deposit, 0, ',', '.') }}
                    </td>
                </tr>
                <!-- <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Stamp Duty (Materai)
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($booking->stamp_duty, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                        Rounded Price
                    </td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                        Rp {{ number_format($booking->round_price, 0, ',', '.') }}
                    </td>
                </tr> -->
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;"><b>Grand Total</b></td>
                    <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"><b>Rp {{ number_format($booking->total_price + $booking->total_service_charge + $booking->total_tax_price + $booking->total_additional_charge + $booking->total_service_charge_additional_charge + $booking->total_tax_additional_charge + $booking->security_deposit + $booking->stamp_duty + $booking->round_price, 0, ',', '.') }}</b></td>
                </tr>
            </table>
            <p style="text-align: justify;"><b>*)</b> Subject to service charge</p>
            <p style="text-align: justify;"><b>**)</b> Free</p>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="2">5. Package Info</td>
                </tr>
                @foreach ($booking->products as $item)
                <tr>
                    <td colspan="2">{!! $item->desc !!}</td>
                </tr>
                @endforeach

            </table>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="2">6. REMARKS</td>
                </tr>
                <tr>
                    <td colspan="2">{!! $booking->remarks !!}</td>
                </tr>
            </table>
            <p style="text-align: justify;">
                By signing this License Agreement, I/we herewith undertake to fully comply with all the applicable terms and conditions.
            </p>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #67128B !important;color: #FFF;">
                    <td colspan="2">7. SIGNATORIES</td>
                </tr>
                <tr>
                    <td width="50%">
                        Signed for and on behalf of the Licensor ({{$company_name}})
                    </td>
                    <td width="50%">
                        Signed for and on behalf of the Licensee ({{$booking->customer->name}})
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Signature & Company Seal</p>
                        <br><br><br><br><br><br><br>
                    </td>
                    <td>
                        <p>Signature & Company Seal</p>
                        <br><br><br><br><br><br><br>
                    </td>
                </tr>
                <tr>
                    <td><b>Name : {{$director_name}}</b></td>
                    <td><b>Name : {{$booking->contact->honorific}} {{$booking->contact->name}}</b></td>
                </tr>
                <tr>
                    <td>Date : {{date("j F Y",strtotime($booking->signed_date))}}</td>
                    <td>Date : {{date("j F Y",strtotime($booking->signed_date))}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>

@endsection
