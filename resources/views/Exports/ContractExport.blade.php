<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($contracts as $contract)
        <tr>
            <td>{{ $contract->id }}</td>
            <td>{{ $contract->name }}</td>
           
        </tr>
    @endforeach
    </tbody>
</table>