<table  class="table table-border table-hover">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Location</th>
                    <th>Customer</th>
                    <th>Tax Number (NPWP)</th>
                    <th>Address</th>
                    <th>E-mail Address</th>
                    <th>Contact Name</th>
                    <th>Telephone</th>
                    <th>Mobile</th>
                    <th>Fax</th>
                    <th>Service Type</th>
                    <th>Service Detail</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Length of Term</th>
                    <th>Term Notice Period</th>
                    <th>Term of payment</th>
                    <th>Monthly Price</th>
                    <th>Number of Workstations</th>
                    <th>Total Rent</th>
                    <th>Tax (VAT/PPN)</th>
                    <th>Total Fees Including Tax</th>
                    <th>Created Date</th>
                </tr>
            </thead>
            <tbody>
            @foreach($bookings as $bookings)
                <tr>
                    <td class="cell">{{$bookings->code}}</td>
                    <td class="cell">{{$bookings->location->name}}</td>
                    <td class="cell">{{$bookings->customer->name}}</td>
                    <td class="cell">{{$bookings->customer->tax_number}}</td>
                    <td class="cell">{{$bookings->customer->address}}</td>
                    <td class="cell">{{$bookings->customer->email}}</td>
                    <td class="cell">{{$bookings->contact->name}}</td>
                    <td class="cell">{{$bookings->customer->phone}}</td>
                    <td class="cell">{{$bookings->customer->mobile_phone}}</td>
                    <td class="cell">{{$bookings->customer->fax}}</td>
                    <td class="cell">
                    @if($bookings->type == "package") Package @endif
                    @if($bookings->type == "product") Virtual Office @endif
                    @if($bookings->type == "room") Serviced Office @endif
                    </td>
                    <td class="cell">
                        @if($bookings->type == "package")
                            {{$bookings->package->name}}
                        @endif
                        @if($bookings->type == "product")
                            {{$bookings->product->name}}
                        @endif
                        @if($bookings->type == "room")
                            @foreach($bookings->rooms as $no => $room)
                                <p>{{$room->room_number}}</p>
                            @endforeach
                        @endif
                    </td>
                    <td class="cell">
                        {{$bookings->start_date}}
                    </td>
                    <td class="cell">
                        {{$bookings->end_date}}
                    </td>
                    <td class="cell">{{$bookings->length_of_term}} Months </td>
                    <td class="cell">{{$bookings->term_notice_period}}-Month Notification</td>
                    <td class="cell">
                        @if($bookings->term_of_payment == 1) Monthly @endif
                        @if($bookings->term_of_payment == 4) Quarterly @endif
                        @if($bookings->term_of_payment == 6) Semi-Anually @endif
                        @if($bookings->term_of_payment == 12) Anually @endif
                    </td>
                   <td class="cell">
                    @if($bookings->type == "package")
                        <p>{{$bookings->package->price}}</p>
                    @endif
                    @if($bookings->type == "product")
                        <p>{{$bookings->product->price}}</p>
                    @endif
                    @if($bookings->type == "room")
                        @foreach($bookings->rooms as $no => $room)
                            <p>{{$room->pivot->monthly_price}}</p>
                        @endforeach
                    @endif
                   </td>
                   <td class="cell">
                       @if($bookings->type == "product")
                            -
                        @endif
                        @if($bookings->type == "room")
                            @foreach($bookings->rooms as $no => $room)
                                <p>{{$room->number_of_workstation}}</p>
                            @endforeach
                        @endif
                   </td>
                   <td class="cell">

                       {{$bookings->detail_price}}
                   </td>
                   <td class="cell">
                       {{$bookings->total_tax_price}}
                   </td>
                   <td class="cell">
                       {{$bookings->total_price + $bookings->total_tax_price}}
                   </td>
                    <td class="cell">
                        {{$bookings->created_at->format('Y-m-d H:i:s')}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
