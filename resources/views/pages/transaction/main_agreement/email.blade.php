<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/css/bootstrap.min.css?v=2.1') }}" rel="stylesheet" media="screen,print"/>

</head>
<body>


<br><br>
<div class="container" style="font-family: Calibri;font-size:20px;">
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
            <img class="img-fluid" src="{{ asset('LOGO-GRHA-165.png') }}" width="350" height="300">
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #4169E1 !important;color: #FFF;">
                    <td width="50%">CUSTOMER DETAIL</td>
                    <td width="50%">Person In Charge (PIC)</td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" class="table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="40%">Customer Name</td>
                                <td width="10%">:</td>
                                <td width="50%">{{ $booking->customer->name }}</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Address</td>
                                <td>:</td>
                                <td>{{ $booking->customer->name }}</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width="100%" class="table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="40%">Name</td>
                                <td width="10%">:</td>
                                <td width="50%">@if($booking->contact_id != null) {{ $booking->contact->name }} @endif</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Phone Number</td>
                                <td>:</td>
                                <td>@if($booking->contact_id != null) {{ $booking->contact->phone }} @endif</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #4169E1 !important;color: #FFF;">
                    <td width="50%">GENERAL INFO</td>
                    <td width="50%">DATE & TIME INFO</td>
                </tr>
                <tr>
                    <td>
                        <table width="100%" class="table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="40%">Product Interest</td>
                                <td width="10%">:</td>
                                <td width="50%">@if($booking->package_id != null)
                                                    {{ $booking->package->name }}
                                                @endif
                                </td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Employee</td>
                                <td>:</td>
                                <td>{{ $booking->employee->name }}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Referral/Agent</td>
                                <td>:</td>
                                <td>
                                    @if($booking->referral_id != null)
                                        {{ $booking->referral->name }}
                                    @endif

                                    @if($booking->agent_id != null)
                                        {{ $booking->agent->name }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table width="100%" class="table-borderless">
                            <tr style="vertical-align: top;">
                                <td width="40%">Start Time</td>
                                <td width="10%">:</td>
                                <td width="50%">{{ $booking->start_time }}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>End Time</td>
                                <td>:</td>
                                <td>{{ $booking->end_time }}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Commencement Date</td>
                                <td>:</td>
                                <td>{{ $booking->start_date }}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>Length Of Term</td>
                                <td>:</td>
                                <td>{{ $booking->length_of_term }}</td>
                            </tr>
                            <tr style="vertical-align: top;">
                                <td>End Date</td>
                                <td>:</td>
                                <td>{{ $booking->end_date }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #4169E1 !important;color: #FFF;">
                    <td colspan="2">REMARKS</td>
                </tr>
                <tr>
                    <td colspan="2">{!! $booking->remarks !!}</td>
                </tr>
            </table>
            <table width="100%" class="table table-bordered">
                <tr style="background-color: #4169E1 !important;color: #FFF;">
                    <td colspan="5">FEES</td>
                </tr>
                <tr>
                    <td width="20%"><b>Inquiries</b></td>
                    <td width="15%"><b>Length of Term</b></td>
                    <td width="15%"><b>Quantity</b></td>
                    <td width="25%"><b>Detail Price</b></td>
                    <td width="25%" ><b>Total</b></td>
                </tr>
                <tr>
                    <td style="padding-top: 10px;padding-bottom: 10px;">
                    @if($booking->package_id != null)
                        {{ $booking->package->name }}
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
                        @endif
                    </td>
                    <td style="padding-top: 10px;padding-bottom: 10px;">
                    {{ $booking->quantity }}
                    </td>
                    <td style="padding-top: 10px;padding-bottom: 10px;">
                    Rp {{ number_format($booking->detail_price, 0, ',', '.') }}
                    </td>
                    <td style="padding-top: 10px;padding-bottom: 10px;">
                    @if($booking->tax_status == 'no_tax' || $booking->tax_status == 'exclude')
                        Rp {{ number_format($booking->total_price + $booking->discount_price, 0, ',', '.') }}
                    @else
                        Rp {{ number_format($booking->total_price + $booking->total_tax_price + $booking->discount_price, 0, ',', '.') }}
                    @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                    Discount Price
                    </td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">

                    Rp {{ number_format($booking->discount_price, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 1px;padding-bottom: 1px;" colspan="3"></td>
                    <td style="padding-top: 1px;padding-bottom: 1px;">
                    <b>Total Price</b>
                    </td>
                    <td style="padding-top: 1px;padding-bottom: 1px;"><b>
                    @if($booking->tax_status == 'no_tax' || $booking->tax_status == 'exclude')
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    @else
                        Rp {{ number_format($booking->total_price + $booking->total_tax_price, 0, ',', '.') }}
                    @endif</b></td>
                </tr>
            </table>
            <p style="text-align: justify;">
                The Licensee confirm that he/she has read and understood the term and conditions overleaf and agrees to be bound by them. The Licensor agrees to provide the services and Facilities as mentioned. We enter License Agreement and agree to all its terms and conditions.
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
window.onload=function(){self.print();}
</script>
</body>
</html>
