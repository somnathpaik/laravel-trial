<tr id="userid-{{ $client->uuid }}" class="table-data">
    <td>{{ $client->name }}</td>
    <td>{{ $client->loadMissing('clientContact')->clientContact->email }}</td>
    <td>{{ $client->loadMissing('clientContact')->clientContact->mobile }}</td>
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
                </a> |
                <a href="javascript:void(0);" class="prompt text-info" title="archived" data-method="GET" data-prompt-text="Are you sure to archived this ?" data-uuid="{{ $client->uuid }}" data-route="{{ route('client.archived', $client->uuid) }}">
                    <i class="fas fa-archive"></i>
                </a>
            </div>
        </div>
    </td>
</tr>