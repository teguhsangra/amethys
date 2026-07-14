<table class="table table-bordered">
    <thead>
        <tr>
            <th>Detail List Booking use Complimentary</th>
        </tr>
        <tr>
            <th>Booking No</th>
            <th>Customer</th>
            <th>Sales</th>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Type Complimentary</th>
            <th>Detail Use Complimentary</th>
        </tr>
    </thead>
    <tbody>
        @foreach($booking_detail as $booking_detail)
            <tr>
                <td>{{ $booking_detail->booking->code }}</td>
                <td>{{ $booking_detail->booking->customer->name }}</td>
                <td>{{ $booking_detail->booking->employee->name }}</td>
                <td>
                    @if($booking_detail->booking->type == "product")
                        VO
                    @elseif($booking_detail->booking->type =="room")
                        {{ $booking->room_category->code }}
                    @endif
                </td>
                <td>{{ $booking_detail->start_date }}</td>
                <td>{{ $booking_detail->end_date }}</td>
                <td>{{ $booking_detail->complimentary->name}}</td>
                <td>{{ $booking_detail->total_complimentary }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
