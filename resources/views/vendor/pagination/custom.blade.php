@if ($paginator->hasPages())
    <div class="pagination" style="font-family: Arial, sans-serif; font-size: 16px;">
        
        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->url(1) }}" style="margin-right: 10px;">&laquo; Перша</a>
        @endif

        @if (!$paginator->onFirstPage())
            <a href="{{ $paginator->previousPageUrl() }}" style="margin-right: 10px;">&lt; Попередня</a>
        @endif

        @php
        $from = max(1, $paginator->currentPage() - 2);
        $to   = min($paginator->lastPage(), $paginator->currentPage() + 2);
    @endphp
    
    @for ($page = $from; $page <= $to; $page++)
        @if ($page == $paginator->currentPage())
            <span class="active" style="margin-right: 10px; font-size:14px;">
                {{ $page }}
            </span>
        @else
            <a href="{{ $paginator->url($page) }}" style="margin-right: 10px;">
                {{ $page }}
            </a>
        @endif
    @endfor

        {{-- Кнопка "Наступна" --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="margin-right: 10px;">Наступна &gt;</a>
        @endif

        {{-- Кнопка "Остання" --}}
        @if ($paginator->currentPage() != $paginator->lastPage())
            <a href="{{ $paginator->url($paginator->lastPage()) }}">Остання &raquo;</a>
        @endif

    </div>
@endif
