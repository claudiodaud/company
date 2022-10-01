<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($quotes as $quote)
        <tr>
            <td>{{ $quote->id }}</td>
            <td>{{ $quote->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>