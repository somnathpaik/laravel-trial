<ul class="recent-search">
    @forelse ($search_records as $search_record)
        <li>
            <a href="javascript:void(0);" class="recent-search-data" data-value="{{ $search_record }}">{{ $search_record }}</a>
        </li>
    @empty
            
    @endforelse
</ul>