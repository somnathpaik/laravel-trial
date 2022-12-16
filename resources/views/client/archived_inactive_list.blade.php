<table class="table table-hover text-nowrap">
    <thead>
        <tr>
            <th>Client Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Status</th>
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
            <td>
                <div class="action_toggle">
                    <button class="dots_btn" data-id="{{ $client->uuid }}">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="buttons" id="{{ $client->uuid }}">
                        <a href="javascript:void(0);" class="open-modal" title="Edit" data-route="{{ route('client.edit', $client->uuid) }}">
                            <i class="fas fa-edit"></i>
                        </a> |
                        <a href="javascript:void(0);" class="prompt text-danger" title="Delete" data-method="DELETE" data-prompt-text="Are you sure to delete this ?" data-uuid="{{ $client->uuid }}" data-route="{{ route('client.destroy', $client->uuid) }}">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </div>
                </div>
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