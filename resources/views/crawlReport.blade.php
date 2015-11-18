<h2>Found broken urls</h2>
@foreach($urlsGroupedByStatusCode as $statusCode => $urls)
    @if (! starts_with($statusCode, [2,3]))
        Crawled {{ count($urls) }} with status code {{ $statusCode }}<br />
    @endif
@endforeach
