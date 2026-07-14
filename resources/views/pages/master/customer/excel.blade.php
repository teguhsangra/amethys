<table class="table table-bordered">
    <thead>
        <tr>
            <th>Customer Type</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Mobile Phone</th>
            <th>Fax</th>
            <th>Address</th>
            <th>Country</th>
            <th>City</th>
            <th>Zipcode</th>
            <th>Tax Number</th>
            <th>Virtual Account No</th>
            <th>Virtual Account Bank</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customer as $data)
        <tr>
            <td>{{ $data->customer_type }}</td>
            <td>{{$data->name}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->phone}}</td>
            <td>{{$data->mobile_phone}}</td>
            <td>{{$data->fax}}</td>
            <td>{{$data->address}}</td>
            <td>{{$data->country}}</td>
            <td>{{$data->city}}</td>
            <td>{{$data->zipcode}}</td>
            <td>{{$data->tax_number}}</td>
            <td>{{$data->zipcode}}</td>
            <td>{{$data->virtual_account_no}}</td>
            <td>{{$data->virtual_account_bank}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
