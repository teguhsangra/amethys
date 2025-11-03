
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Inquiry List</th>
        </tr>
        <tr>
            <th>Inquiry No</th>
            <th>Customer</th>
            <th>Sales</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Price</th>
            <th>Total Service Charge</th>
            <th>Total VAT</th>
            <th>Total Additional Charge</th>
            <th>Total Additional Service Charge</th>
            <th>Total Additional VAT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($inquiries as $inquiry)
            <tr>
                <td>{{ $inquiry->code }}</td>
                <td>{{ $inquiry->customer->name }}</td>
                <td>{{ $inquiry->employee->name }}</td>
                <td>
                    @if($inquiry->type == "product")
                        VO
                    @elseif($inquiry->type =="room")
                        {{ $inquiry->room_category->code }}
                    @endif
                </td>
                <td>{{ $inquiry->start_date }}</td>
                <td>{{ $inquiry->end_date }}</td>
                <td>{{ $inquiry->total_price }}</td>
                <td>{{ $inquiry->total_service_charge }}</td>
                <td>{{ $inquiry->total_tax_price }}</td>
                <td>{{ $inquiry->total_additional_charge }}</td>
                <td>{{ $inquiry->total_service_charge_additional_charge }}</td>
                <td>{{ $inquiry->total_tax_additional_charge }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Booking List</th>
        </tr>
        <tr>
            <th>Booking No</th>
            <th>Customer</th>
            <th>Sales</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Price</th>
            <th>Total Service Charge</th>
            <th>Total VAT</th>
            <th>Total Additional Charge</th>
            <th>Total Additional Service Charge</th>
            <th>Total Additional VAT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->code }}</td>
                <td>{{ $booking->customer->name }}</td>
                <td>{{ $booking->employee->name }}</td>
                <td>
                    @if($booking->type == "product")
                        VO
                    @elseif($booking->type =="room")
                        {{ $booking->room_category->code }}
                    @endif
                </td>
                <td>{{ $booking->start_date }}</td>
                <td>{{ $booking->end_date }}</td>
                <td>{{ $booking->total_price }}</td>
                <td>{{ $booking->total_service_charge }}</td>
                <td>{{ $booking->total_tax_price }}</td>
                <td>{{ $booking->total_additional_charge }}</td>
                <td>{{ $booking->total_service_charge_additional_charge }}</td>
                <td>{{ $booking->total_tax_additional_charge }}</td>
            </tr>
        @endforeach
    </tbody>
</table>