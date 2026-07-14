@extends('layouts.app')
@section('title')
Rakomsis Inquiry - {{ $inquiry->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Inquiry</h4>
            </div>
            <div class="card-body">
                <div class="toolbar">
                    <a href="{{ url($url) }}" class="btn btn-rose">
                        <i class="fa fa-arrow-left"></i> Back To Inquiry
                    </a>
                    <div class="btn-group btn-group-md" role="group" >
                    <a onclick="print('{{ url($print_url) }}')" class="btn btn-primary pull-right text-white">
                        <i class="fa fa-print"></i> Print
                    </a>
                    </div>
                </div>
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
                                <td colspan="3"><b>DETAILS</b></td>
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
                            <tr style="vertical-align: top;">
                                <td style="padding-top: 1px;padding-bottom: 1px;">Term of Payment</td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
					@if($inquiry->term_of_payment == 1)
						Monthly in advance
					@elseif($inquiry->term_of_payment == 3)
						Quarterly in advance
					@elseif($inquiry->term_of_payment == 6)
						Semi-Annually in Advance
					@else
						Annually in Advance
					@endif
				</td>
                            </tr>
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
                                <td width="35%"><b>Inquiries</b></td>
                                <td width="15%"><b>Quantity</b></td>
                                <td width="25%"><b>Detail Price</b></td>
                                <td width="25%" ><b>Total</b></td>
                            </tr>
                            @if($inquiry->type == 'product')
                                <tr>
                                    <td style="padding-top: 10px;padding-bottom: 10px;">{{ $inquiry->product->name }}</td>
                                    <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">
                                        @php
                                            $length_of_term = $inquiry->length_of_term;

                                            if($inquiry->free_term_booking != null){
                                                $length_of_term = $inquiry->length_of_term - $inquiry->free_term_booking;
                                            }

					    $length_of_term = ($inquiry->term_of_payment) ? $inquiry->term_of_payment : 1;
                                        @endphp

                                        {{ $length_of_term }}

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
                                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                        {{ $default_currency }} {{ number_format($inquiry->detail_price) }}
                                    </td>
                                    <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                        {{ $default_currency }} {{ number_format($inquiry->detail_price * $length_of_term) }}
                                    </td>
                                </tr>
                                @if($inquiry->free_term_booking > 0)
                                    <tr>
                                        <td style="padding-top: 10px;padding-bottom: 10px;"><b>**)</b> {{ $inquiry->product->name }}</td>
                                        <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">

                                            {{ $inquiry->free_term_booking }}

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
                                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                            {{ $default_currency }} {{ number_format(0) }}
                                        </td>
                                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                            {{ $default_currency }} {{ number_format(0) }}
                                        </td>
                                    </tr>
                                @endif
                            @elseif($inquiry->type == 'package')
                                @foreach($inquiry->packages as $package)
                                    <tr>
                                        <td style="padding-top: 10px;padding-bottom: 10px;">{{ $package->name }}</td>
                                        <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">
                                            @php
                                                $length_of_term = $package->pivot->length_of_term;
                                            @endphp

                                            {{ $length_of_term }}

                                            @if($inquiry->price_type == 'yearly')
                                                Year(s)
                                            @elseif($inquiry->price_type == 'monthly')
                                                Month(s)
                                            @elseif($inquiry->price_type == 'daily')
                                                Day(s)
                                            @elseif($inquiry->price_type == 'hourly')
                                                Hours(s)
                                            @endif
                                            @if($package->pivot->quantity > 1)
                                                <br> {{ number_format($package->pivot->quantity) }}
                                            @endif
                                        </td>
                                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                            {{ $default_currency }} {{ number_format($package->pivot->detail_price) }}
                                        </td>
                                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                            {{ $default_currency }} {{ number_format($package->pivot->detail_price * $length_of_term) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif($inquiry->type == 'room')
                                @foreach($inquiry->rooms as $room)
                                    <tr>
                                        <td style="padding-top: 10px;padding-bottom: 10px;">{{ $room->room_number }}</td>
                                        <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">
                                            @php
                                                $length_of_term = $inquiry->length_of_term;

                                                if($inquiry->free_term_booking != null){
                                                    $length_of_term = $inquiry->length_of_term - $inquiry->free_term_booking;
                                                }

						$length_of_term = ($inquiry->term_of_payment) ? $inquiry->term_of_payment : 1; 
                                            @endphp

                                            {{ $length_of_term }}

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
                                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                            {{ $default_currency }} {{ number_format($room->pivot->detail_price) }}
                                        </td>
                                        <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                            {{ $default_currency }} {{ number_format($room->pivot->detail_price * $length_of_term) }}
                                        </td>
                                    </tr>
                                    @if($inquiry->free_term_booking > 0)
                                        <tr>
                                            <td style="padding-top: 10px;padding-bottom: 10px;"><b>**)</b> {{ $room->room_number }}</td>
                                            <td class="text-center" style="padding-top: 10px;padding-bottom: 10px;">

                                                {{ $inquiry->free_term_booking }}

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
                                            <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                                {{ $default_currency }} {{ number_format(0) }}
                                            </td>
                                            <td class="text-right" style="padding-top: 10px;padding-bottom: 10px;">
                                                {{ $default_currency }} {{ number_format(0) }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                <b>Discount @if($inquiry->usable_discount == "percentage")<span class="text-right">({{number_format($inquiry->discount_percentage, 0, ',', '.')}}%)</span>@endif</b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;">
                                    <b>{{ $default_currency }} {{ number_format($inquiry->discount_price) }}</b>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                    <b>Total After Discount</b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format(($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment)) }}</b></td>
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
                                <td ><b>Additional Charge</b> - {{ $product->name }}</td>
                                <td class="text-center">{{ number_format($product->pivot->quantity) }}</td>
                                <td class="text-right" class="text-center">{{ $default_currency }} {{number_format($detail_price)}}</td>
                                <td class="text-right"><b> {{ $default_currency }} {{ number_format($sub_total) }} </b></td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                    <b>Sub Total<span class="text-right"></span></b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format(($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $total_additional_charge) }}</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                    <b>Service Charge<span class="text-right">({{ number_format($service_charge * 100) }}%)</span></b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($inquiry->total_service_charge + $inquiry->total_service_charge_additional_charge) }}</b></td>
                            </tr>
                            <tr>
                                @php
                                    $total_price = ($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_service_charge + $total_additional_charge + $inquiry->total_service_charge_additional_charge;
                                @endphp
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                    <b>Total</b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($total_price) }}</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                    <b>VAT<span class="text-right">({{ number_format($tax_percentage * 100) }}%)</span></b>
                                </td>
                                @php
                                $vat = ($inquiry->total_tax_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_tax_additional_charge;
                                @endphp
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($vat) }}</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                <b>Security Deposit</b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format($inquiry->security_deposit) }}</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3">
                                    <b>Grand Total</b>
                                </td>
                                <td class="text-right" style="padding-top: 1px;padding-bottom: 1px;"> <b> {{ $default_currency }} {{ number_format(($inquiry->total_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $inquiry->total_service_charge + ($inquiry->total_tax_price / $inquiry->length_of_term * $inquiry->term_of_payment) + $total_additional_charge + $inquiry->total_service_charge_additional_charge + $inquiry->total_tax_additional_charge + $inquiry->security_deposit + $inquiry->stamp_duty + $inquiry->round_price) }}</b></td>
                            </tr>
                        </table>
                        <p style="text-align: justify;"><b>*)</b> Subject to service charge</p>
                        <p style="text-align: justify;"><b>**)</b> Free</p>
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #67128B !important;color: #FFF;">
                                <td colspan="2"><b>REMARKS</b></td>
                            </tr>
                            <tr>
                                <td colspan="2">{!! $inquiry->remarks !!}</td>
                            </tr>
                        </table>
                        <h3><i>This is a computer generated Inquiry no signature is required.</i></h3>
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
