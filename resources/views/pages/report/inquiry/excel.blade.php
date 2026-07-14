<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Type</th>
            <th>Referral</th>
            <th>Customer Status</th>
            <th>Customer</th>
            <th>Contact Name</th>
            <th>Address</th>
            <th>Tlp & Fax</th>
            <th>Email</th>
            <th>Location</th>
            <th>Commencement Date</th>
            <th>End Date</th>
            <th>Length of Term</th>
            <th>Sales Name</th>
        </tr>
    </thead>
    <tbody>
    @foreach($inquiry as $inquiry)
        <tr>
            <td >{{$inquiry->code}}</td>
            <td >
                @if($inquiry->type == "package")
                    Package {{$inquiry->package->name}}
                @endif
                @if($inquiry->type == "product")
                    Virtual Office {{$inquiry->product->name}}
                @endif
                @if($inquiry->type == "room")
                    Serviced Office
                    <ol>
                    @foreach($inquiry->room as $no => $room)
                    <li>Room {{ $room->room_number}} ({{ $room->location->name}})</li>
                    @endforeach
                    </ol>
                @endif
            </td>
            <td >
                {{ $inquiry->referral->name }}
            </td>
            <td >
                @if($inquiry->customer_status == "N")
                    New Customer
                @endif
                @if($inquiry->customer_status == "E")
                    Exist Customer
                @endif
            </td>
            <td >
                {{$inquiry->customer->name}}
            </td>
            <td >
                {{$inquiry->contact->name}}
            </td>
            <td >
                {{ $inquiry->customer->address }}
            </td>
            <td >
                {{ $inquiry->customer->phone }} & {{ $inquiry->customer->fax }}
            </td>
            <td >
                {{$inquiry->customer->email}}
            </td>
            <td >{{$inquiry->location->name}}</td>
            <td >{{$inquiry->start_date}}</td>
            <td >{{$inquiry->end_date}}</td>
            <td >{{$inquiry->length_of_term}} Months</td>
            <td >{{$inquiry->employee->name}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
