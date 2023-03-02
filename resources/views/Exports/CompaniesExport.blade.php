<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Social Name</th>
        <th>Fantasy Name</th>
    </tr>
    </thead>
    <tbody>
    @foreach($companies as $company)
        <tr>
            <td>{{ $company->id }}</td>
            <td>{{ $company->social_name }}</td>
            <td>{{ $company->fantasy_name }}</td>
        </tr>
    @endforeach
    </tbody>
</table>