@if ($paginator->hasPages())
    <nav class="pager-nav">
        @if ($paginator->onFirstPage())
            <span>&laquo; Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}">&laquo; Anterior</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span>{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <strong>{{ $page }}</strong>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}">Próxima &raquo;</a>
        @else
            <span>Próxima &raquo;</span>
        @endif
    </nav>
@endif
