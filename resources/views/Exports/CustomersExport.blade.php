<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Social Name</th>
        <th>Fantasy Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->social_name }}</td>
            <td>{{ $customer->fantasy_name }}</td>
           
        </tr>
    @endforeach
    </tbody>
</table>