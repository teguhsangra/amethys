<table>
    <thead>
        <tr>
            <td>Office Occupancy</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <th colspan="4" class="table-bordered bg-primary" >Metrix</th>
            @for($i=0; $i<=$total_month; $i++)
                <th class="table-bordered bg-primary">{{$array_of_str_month[$i]}} {{$array_of_str_year[$i]}}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$location->name}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Total Revenue</b></td>
            @for($i=0; $i<=$total_month; $i++)
                <td class="table-bordered">{{number_format($array_total_revenue_of_month[$i],0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>{{date("j F Y",strtotime($first_of_start_date))}} - {{date("j F Y",strtotime($end_of_end_date))}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Total Office Inventory</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td  class="table-bordered">{{number_format(sizeof($rooms),0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>Center Number</td>
            <td>{{$location->code}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Total SQM Inventory</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{number_format($total_sqm,0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>Average Term</td>
            <td>{{$total_term/$total_booking}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Total Occupied Office</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{number_format($array_total_occupied_office_of_month[$i],0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>Report Generated</td>
            <td>{{date("j F Y")}}</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Total Occupied SQM</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{number_format($array_total_occupied_sqm_of_month[$i],0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Average Price/Office</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{number_format($array_total_revenue_of_month[$i]/sizeof($rooms),0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Average Price/SQM</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{number_format($array_total_revenue_of_month[$i]/$total_sqm,0,'','')}}</td>
            @endfor
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>Office Occupancy</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{number_format($array_total_occupied_office_of_month[$i]/sizeof($rooms)*100,0,'','')}}%</td>
            @endfor
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="4" class="table-bordered bg-primary"><b>SQM Occupancy</b></td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">
                {{number_format($used_sqm/$total_sqm*100,0,'','')}}%
            </td>
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
            <th>Room No</th>
            <th>SQM</th>
            <th>Quoted Price</th>
            <th>Room Deposit</th>
            <th>Paid Deposit</th>
            <th>Customer</th>
            <th>Commencement Date</th>
            <th>End Date</th>
            <th>Term</th>
            @for($i=0; $i<=$total_month; $i++)
                <th>{{$array_of_str_month[$i]}} {{$array_of_str_year[$i]}}</th>
            @endfor
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $grand_total = 0;
        @endphp
        @foreach($rooms as $no=> $room)
            @php
                $total_in_row = 0;
            @endphp 
            <tr>
                <td class="table-info">{{ $room->room_number }}</td>
                <td class="table-info">{{ $room->sqm }}</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ number_format($booking->quoted_price, 0, '', '') }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ number_format($booking->quoted_price * 2, 0, '', '') }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ number_format($booking->security_deposit, 0, '', '') }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ $booking->customer->name }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ date("j F Y", strtotime($booking->start_date)) }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ date("j F Y", strtotime($booking->end_date)) }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ number_format($booking->length_of_term, 0, '', '') }} @endforeach</td>
                <td class="table-info">@foreach($array_of_booking[$no] as $booking) {{ $booking->employee->name }} @endforeach</td>
                @for($i=0; $i<=$total_month; $i++)
                    @php
                        $total_in_row = $total_in_row + $array_of_booking_price[$no][$i];
                        $grand_total = $grand_total + $array_of_booking_price[$no][$i];
                    @endphp
                    <td>{{ number_format($array_of_booking_price[$no][$i]) }}</td>
                @endfor
                <td class="success text-right">{{ number_format($total_in_row, 0, '', '') }}</td>
            </tr>
        @endforeach
        <tr class="table-success">
            <td>Grand total</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            @for($i=0; $i<=$total_month; $i++)
                <td class="table-bordered">
                    {{number_format($array_total_revenue_of_month[$i],0,'','')}}
                </td>
            @endfor
            <td>{{number_format($grand_total,0,'','')}}</td>
        </tr>
    </tbody>
</table>
