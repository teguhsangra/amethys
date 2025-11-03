<table class="table table-bordered">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Name</th>
            <th>ID Number</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Mobile Phone</th>
            <th>Birth Date</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        @foreach($contact as $data)
        <tr>
            <td>{{ $data->customer->name }}</td>
            <td>{{$data->honorific}} {{$data->name}}</td>
            <td>{{$data->id_number}}</td>
            <td>{{$data->email}}</td>
            <td>{{$data->phone}}</td>
            <td>{{$data->mobile_phone}}</td>
            <td>{{$data->birth_date}}</td>
            <td>{{$data->address}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
