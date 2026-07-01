<nav class="pagination-nav" aria-label="Pagination">
    @if($paginator->onFirstPage())
        <span class="page-btn page-disabled" aria-disabled="true">‹</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="page-btn" rel="prev" aria-label="Page précédente">‹</a>
    @endif

    @foreach($elements as $element)
        @if(is_string($element))
            <span class="page-btn page-dots">{{ $element }}</span>
        @endif

        @if(is_array($element))
            @foreach($element as $page => $url)
                @if($page == $paginator->currentPage())
                    <span class="page-btn page-active" aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="page-btn" rel="next" aria-label="Page suivante">›</a>
    @else
        <span class="page-btn page-disabled" aria-disabled="true">›</span>
    @endif
</nav>
