<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($customers as $customer)
        <tr>
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->name }}</td>
           
        </tr>
    @endforeach
    </tbody>
</table>