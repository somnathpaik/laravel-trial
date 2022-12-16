@forelse ($search_records as $search_record)
<tr>
    <td>{{ $search_record->keyword }}</td>
    <td>{{ $search_record->popularity_count }}</td>
    <td>{{ $search_record->client->name }}</td>
    <td>{{ $search_record->updated_at->format(config('setting.date_time_format')) }}</td>
</tr>
@empty

@endforelse