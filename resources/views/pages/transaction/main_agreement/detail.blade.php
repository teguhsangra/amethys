@extends('layouts.app')
@section('title')
Rakomsis Main Agreement - {{ $booking->code }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-info card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Detail Main Agreement</h4>
            </div>
            <div class="card-body">
                <a href="{{ url($url) }}" class="btn btn-rose">
                    <i class="fa fa-arrow-left"></i> Back To Main Agreement
                </a>
                <div class="toolbar">

                    <div class="btn-group btn-group-md" role="group" >
                        <a onclick="print('{{ url($print_url) }}')" class="btn  btn-info pull-right text-white">
                            <i class="fa fa-print"></i> Agreement
                        </a>
                        <a onclick="print('{{ url($domicile_url) }}')" class="btn  btn-success pull-right text-white">
                            <i class="fa fa-print"></i> Domicile & House Rule
                        </a>
                        <a onclick="print('{{ url($term_condition_url) }}')" class="btn  btn-warning pull-right text-white">
                            <i class="fa fa-print"></i> Term & Condition
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
                                            <td width="40%">Company Name</td>
                                            <td width="10%">:</td>
                                            <td width="50%">{{$company_name}}</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Address</td>
                                            <td>:</td>
                                            <td>{{$company_address_1}}, {{$company_address_1}}</td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Tlp & Fax</td>
                                            <td>:</td>
                                            <td>{{$company_phone}} & {{$company_fax}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Bank</td>
                                            <td>:</td>
                                            <td>{{$bank_account->bank_name}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Bank Account No</td>
                                            <td>:</td>
                                            <td>{{$bank_account->account_no}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Currency Code</td>
                                            <td>:</td>
                                            <td>{{$bank_account->currency_code}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Branch Code</td>
                                            <td>:</td>
                                            <td>{{$bank_account->branch_code}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>SWIFT Code</td>
                                            <td>:</td>
                                            <td>{{$bank_account->swift_code}}</td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table width="100%" class="table-borderless">
                                        <tr style="vertical-align: top;">
                                            <td width="40%">Company Name</td>
                                            <td width="10%">:</td>
                                            <td width="50%">{{$booking->customer->name}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Tax Number (NPWP)</td>
                                            <td>:</td>
                                            <td>{{$booking->customer->tax_number}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Address</td>
                                            <td>:</td>
                                            <td>{{$booking->customer->address}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>E-mail Address</td>
                                            <td>:</td>
                                            <td>{{$booking->customer->email}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Contact Name</td>
                                            <td>:</td>
                                            <td>{{$booking->contact->name}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Telephone</td>
                                            <td>:</td>
                                            <td>{{$booking->contact->phone}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Mobile</td>
                                            <td>:</td>
                                            <td>{{$booking->contact->mobile_phone}}</td>
                                        </tr>
                                        <tr style="vertical-align: top;">
                                            <td>Fax</td>
                                            <td>:</td>
                                            <td>{{$booking->customer->fax_no}}</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td colspan="4">3. DETAILS</td>
                            </tr>
                            <tr>
                                <!-- Service Info -->
                                <td width="25%">
                                    Service Type<br>
                                    @if($booking->type == "package")
                                    Details
                                    @endif
                                    @if($booking->type == "product")
                                    Details
                                    @endif
                                    @if($booking->type == "room")
                                        Details
                                        @foreach($booking->rooms as $no => $room)
                                            @if($no > 0)
                                                <br>
                                            @endif
                                        @endforeach
                                    @endif
                                    <br>
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
                                    @if($booking->type == "package") : Package @endif
                                    @if($booking->type == "product") : Virtual Office @endif
                                    @if($booking->type == "room") : Serviced Office @endif
                                    <br>
                                    @if($booking->type == "product")
                                        : {{$booking->product->name}}
                                    @endif
                                    @if($booking->type == "room")
                                        @foreach($booking->rooms as $no => $room)
                                            @if($no > 0)
                                                <br>
                                            @endif
                                            : {{$room->room_number}}
                                        @endforeach
                                    @endif
                                    <br>
                                    : {{date("j F Y",strtotime($booking->start_date))}}<br>
                                    : {{date("j F Y",strtotime($booking->end_date))}}<br>
                                    : {{$booking->length_of_term}} Months<br>
                                    : {{$booking->term_notice_period}}-Month Notification<br>
                                    :
                                    @if($booking->term_of_payment == 1) Monthly @endif
                                    @if($booking->term_of_payment == 4) Quarterly @endif
                                    @if($booking->term_of_payment == 6) Semi-Annually @endif
                                    @if($booking->term_of_payment == 12) Annually @endif
                                    @if($booking->booking_type == "room")
                                        <br>
                                        :
                                        @php
                                            $number_of_workstation = 0;
                                        @endphp
                                        @foreach($booking->rooms as $room)
                                            @php
                                                $number_of_workstation = $number_of_workstation + $room->number_of_workstation;
                                            @endphp
                                        @endforeach
                                        {{$number_of_workstation}}
                                    @endif
                                </td>
                                <!-- Service Info -->

                                <!-- Furniture/Phone Info -->
                                <td width="25%">
                                    @if($booking->type == "product")
                                        <br>
                                        <h5>Phone Numbers</h5>
                                        <br>
                                        <h5>Fax Number</h5>
                                    @endif
                                    @if($booking->type == "room")
                                        @foreach($booking->furniture as $furniture)
                                            {{$furniture->name}}<br>
                                        @endforeach
                                    @endif
                                </td>

                                <td width="25%" class="text-left">
                                    @if($booking->type == "product")
                                        <br>
                                        <h5>: {{$booking->dedicated_number}}</h5>
                                        <br>
                                        <h5>: {{$booking->fax_number}}</h5>
                                    @endif
                                    @if($booking->type == "room")
                                        @foreach($booking->furniture as $furniture)
                                        : {{$furniture->pivot->quantity}}<br>
                                        @endforeach
                                    @endif
                                </td>
                                <!-- Furniture/Phone Info -->
                            </tr>
                        </table>
                        <table width="100%" class="table table-bordered">
                            <tr style="background-color: #4169E1 !important;color: #FFF;">
                                <td colspan="5">4. FEES</td>
                            </tr>
                            <tr>
                                <td width="20%"><b>Service Type</b></td>
                                <td width="15%"><b>Length of Term</b></td>
                                <td width="15%"><b>Quantity</b></td>
                                <td width="25%"><b>Detail Price</b></td>
                                <td width="25%" ><b>Total</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 10px;padding-bottom: 10px;">
                                @if($booking->type == 'package')
                                    {{ $booking->package->name }}
                                @endif
                                @if($booking->type == 'product')
                                    {{ $booking->product->name }}
                                @endif
                                @if($booking->type == 'room')
                                    @foreach($booking->rooms as $rooms)
                                        {{$rooms->room_number}}
                                    @endforeach
                                @endif
                                </td>
                                <td style="padding-top: 10px;padding-bottom: 10px;" class="text-center">
                                    {{ $booking->length_of_term }}
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
                                <td style="padding-top: 10px;padding-bottom: 10px;">
                                {{ $booking->quantity }}
                                </td>
                                <td style="padding-top: 10px;padding-bottom: 10px;">
                                Rp {{ number_format($booking->detail_price, 0, ',', '.') }}
                                </td>
                                <td style="padding-top: 10px;padding-bottom: 10px;">
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
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
                                <b>Discount @if($booking->usable_discount == "percentage")<span class="text-right">({{number_format($booking->discount_percentage, 0, ',', '.')}}%)</span>@endif</b>
                                </td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">

                                Rp {{ number_format($booking->discount_price, 0, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                @php
                                    $total_price = $total_price - $discount_price;
                                @endphp
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
                                Total After Discount
                                </td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp <b>{{ number_format($total_price) }}</b></td>
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
                                    $sub_total = $detail_price * $product->pivot->quantity;
                                    $total_additional_charge = $total_additional_charge + $sub_total;

                                    if($booking->tax_status == "no_tax"){

                                    }else if($booking->tax_status == "exclude"){
                                        $total_tax_additional_charge = $total_additional_charge *$tax_percentage;
                                    }else if($booking->tax_status == "include"){
                                        $temp_2 = $total_additional_charge;
                                        $total_additional_charge = $total_additional_charge / (1 + $tax_percentage);
                                        $total_tax_additional_charge = $temp_2 - $total_additional_charge;
                                    }else{
                                        $total_tax_additional_charge = 0;
                                    }
                                @endphp
                            <tr>
                                <td colspan="2" style="padding-top: 1px;padding-bottom: 1px;"><b>Additional Charge</b> - {{ $product->name }}</td>
                                <td>{{ number_format($product->pivot->quantity, 0, ',', '.') }}</td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp {{number_format($detail_price, 0, ',', '.')}}</td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp {{ number_format($sub_total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
                                <b>Tax & Service <span class="text-right">(21%)</span></b>
                                </td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp <b>{{ number_format($booking->total_tax_price + $total_tax_additional_charge, 0, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
                                <b>Security Deposit</b>
                                </td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp <b>{{ number_format($booking->security_deposit, 0, ',', '.') }}</b></td>
                            </tr>
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">
                                <b>Stamp Duty (Materai)</b>
                                </td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp <b>{{ number_format($booking->stamp_duty, 0, ',', '.') }}</b></td>
                            </tr>

                            @if(sizeof($booking->products) > 0)
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;"><b>Grand Total</b></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp <b>{{ number_format($total_price + $booking->total_tax_price + $total_additional_charge + $total_tax_additional_charge + $booking->security_deposit + $booking->stamp_duty, 0, ',', '.') }}</b></td>
                            </tr>
                            @else
                            <tr>
                                <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;"><b>Grand Total</b></td>
                                <td style="padding-top: 1px;padding-bottom: 1px;">Rp <b>{{ number_format($total_price + $booking->total_tax_price + $booking->security_deposit + $booking->stamp_duty, 0, ',', '.') }}</b></td>
                            </tr>
                            @endif
                        </table>
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
                                    <br><br><br><br><br><br><br><br><br>
                                </td>
                                <td>
                                    <p>Signature & Company Seal</p>
                                    <br><br><br><br><br><br><br><br><br>
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
