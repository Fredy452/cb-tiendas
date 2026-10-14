<div class="cb-search-container relative mx-auto mt-10 max-w-3xl" data-search-wrapper>
    <form action="{{ route('tiendas.index') }}" method="GET" class="cb-panel p-4 sm:p-5" data-search-form>
        @if (($selectedScope ?? 'all') !== 'all')
            <input type="hidden" name="scope" value="{{ $selectedScope }}">
        @endif
        @foreach ($selectedCategoryIds ?? [] as $categoryId)
            <input type="hidden" name="categories[]" value="{{ $categoryId }}">
        @endforeach

        <div
            class="flex flex-col gap-3 border-b border-(--cb-border) pb-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="flex items-center gap-2 text-sm font-semibold text-(--cb-text)">
                <span class="material-symbols-outlined text-[18px] text-(--cb-primary)">tune</span>
                ¿Qué buscás hoy?
            </p>

            <fieldset class="flex rounded-xl bg-(--cb-surface-soft) p-1" aria-label="Tipo de actividad">
                <legend class="sr-only">Tipo de actividad</legend>
                <label>
                    <input type="radio" name="offer_type" value="" class="peer sr-only"
                        @checked(empty($selectedOfferType)) data-search-offer-type>
                    <span
                        class="inline-flex min-h-9 cursor-pointer items-center justify-center rounded-lg px-3 text-sm font-semibold text-(--cb-muted) peer-checked:bg-(--cb-primary) peer-checked:text-white">Todos</span>
                </label>
                <label>
                    <input type="radio" name="offer_type" value="products" class="peer sr-only"
                        @checked(($selectedOfferType ?? null) === 'products') data-search-offer-type>
                    <span
                        class="inline-flex min-h-9 cursor-pointer items-center justify-center gap-1 rounded-lg px-3 text-sm font-semibold text-(--cb-muted) peer-checked:bg-white peer-checked:text-(--cb-primary) peer-checked:shadow-sm">
                        <span class="material-symbols-outlined text-[17px]">inventory_2</span>
                        Productos
                    </span>
                </label>
                <label>
                    <input type="radio" name="offer_type" value="services" class="peer sr-only"
                        @checked(($selectedOfferType ?? null) === 'services') data-search-offer-type>
                    <span
                        class="inline-flex min-h-9 cursor-pointer items-center justify-center gap-1 rounded-lg px-3 text-sm font-semibold text-(--cb-muted) peer-checked:bg-white peer-checked:text-(--cb-primary) peer-checked:shadow-sm">
                        <span class="material-symbols-outlined text-[17px]">handyman</span>
                        Servicios
                    </span>
                </label>
            </fieldset>
        </div>

        <div class="mt-4 flex flex-col gap-3 rounded-2xl bg-(--cb-surface-soft) p-2 sm:flex-row sm:items-center">
            <label for="{{ $searchId }}"
                class="flex min-w-0 flex-1 items-center gap-3 rounded-xl bg-white px-4 py-3 text-(--cb-outline)">
                <span class="material-symbols-outlined">search</span>
                <input id="{{ $searchId }}" name="search" type="search" value="{{ $searchValue }}"
                    autocomplete="off" role="combobox" aria-autocomplete="list"
                    aria-controls="{{ $searchId }}-suggestions" aria-expanded="false"
                    class="w-full min-w-0 border-none bg-transparent p-0 text-base text-(--cb-text) outline-none placeholder:text-(--cb-outline)"
                    placeholder="Buscar emprendedores, productos o servicios..." data-search-input>
            </label>

            <button type="submit" class="cb-button-primary min-h-12 px-8">
                Explorar <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </div>

        @if ($categories->isNotEmpty())
            <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                <span class="font-medium text-(--cb-muted)">Frecuentes:</span>
                @foreach ($categories->take(3) as $category)
                    <a href="{{ route('tiendas.index', array_merge($searchContext ?? [], ['category' => $category->slug ?: $category->getKey()])) }}"
                        class="cb-pill-desc hover:bg-(--cb-primary-soft) px-3 py-1.5">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </form>

    <div id="{{ $searchId }}-suggestions" class="cb-search-panel hidden" aria-label="Sugerencias de búsqueda"
        data-search-panel data-search-url="{{ route('buscar.sugerencias') }}">
        <div data-search-popular class="hidden">
            <p class="cb-search-panel-label">BÚSQUEDAS POPULARES</p>
            <ul data-popular-list></ul>
            <p class="hidden px-4 py-3 text-sm text-(--cb-outline)" data-popular-empty>No hay búsquedas recientes
                todavía.</p>
        </div>

        <div data-search-loading class="hidden px-4 py-3 text-sm text-(--cb-muted)">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined cb-spin text-[18px]">progress_activity</span>
                Buscando productos...
            </div>
        </div>

        <div data-search-empty class="hidden px-4 py-5 text-center text-sm text-(--cb-muted)">
            <span class="material-symbols-outlined mb-1 block text-3xl text-(--cb-outline)">search_off</span>
            Sin resultados para &ldquo;<span class="font-medium text-(--cb-text)" data-search-query></span>&rdquo;
        </div>

        <ul role="listbox" data-search-results class="hidden"></ul>
    </div>
</div>
