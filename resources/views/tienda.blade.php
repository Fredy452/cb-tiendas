@extends('layouts.public')

@section('title', 'Explorar negocios | CB Tiendas')
@section('meta_description', 'Directorio público de negocios aprobados en Coronel Bogado con búsqueda y filtros por categoría.')

@section('content')
    @php
        $baseScopeQuery = request()->except('page', 'scope');
    @endphp

    <section class="cb-shell cb-section">
        <div class="mx-auto max-w-4xl text-center">
            <p class="cb-kicker mb-4">Directorio público</p>
            <h1 class="cb-heading text-(--cb-primary)">Explorar negocios</h1>
            <p class="mx-auto mt-4 max-w-2xl text-lg leading-8 text-(--cb-muted)">
                Encontrá lo que buscás en tu ciudad y filtrá el directorio por rubro, nombre o descripción.
            </p>
        </div>

        <form action="{{ route('tiendas.index') }}" method="GET" class="mt-10">
            @if ($selectedScope !== 'all')
                <input type="hidden" name="scope" value="{{ $selectedScope }}">
            @endif

            <div class="cb-panel mx-auto flex max-w-3xl flex-col gap-3 p-3 sm:flex-row sm:items-center">
                <label for="stores-search" class="flex flex-1 items-center gap-3 rounded-2xl bg-(--cb-surface-panel) px-4 py-2 text-(--cb-outline)">
                    <span class="material-symbols-outlined">search</span>
                    <input
                        id="stores-search"
                        name="search"
                        type="search"
                        value="{{ $search }}"
                        class="w-full border-none bg-transparent p-0 text-base text-(--cb-text) outline-none placeholder:text-(--cb-outline)"
                        placeholder="Buscar emprendedores, servicios o productos..."
                    >
                </label>

                <button type="submit" class="cb-button-primary rounded-2xl px-8">Buscar</button>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">
                <aside class="space-y-5 lg:sticky lg:top-28">
                    <div class="cb-panel p-6">
                        <div class="mb-5 flex items-center justify-between gap-4">
                            <h2 class="text-sm font-semibold uppercase tracking-[0.22em] text-(--cb-outline)">Categorías</h2>
                            <a href="{{ route('tiendas.index') }}" class="text-sm font-medium text-(--cb-secondary) hover:underline">Limpiar</a>
                        </div>

                        <div class="space-y-3">
                            @forelse ($categories as $category)
                                <label class="flex cursor-pointer items-start gap-3 rounded-2xl px-3 py-2 transition hover:bg-[rgba(244,242,255,0.8)]">
                                    <input
                                        type="checkbox"
                                        name="categories[]"
                                        value="{{ $category->id }}"
                                        @checked(in_array($category->id, $selectedCategoryIds, true))
                                        class="mt-1 h-5 w-5 rounded border-(--cb-border) text-(--cb-primary) focus:ring-(--cb-primary)"
                                    >
                                    <span>
                                        <span class="block font-medium text-(--cb-text)">{{ $category->name }}</span>
                                        <span class="text-sm text-(--cb-muted)">
                                            {{ $category->public_stores_count }} {{ Illuminate\Support\Str::plural('negocio', $category->public_stores_count) }}
                                        </span>
                                    </span>
                                </label>
                            @empty
                                <p class="text-sm leading-7 text-(--cb-muted)">Aún no hay categorías activas para filtrar.</p>
                            @endforelse
                        </div>

                        <button type="submit" class="cb-button-primary mt-6 w-full rounded-2xl">Aplicar filtros</button>
                    </div>

                    <div class="flex flex-wrap gap-3">
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
                            <p class="text-lg font-semibold text-(--cb-text)">
                                Mostrando {{ $stores->firstItem() ?? 0 }} a {{ $stores->lastItem() ?? 0 }} de {{ $stores->total() }} negocios
                            </p>
                            <p class="mt-1 text-sm text-(--cb-muted)">
                                Resultados públicos aprobados dentro del directorio de Coronel Bogado.
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
    </section>
@endsection
