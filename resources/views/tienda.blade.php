@extends('layouts.public')

@section('title', 'Explorar negocios | CB Tiendas')
@section('meta_description', 'Directorio público de negocios aprobados en Coronel Bogado con búsqueda y filtros por categoría.')

@section('content')
    @php
        $baseScopeQuery = request()->except('page', 'scope');
    @endphp

    <section class=" bg-[#F0EFFF]">
        <div class="cb-shell cb-section">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="cb-heading text-(--cb-primary)">Explorar negocios</h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 font-light">
                    Encontrá lo que buscás en tu ciudad y filtrá el directorio por rubro, nombre o descripción.
                </p>
            </div>

            <div class="cb-search-container relative mx-auto mt-10 max-w-3xl" data-search-wrapper>
            <form action="{{ route('tiendas.index') }}" method="GET" data-search-form>
                @if ($selectedScope !== 'all')
                    <input type="hidden" name="scope" value="{{ $selectedScope }}">
                @endif

                <div class="cb-panel flex flex-col gap-3 p-3 sm:flex-row sm:items-center" data-search-parent>
                    <label for="stores-search" class="flex flex-1 items-center gap-3 rounded-2xl bg-(--cb-surface-panel) px-4 py-2 text-(--cb-outline)">
                        <span class="material-symbols-outlined">search</span>
                        <input
                            id="stores-search"
                            name="search"
                            type="search"
                            value="{{ $search }}"
                            autocomplete="off"
                            class="w-full border-none bg-transparent p-0 text-base text-(--cb-text) outline-none placeholder:text-(--cb-outline)"
                            placeholder="Buscar emprendedores, servicios o productos..."
                            data-search-input
                        >
                    </label>

                    <button type="submit" class="cb-button-primary rounded-2xl px-8">Buscar</button>
                </div>

                {{-- Panel de autocompletar --}}
                <div class="cb-search-panel hidden" role="listbox" aria-label="Sugerencias de búsqueda"
                     data-search-panel
                     data-search-url="{{ route('buscar.sugerencias') }}">

                    {{-- Búsquedas populares / recientes --}}
                    <div data-search-popular class="hidden">
                        <p class="cb-search-panel-label">BÚSQUEDAS POPULARES</p>
                        <ul data-popular-list></ul>
                        <p class="hidden px-4 py-3 text-sm text-(--cb-outline)" data-popular-empty>No hay búsquedas recientes todavía.</p>
                    </div>

                    {{-- Estado cargando --}}
                    <div data-search-loading class="hidden px-4 py-3 text-sm text-(--cb-muted)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined cb-spin text-[18px]">progress_activity</span>
                            Buscando productos...
                        </div>
                    </div>

                    {{-- Sin resultados --}}
                    <div data-search-empty class="hidden px-4 py-5 text-center text-sm text-(--cb-muted)">
                        <span class="material-symbols-outlined mb-1 block text-3xl text-(--cb-outline)">search_off</span>
                        Sin resultados para &ldquo;<span class="font-medium text-(--cb-text)" data-search-query></span>&rdquo;
                    </div>

                    {{-- Lista de resultados --}}
                    <ul data-search-results class="hidden"></ul>
                </div>
            </div>

                <div class="mt-12 grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">
                    <aside class="space-y-5 lg:sticky lg:top-28">
                        <button
                            type="button"
                            class="cb-button-ghost w-full justify-between rounded-2xl lg:hidden"
                            data-mobile-categories-toggle
                            aria-expanded="false"
                            aria-controls="mobile-categories-panel"
                        >
                            <span>Categorías</span>
                            <span class="material-symbols-outlined" data-mobile-categories-icon>expand_more</span>
                        </button>

                        <div class="fixed inset-0 z-40 hidden lg:hidden" data-mobile-categories-layer>
                            <button
                                type="button"
                                class="absolute inset-0 bg-[#111827]/55"
                                aria-label="Cerrar filtros"
                                data-mobile-categories-overlay
                            ></button>

                            <div
                                id="mobile-categories-panel"
                                class="absolute inset-y-0 right-0 w-[min(92vw,28rem)] translate-x-full overflow-y-auto bg-white px-5 py-5 shadow-[-12px_0_32px_rgba(15,23,42,0.18)] transition-transform duration-300 ease-out"
                                data-mobile-categories-panel
                                role="dialog"
                                aria-modal="true"
                                aria-label="Filtros por categoría"
                            >
                                <div class="mb-5 flex items-center justify-between gap-3 border-b border-(--cb-border) pb-4">
                                    <h2 class="text-2xl font-bold text-(--cb-text)">Categorías</h2>
                                    <button
                                        type="button"
                                        class="inline-flex items-center justify-center rounded-full border border-(--cb-border) p-2 text-(--cb-text)"
                                        aria-label="Cerrar filtros"
                                        data-mobile-categories-close
                                    >
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>

                                <div class="space-y-3">
                                    @forelse ($categories as $category)
                                        <label class="flex items-start gap-3 rounded-2xl px-2 py-2.5 {{ in_array($category->id, $selectedCategoryIds, true) ? 'bg-[rgba(177,240,206,0.28)]' : '' }}">
                                            <input
                                                type="checkbox"
                                                name="categories[]"
                                                value="{{ $category->id }}"
                                                @checked(in_array($category->id, $selectedCategoryIds, true))
                                                data-category-checkbox
                                                class="mt-1 h-5 w-5 rounded border-(--cb-border) text-(--cb-primary) focus:ring-(--cb-primary)"
                                            >
                                            <span class="block text-lg font-medium text-(--cb-text)">{{ $category->name }}</span>
                                        </label>
                                    @empty
                                        <p class="text-sm leading-7 text-(--cb-muted)">Aún no hay categorías activas para filtrar.</p>
                                    @endforelse
                                </div>

                                <div class="mt-6 border-t border-(--cb-border) pt-4">
                                    <a href="{{ route('tiendas.index') }}" class="inline-flex text-base font-medium text-(--cb-secondary) hover:underline">Limpiar filtros</a>
                                </div>
                            </div>
                        </div>

                        <div class="cb-panel hidden p-6 lg:block" data-mobile-categories-desktop>
                            <div class="mb-5 flex items-center justify-between gap-4">
                                <h2 class="text-xl font-medium">Categorías</h2>
                                <a href="{{ route('tiendas.index') }}" class="text-sm font-medium text-(--cb-secondary) hover:underline">Limpiar</a>
                            </div>

                            <div class="space-y-3">
                                @forelse ($categories as $category)
                                    <label class="flex items-start gap-3 px-3 py-1">
                                        <input
                                            type="checkbox"
                                            name="categories[]"
                                            value="{{ $category->id }}"
                                            @checked(in_array($category->id, $selectedCategoryIds, true))
                                            data-category-checkbox
                                            class="mt-1 h-5 w-5 rounded border-(--cb-border) text-(--cb-primary) focus:ring-(--cb-primary)"
                                        >
                                        <span>
                                            <span class="block font-medium">{{ $category->name }}</span>
                                            {{-- <span class="text-sm text-(--cb-muted)">
                                                {{ $category->public_stores_count }} {{ Illuminate\Support\Str::plural('negocio', $category->public_stores_count) }}
                                            </span> --}}
                                        </span>
                                    </label>
                                @empty
                                    <p class="text-sm leading-7 text-(--cb-muted)">Aún no hay categorías activas para filtrar.</p>
                                @endforelse
                            </div>

                        </div>

                        <div class="hidden md:flex flex-wrap gap-3 lg:flex ">
                            <a href="{{ route('tiendas.index', $baseScopeQuery) }}" class="{{ $selectedScope === 'all' ? 'cb-button-primary' : 'cb-button-ghost' }} px-5! py-2!">
                                Todos
                            </a>
                            <a href="{{ route('tiendas.index', array_merge($baseScopeQuery, ['scope' => 'featured'])) }}" class="{{ $selectedScope === 'featured' ? 'cb-button-primary' : 'cb-button-ghost' }} px-5! py-2!">
                                Destacados
                            </a>
                            <a href="{{ route('tiendas.index', array_merge($baseScopeQuery, ['scope' => 'new'])) }}" class="{{ $selectedScope === 'new' ? 'cb-button-primary' : 'cb-button-ghost' }} px-5! py-2!">
                                Nuevos
                            </a>
                        </div>
                    </aside>

                    <div>
                        <div class="cb-panel flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-lg ">
                                    Mostrando <span class="font-semibold text-(--cb-text)">{{ $stores->firstItem() ?? 0 }}</span> a <span class="font-semibold text-(--cb-text)">{{ $stores->lastItem() ?? 0 }}</span> de <span class="font-semibold text-(--cb-text)">{{ $stores->total() }}</span> negocios
                                </p>
                            </div>

                            @if ($search !== '')
                                <span class="cb-pill">Búsqueda: {{ $search }}</span>
                            @endif
                        </div>

                        @if ($stores->isEmpty())
                            <div class="mt-8">
                                @include('partials.public.empty-state', [
                                    'title' => 'No encontramos negocios con esos filtros',
                                    'description' => 'Probá con otra búsqueda, quitá algún filtro o registrá el primer emprendimiento de ese rubro.',
                                    'actionLabel' => 'Registrar emprendimiento',
                                    'actionUrl' => route('emprendimientos.create'),
                                ])
                            </div>
                        @else
                            <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($stores as $store)
                                    @include('partials.public.store-card', ['store' => $store])
                                @endforeach
                            </div>

                            <div class="mt-10">
                                {{ $stores->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
