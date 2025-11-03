<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>VO Occupancy Reports</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
            <style type="text/css">
            .table-bordered {
            border: 5px solid #ddd !important;
            }
            .bg-primary {
                color: #000;
                background-color: #d9edf7;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <td>VO Occupancy</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <th colspan="5" class="table-bordered bg-primary" >Metrix</th>
                    @for($i=0; $i<=$total_month; $i++)
                        <th class="table-bordered bg-primary">{{$array_of_str_month[$i]}} <br> {{$array_of_str_year[$i]}}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$location->name}}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td colspan="5" class="table-bordered bg-primary"><b>Total Revenue</b></td>
                    @for($i=0; $i<=$total_month; $i++)
                        <td class="table-bordered">{{$array_total_revenue_of_month[$i]}}</td>
                    @endfor
                </tr>
                <tr>
                    <td>Center Number</td>
                    <td>{{$location->code}}</td>
                    <td>&nbsp;</td>
                    <td colspan="5" class="table-bordered bg-primary"><b>Total VO</b></td>
                    @for($i=0; $i<=$total_month; $i++)
                        <td class="table-bordered">{{$array_total_vo_of_month[$i]}}</td>
                    @endfor
                </tr>
                <tr>
                    <td>Average Term</td>
                    <td>{{ $total_term/sizeof($bookings) }}</td>
                    <td>&nbsp;</td>
                    <td colspan="5" class="table-bordered bg-primary"><b>Average Price</b></td>
                    @for($i=0; $i<=$total_month; $i++)
                        <td class="table-bordered">@if($array_total_vo_of_month[$i] > 0) {{ $array_total_revenue_of_month[$i]/$array_total_vo_of_month[$i] }}@endif</td>
                    @endfor
                </tr>
            </tbody>
        </table>
        <table>
            <tr>
            </tr>
            <tr>
                <td><b>Detail Report</b></td>
            </tr>
        </table>
        <table>
            <thead>
                <tr class="table-bordered bg-primary">
                    <th>Service Name</th>
                    <th>Quoted Price</th>
                    <th>Paid Deposit</th>
                    <th>Customer</th>
                    <th>Commencement Date</th>
                    <th>End Date</th>
                    <th>Term</th>
                    <th>Sales</th>
                    @for($i=0; $i<=$total_month; $i++)
                        <th>{{$array_of_str_month[$i]}} <br> {{$array_of_str_year[$i]}}</th>
                    @endfor
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grand_total = 0;
                @endphp
                @foreach($bookings as $no=> $booking)
                    @php
                        $total_in_row = 0;
                    @endphp 
                    <tr>
                        <td class="table-info">{{ $booking->product->name }}</td>
                        <td class="table-info">{{ $booking->product->price }}</td>
                        <td class="table-info">{{ $booking->security_deposit }}</td>
                        <td class="table-info">{{ $booking->customer->name }}</td>
                        <td class="table-info">{{ date("j F Y",strtotime($booking->start_date)) }}</td>
                        <td class="table-info">{{ date("j F Y",strtotime($booking->end_date)) }}</td>
                        <td class="table-info">{{ $booking->length_of_term }}</td>
                        <td class="table-info">{{ $booking->employee->name }}</td>
                        @for($i=0; $i<=$total_month; $i++)
                            @php
                                $total_in_row = $total_in_row + $array_of_booking_price[$no][$i];
                                $grand_total = $grand_total + $array_of_booking_price[$no][$i];
                            @endphp
                            <td>{{ $array_of_booking_price[$no][$i] }}</td>
                        @endfor
                        <td class="success text-right">{{ $total_in_row }}</td>
                    </tr>
                @endforeach
                <tr class="success">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    @for($i=0; $i<=$total_month; $i++)
                        <td class="table-bordered">
                            {{ $array_total_revenue_of_month[$i] }}
                        </td>
                    @endfor
                    <td>{{ $grand_total }}</td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
