<table  class="table table-border table-hover">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Invoice Number</th>
            <th>Invoice Date</th>
            <th>Invoice Due Date</th>
            <th>Amount Outstanding</th>
            <th>Current</th>
            <th>Aged 1 - 30</th>
            <th>Aged 31 - 60</th>
            <th>Aged 61 - 90</th>
            <th>Aged > 91</th>
        </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
            <?php
                $now = Carbon\Carbon::now();
                $due_date = Carbon\Carbon::parse($invoice->due_date);

                $diff = $now->diffInDays($due_date);
                $total_outstanding = $invoice->total_price + $invoice->total_service_charge + $invoice->total_tax_price + $invoice->stamp_duty + $invoice->round_price - $invoice->total_paid;

                if($due_date >= $now){
                    $diff = 0;
                }
            ?>
            <tr>
                <td>{{ $invoice->customer->name }}</td>
                <td>{{ $invoice->code }}</td>
                <td>{{ date("d M Y", strtotime($invoice->invoice_date)) }}</td>
                <td>{{ date("d M Y", strtotime($invoice->due_date)) }}</td>
                <td>{{ $total_outstanding }}</td>
                <td>
                    @if($diff == 0)
                        {{ $total_outstanding }}
                    @endif
                </td>
                <td>
                    @if($diff >= 1 && $diff <= 30)
                        {{ $total_outstanding }}
                    @endif
                </td>
                <td>
                    @if($diff >= 31 && $diff <= 60)
                        {{ $total_outstanding }}
                    @endif
                </td>
                <td>
                    @if($diff >= 61 && $diff <= 90)
                        {{ $total_outstanding }}
                    @endif
                </td>
                <td>
                    @if($diff >= 91)
                        {{ $total_outstanding }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
