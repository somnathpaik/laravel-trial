<table class="table table-hover text-nowrap" style="display:block;">
    <thead>
        <tr>
            <th>Search Keyword</th>
            <th>Popularity Count</th>
            <th>Client</th>
            <th>Updated At</th>
        </tr>
    </thead>
    <tbody class="list" id="table-ajax" style="height:200px; overflow-y:auto; width: 100%; display:block;">
        @forelse ($search_records as $search_record)
        <tr>
            <td>{{ $search_record->keyword }}</td>
            <td>{{ $search_record->popularity_count }}</td>
            <td>{{ $search_record->client->name }}</td>
            <td>{{ $search_record->updated_at->format(config('setting.date_time_format')) }}</td>            
        </tr>
        @empty
        <tr class="no-data-table-row">
            <td colspan="100%" class="text-center">No data found</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{-- $search_records->appends($_GET)->links() --}}