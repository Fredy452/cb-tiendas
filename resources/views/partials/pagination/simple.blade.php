@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginación" class="cb-pagination-wrap">
        <div class="cb-pagination" role="list">
            @if ($paginator->onFirstPage())
                <span class="cb-pagination-nav is-disabled" aria-disabled="true" aria-label="Anterior">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </span>
            @else
                <a class="cb-pagination-nav" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Anterior">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </a>
            @endif

            <span class="cb-pagination-page is-active">{{ $paginator->currentPage() }}</span>

            @if ($paginator->hasMorePages())
                <a class="cb-pagination-nav" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Siguiente">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </a>
            @else
                <span class="cb-pagination-nav is-disabled" aria-disabled="true" aria-label="Siguiente">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>
@endif
