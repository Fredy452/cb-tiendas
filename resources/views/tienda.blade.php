@extends('layouts.public')

@php
    $exploreContent = match ($selectedOfferType) {
        'products' => [
            'title' => 'Explorá negocios que venden productos',
            'description' => 'Encontrá productos locales y filtrá el directorio por rubro, nombre o descripción.',
        ],
        'services' => [
            'title' => 'Explorá negocios que ofrecen servicios',
            'description' => 'Encontrá servicios locales y filtrá el directorio por rubro, nombre o descripción.',
        ],
        default => [
            'title' => 'Explorar negocios',
            'description' => 'Encontrá lo que buscás en tu ciudad y filtrá el directorio por rubro, nombre o descripción.',
        ],
    };
@endphp

@section('title', $exploreContent['title'] . ' | CB Tiendas')
@section('meta_description', $exploreContent['description'])

@section('content')
    @php
        $baseScopeQuery = request()->except('page', 'scope');
        $searchContext = request()->except('page', 'categories', 'category');
    @endphp

    <section class=" bg-[#F0EFFF]">
        <div class="cb-shell cb-section">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="cb-heading text-(--cb-primary)">{{ $exploreContent['title'] }}</h1>
                <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 font-light">
                    {{ $exploreContent['description'] }}
                </p>
            </div>

            @include('partials.public.search-drawer', [
                'searchId' => 'stores-search',
                'searchValue' => $search,
                'selectedOfferType' => $selectedOfferType,
                'categories' => $categories,
                'selectedCategoryIds' => $selectedCategoryIds,
                'selectedScope' => $selectedScope,
                'searchContext' => $searchContext,
            ])

        <div class="mt-12 grid min-w-0 gap-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">
            <aside class="min-w-0 space-y-5 lg:sticky lg:top-28">
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

                <div class="cb-panel hidden p-6 lg:block" data-mobile-categories-desktop>
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <h2 class="text-xl font-medium">Categorías</h2>
                        <a href="{{ route('tiendas.index') }}" class="text-sm font-medium text-(--cb-secondary) hover:underline">Limpiar</a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($categories as $category)
                            <label class="flex items-center gap-3 rounded-2xl px-3 py-2.5 transition {{ in_array($category->id, $selectedCategoryIds, true) ? 'bg-[#dff7ee]' : 'hover:bg-[#f8fafc]' }}">
                                <input
                                    type="checkbox"
                                    name="categories[]"
                                    value="{{ $category->id }}"
                                    @checked(in_array($category->id, $selectedCategoryIds, true))
                                    data-category-checkbox
                                    class="mt-0.5 h-5 w-5 shrink-0 rounded border-(--cb-border) bg-white accent-(--cb-primary)"
                                >
                                <span class="block font-medium text-(--cb-text)">{{ $category->name }}</span>
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
                <div x-data="infiniteStores(@js($stores->nextPageUrl()), {{ $stores->count() }})">
                    <div class="cb-panel flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-lg ">
                                Mostrando <span class="font-semibold text-(--cb-text)" x-text="loadedCount">{{ $stores->count() }}</span> de <span class="font-semibold text-(--cb-text)">{{ $stores->total() }}</span> negocios
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
                        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3" x-ref="grid" aria-live="polite">
                            @include('partials.public.store-cards', ['stores' => $stores])
                        </div>

                        <div class="mt-10 flex flex-col items-center gap-3" aria-live="polite">
                            <div x-ref="sentinel" class="h-1 w-full" aria-hidden="true"></div>
                            <p x-cloak x-show="isLoading" role="status" class="text-sm text-(--cb-muted)">Cargando más negocios...</p>
                            <p x-cloak x-show="errorMessage" x-text="errorMessage" role="alert" class="text-sm text-[#93000a]"></p>
                            <button
                                type="button"
                                x-cloak
                                x-show="(!supportsObserver && nextPageUrl) || errorMessage"
                                x-on:click="loadMore()"
                                x-bind:disabled="isLoading"
                                class="cb-button-ghost"
                            >
                                <span x-text="errorMessage ? 'Reintentar' : 'Cargar más negocios'"></span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="pointer-events-none fixed inset-0 z-40 opacity-0 transition-opacity duration-300 ease-out lg:hidden" style="overflow: hidden;" data-mobile-categories-layer>
            <button
                type="button"
                class="absolute inset-0 bg-[#111827]/55"
                aria-label="Cerrar filtros"
                data-mobile-categories-overlay
            ></button>

            <aside
                id="mobile-categories-panel"
                class="absolute top-0 right-0 translate-x-full px-5 py-5 transition-transform duration-300 ease-out"
                style="top: 0; right: 0; bottom: 0; width: min(92vw, 24rem); max-width: 100vw; overflow-y: auto; overflow-x: hidden; background: #F4F5F7; box-shadow: -12px 0 32px rgba(15, 23, 42, 0.18);"
                data-mobile-categories-panel
                role="dialog"
                aria-modal="true"
                aria-label="Filtros por categoría"
            >
                <div class="mb-5 flex items-center justify-between gap-3 border-b border-(--cb-border) pb-4">
                    <h2 class="text-3xl font-black tracking-[-0.04em] text-(--cb-text)">Categorías</h2>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center rounded-full border border-(--cb-border) bg-white p-2 text-(--cb-text) shadow-sm"
                        aria-label="Cerrar filtros"
                        data-mobile-categories-close
                    >
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <nav class="mb-5 grid grid-cols-3 gap-2" aria-label="Alcance del directorio">
                    <a href="{{ route('tiendas.index', $baseScopeQuery) }}" class="{{ $selectedScope === 'all' ? 'cb-button-primary' : 'cb-button-ghost' }} px-2! py-2! text-xs!">
                        Todos
                    </a>
                    <a href="{{ route('tiendas.index', array_merge($baseScopeQuery, ['scope' => 'featured'])) }}" class="{{ $selectedScope === 'featured' ? 'cb-button-primary' : 'cb-button-ghost' }} px-2! py-2! text-xs!">
                        Destacados
                    </a>
                    <a href="{{ route('tiendas.index', array_merge($baseScopeQuery, ['scope' => 'new'])) }}" class="{{ $selectedScope === 'new' ? 'cb-button-primary' : 'cb-button-ghost' }} px-2! py-2! text-xs!">
                        Nuevos
                    </a>
                </nav>

                <div class="space-y-3">
                    @forelse ($categories as $category)
                        <label class="flex items-center gap-3 rounded-2xl px-3 py-3 text-(--cb-text) transition {{ in_array($category->id, $selectedCategoryIds, true) ? 'bg-[#dff7ee] shadow-[inset_0_0_0_1px_rgba(26,123,89,0.08)]' : 'bg-transparent hover:bg-white/60' }}" style="min-width: 0;">
                            <input
                                type="checkbox"
                                name="categories[]"
                                value="{{ $category->id }}"
                                @checked(in_array($category->id, $selectedCategoryIds, true))
                                data-category-checkbox
                                class="mt-0.5 h-5 w-5 shrink-0 rounded border-(--cb-border) bg-white accent-(--cb-primary)"
                            >
                            <span class="block text-xl font-medium leading-6" style="min-width: 0; overflow-wrap: anywhere;">{{ $category->name }}</span>
                        </label>
                    @empty
                        <p class="text-sm leading-7 text-(--cb-muted)">Aún no hay categorías activas para filtrar.</p>
                    @endforelse
                </div>

                <div class="mt-6 border-t border-(--cb-border) pt-4">
                    <a href="{{ route('tiendas.index') }}" class="inline-flex items-center text-base font-semibold text-(--cb-secondary) underline-offset-4 hover:underline">Limpiar filtros</a>
                </div>
            </aside>
        </div>
        </div>
    </section>
@endsection
