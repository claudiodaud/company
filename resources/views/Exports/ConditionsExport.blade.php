<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($conditions as $condition)
        <tr>
            <td>{{ $condition->id }}</td>
            <td>{{ $condition->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>