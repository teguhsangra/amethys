<table  class="table table-border table-hover">
    <thead>
        <tr>
            <th>Service Type</th>
            <th>Location Name</th>
            <th>Code</th>
            <th>Customer</th>
            <th>Customer Email</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
    </thead>
    <tbody>
    @foreach($bookings as $booking)
        <tr>
            <td class="cell">
                @if($booking->type == 'product')
                    Virtual Office
              @elseif($booking->type == 'room')
                    @if($booking->room_category_id == 1){
                        Serviced Office
                    @elseif($booking->room_category_id == 2){
                        Meeting Room
                        @elseif($booking->room_category_id == 3){
                        Workstation
                        @elseif($booking->room_category_id == 4){
                        Hotel
                        @elseif($booking->room_category_id == 5){
                         Regular Office
                    @endif
                @endif
            </td>
            <td class="cell">{{$booking->location_name}}</td>
            <td class="cell">{{$booking->code}}</td>
            <td class="cell">{{$booking->customer_name}}</td>
            <td class="cell">{{$booking->customer_email}}</td>
            <td class="cell">{{$booking->start_date}}</td>
            <td class="cell">{{$booking->end_date}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
