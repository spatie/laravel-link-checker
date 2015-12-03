<h1>Found broken links</h1>

@foreach($urlsGroupedByStatusCode as $statusCode => $urls)

    @if ($statusCode >= 400))
    
        Crawled {{ count($urls) }} link(s) with status code {{ $statusCode }}:
        
        <ul>
            @foreach($urls as $url)
                <li>{!! link_to($url) !!} </li>
            @endforeach
        </ul>
    
    @endif
    
@endforeach
