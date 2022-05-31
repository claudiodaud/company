<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{{ $company->id }}</td>
            <td>{{ $company->name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>