<table>
    <thead>
        <tr>
            <td class="table-bordered">Customer</td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{$array_of_str_month[$i]}} {{$array_of_str_year[$i]}}</td>
            @php
                $grand_total_payment[$i] = 0;
            @endphp
            @endfor
        </tr>
    </thead>
    <tbody>
    @foreach($payments as $no=> $payment)
        @php
            $total_of_summary = 0;
        @endphp
        <tr>
            <td class="table-bordered">{{ $payment->customer['name'] }}</td>
            @for($i=0; $i<=$total_month; $i++)
            <td>
                @if($payment->payment_date >= $array_of_first_month[$i] && $payment->payment_date <= $array_of_end_month[$i])
                    @foreach($payment->payment_detail as $detail)
                        @php
                            $total_of_summary = $total_of_summary + $detail->amount;
                            $grand_total_payment[$i] = $grand_total_payment[$i] + $total_of_summary;
                        @endphp
                    @endforeach
                    {{ $total_of_summary }}
                @endif
            </td>
            @endfor
        </tr>
    @endforeach
        <tr>
            <td class="table-bordered">Total</td>
            @for($i=0; $i<=$total_month; $i++)
            <td class="table-bordered">{{$grand_total_payment[$i]}}</td>
            @endfor
        </tr>
    </tbody>
</table>
