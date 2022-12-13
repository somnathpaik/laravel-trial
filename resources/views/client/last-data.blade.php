<tr id="userid-{{ $client->uuid }}" class="table-data">
    <td>{{ $client->name }}</td>
    <td>{{ $client->loadMissing('clientContact')->clientContact->email }}</td>
    <td>{{ $client->loadMissing('clientContact')->clientContact->mobile }}</td>
    <td>{{ $client->is_active_text }}</td>
    <td>{{ $client->popularity_count ?? 0 }}</td>
    <td>
        <a href="javascript:void(0);" class="open-modal" title="Edit" data-route="{{ route('client.edit', $client->uuid) }}">Edit</a> |
        <a href="javascript:void(0);" class="delete-client" title="Delete" data-uuid="{{ $client->uuid }}" data-route="{{ route('client.destroy', $client->uuid) }}">Delete</a>
    </td>
</tr>