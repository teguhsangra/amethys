@extends('layouts.app')
@section('title')
Rakomsis Package - {{ $booking->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Package</h4>
            </div>
            <div class="card-body">
                <a href="{{ url($url) }}" class="btn btn-rose">
                    <i class="fa fa-arrow-left"></i> Back To Package
                </a>
                <div class="toolbar">

                    <div class="btn-group btn-group-md" role="group" >
                        <a onclick="print('{{ url($print_url) }}')" class="btn  btn-info pull-right text-white">
                            <i class="fa fa-print"></i> Print
                        </a>
                        <a onclick="mail('{{ url($email_url) }}')" class="btn  btn-primary pull-right text-white">
                            <i class="fa fa-at"></i> Send to email
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td width="50%"><b>1. PROVIDER</b></td>
                                <td width="50%"><b>2. CUSTOMER</b></td>
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
                                            <td>{{$company_address_1}}, {{$company_address_1}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Telephone</td>
                                            <td>{{$company_phone}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Fax</td>
                                            <td>{{$company_fax}}</td>
                                        </tr>
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
                                            <td>Address</td>
                                            <td>{{$booking->customer->address}}</td>
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
                                            <td>Telephone</td>
                                            <td>{{$booking->contact->phone}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Mobile</td>
                                            <td>{{$booking->contact->mobile_phone}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Fax</td>
                                            <td>{{$booking->customer->fax_no}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-bordered" style="vertical-align: center;">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td colspan="4">3. DETAILS</td>
                            </tr>
                            <tr>
                                <!-- Service Info -->
                                <td width="25%">
                                    Service Type <br>
                                    Package(s) <br>
                                    @if($booking->is_main_agreement == "Y")
                                        Term Notice Period <br>
                                        Term of payment <br>
                                        Complimentary @foreach($booking->complimentarys as $complimentary) <br> @endforeach<br>
                                    @endif
                                </td>
                                <td width="25%">
                                    : Package <br>
                                    : @foreach($booking->rooms as $package) {{ $package->name }}<br> @endforeach
                                    @if($booking->is_main_agreement == "Y")
                                        : {{$booking->term_notice_period}}-Month Notification<br>
                                        :
                                        @if($booking->term_of_payment == 1) Monthly 
                                        @elseif($booking->term_of_payment == 3) Quarterly 
                                        @elseif($booking->term_of_payment == 6) Semi-Annually 
                                        @elseif($booking->term_of_payment == 12) Annually
                                        @else {{ $booking->term_of_payment }} Per {{ $booking->price_type }} @endif
                                        <br>
                                        : @foreach($booking->complimentarys as $complimentary) {{ $complimentary->pivot->total_complimentary.' '.$complimentary->name }} <br>&nbsp; @endforeach
                                    @endif
                                </td>
                                <!-- Service Info -->

                                <!-- Furniture/Phone Info -->
                                <td width="25%" style="top: 50%;">
                                    @if(sizeof($booking->dedicated_phones) > 0)
                                        <b>Dedicated Phone Number</b> <br>
                                    @endif

                                    @foreach($booking->dedicated_phones as $dedicated_phone)
                                        <br>
                                    @endforeach
                                    
                                    @if(sizeof($booking->furniture) > 0)
                                        <b>Furniture</b> <br>
                                    @endif
                                    @foreach($booking->furniture as $furniture)
                                        {{ $furniture->name }} <br>
                                    @endforeach
                                </td>

                                <td width="25%" class="text-left" style="top: 50%;">
                                    @foreach($booking->dedicated_phones as $dedicated_phone)
                                        : {{ $dedicated_phone->number }} <br>
                                    @endforeach
                                    <br>

                                    @if(sizeof($booking->furniture) > 0)
                                        &nbsp; <br>
                                    @endif
                                    @foreach($booking->furniture as $furniture)
                                        : {{ $furniture->pivot->quantity }} <br>
                                    @endforeach
                                </td>
                                <!-- Furniture/Phone Info -->
                            </tr>
                        </table>
                        <table width="100%" class="table table-bordered" style="vertical-align: center;">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td colspan="5">4. FEES</td>
                            </tr>
                            <tr>
                                <td width="20%"><b>Package</b></td>
                                <td width="15%"><b>Length of Term</b></td>
                                <td width="15%"><b>Quantity</b></td>
                                <td width="25%"><b>Detail Price</b></td>
                                <td width="25%" ><b>Total</b></td>
                            </tr>
                            @php
                                $total_price= 0;
                            @endphp
                            @foreach($booking->packages as $package)
                            <tr>
                                <td style="padding-top: 10px;padding-bottom: 10px;">
                                    <b>@if($package->has_service_charge == "Y") *) @endif</b> {{ $package->name }}
                                </td>
                                <td style="padding-top: 10px;padding-bottom: 10px;" class="text-center">
                                    {{ $package->pivot->length_of_term }} @if($booking->free_term_booking > 0)(-{{ $booking->free_term_booking }}) @endif
                                    @if($package->pivot->price_type == 'yearly')
                                        Year(s)
                                    @elseif($package->pivot->price_type == 'monthly')
                                        Month(s)
                                    @elseif($package->pivot->price_type == 'daily')
                                        Day(s)
                                    @elseif($package->pivot->price_type == 'hourly')
                                        Hours(s)
                                    @elseif($package->pivot->price_type == 'halfday')
                                        Hours(s)
                                    @endif
                                    <br> {{ date('j F Y', strtotime($package->pivot->start_date))}} - {{ date('j F Y', strtotime($package->pivot->end_date))}}

                                    @if($package->pivot->price_type == 'hourly' || $package->pivot->price_type == 'halfday')
                                        <br> {{ $package->pivot->start_time.' - '.$package->pivot->end_time }}
                                    @endif
                                </td>
                                <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">
                                    {{ number_format($package->pivot->quantity, 0, ',', '.') }}
                                </td>
                                <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                    @php
                                        if($booking->tax_status == 'include'){
                                            $package_price = $package->pivot->detail_price;
                                            $package_price = $package_price / (1 + $tax_percentage);
                                            if($package->has_service_charge == "Y"){
                                                $package_price = $package_price / (1 + $service_charge);
                                            }
                                        }else{
                                            $package_price = $package->pivot->detail_price;
                                        }
                                    @endphp

                                    Rp {{ number_format($package_price, 0, ',', '.') }}
                                </td>
                                <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                    @php
                                        $sub_total_main =0;
                                        $discount_price = $booking->discount_price;

                                        if($package->pivot->price_type == 'halfday'){
                                            $sub_total_main = $package_price * $package->pivot->quantity;
                                        }else{
                                            $sub_total_main = $package_price * $package->pivot->quantity* ($package->pivot->length_of_term - $booking->free_term_booking);
                                        }

                                        $total_price = $total_price + $sub_total_main;
                                    @endphp

                                    Rp {{ number_format($sub_total_main, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                @php
                                    $discount_price = $total_price - $booking->total_price;
                                @endphp
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
                                    <b>Discount @if($booking->usable_discount == "percentage")<span class="text-right">({{number_format($booking->discount_percentage, 0, ',', '.')}}%)</span>@endif</b>
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
                                    <b>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</b>
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
                                    Tax <span class="text-right">({{ number_format($tax_percentage * 100, 0, ',', '.') }}%)</span>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                                    Rp {{ number_format($booking->total_tax_price + $booking->total_tax_additional_charge, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;"><b>Total After Tax & Charge</b></td>
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
                        <p style="text-align: justify;">
                            <b>*)</b> Subject to service charge
                        </p>
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td colspan="2">5. REMARKS</td>
                            </tr>
                            <tr>
                                <td colspan="2">{!! $booking->remarks !!}</td>
                            </tr>
                        </table>
                        <p style="text-align: justify;">
                            The Licensee confirm that he/she has read and understood the term and conditions overleaf and agrees to be bound by them. The Licensor agrees to provide the services and Facilities as mentioned. We enter License Agreement and agree to all its terms and conditions.
                        </p>
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td colspan="2">6. SIGNATORIES</td>
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
                                <td><b>Name : </b></td>
                                <td><b>Name : {{$booking->contact->name}}</b></td>
                            </tr>
                            <tr>
                                <td>Date : {{date("j F Y",strtotime($booking->signed_date))}}</td>
                                <td>Date : {{date("j F Y",strtotime($booking->signed_date))}}</td>
                            </tr>
                        </table>
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
    function mail(link){
        document.sendMailForm.action = link;
        $("#sendMailModal").modal();
    }
</script>
@endsection
