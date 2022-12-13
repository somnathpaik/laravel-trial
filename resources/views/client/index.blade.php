<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Status</th>
            <th>Popularity Count</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody class="list">
        @forelse ($clients as $client)
        <tr id="userid-{{ $client->uuid }}" class="table-data">
            <td>{{ $client->name }}</td>
            <td>{{ $client->clientContact->email }}</td>
            <td>{{ $client->clientContact->mobile }}</td>
            <td>{{ $client->is_active_text }}</td>
            <td>{{ $client->popularity_count }}</td>
            <td>
                <a href="javascript:void(0);" class="open-modal" title="Edit" data-route="{{ route('client.edit', $client->uuid) }}">Edit</a> |
                <a href="javascript:void(0);" class="delete-client" title="Delete" data-uuid="{{ $client->uuid }}" data-route="{{ route('client.destroy', $client->uuid) }}">Delete</a>
            </td>
        </tr>
        @empty
        <tr class="no-data-table-row">
            <td colspan="100%" class="text-center">No data found</td>
        </tr>
        @endforelse

    </tbody>
</table>
{{ $clients->appends($_GET)->links() }}