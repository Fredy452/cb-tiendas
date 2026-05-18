@extends('layouts.public')

@section('title', 'CB Tiendas | Directorio oficial de emprendedores')
@section('meta_description', 'La plaza digital de Coronel Bogado para descubrir, apoyar y registrar emprendimientos locales.')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-120" style="background: radial-gradient(circle at top right, rgba(177, 240, 206, 0.5), transparent 42%), radial-gradient(circle at left, rgba(212, 227, 255, 0.45), transparent 38%);"></div>

        <div class="cb-shell cb-section relative">
            <div class="mx-auto max-w-4xl text-center">
                <p class="cb-kicker mb-4">La plaza digital de Coronel Bogado</p>
                <h1 class="cb-display mx-auto max-w-4xl">
                    Descubre y apoya <span class="text-(--cb-primary)">lo nuestro</span>.
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg leading-8 text-(--cb-muted) sm:text-xl">
                    El directorio oficial de emprendedores de Coronel Bogado. Conectando talento local con nuestra comunidad.
                </p>

                <form action="{{ route('tiendas.index') }}" method="GET" class="cb-panel mx-auto mt-10 flex max-w-3xl flex-col gap-3 p-3 sm:flex-row sm:items-center">
                    <label for="home-search" class="flex flex-1 items-center gap-3 rounded-2xl bg-(--cb-surface-panel) px-4 py-2 text-(--cb-outline)">
                        <span class="material-symbols-outlined">search</span>
                        <input
                            id="home-search"
                            name="search"
                            type="search"
                            class="w-full border-none bg-transparent p-0 text-base text-(--cb-text) outline-none placeholder:text-(--cb-outline)"
                            placeholder="Buscar emprendedores, servicios o productos..."
                        >
                    </label>

                    <button type="submit" class="cb-button-primary rounded-2xl px-8">Explorar</button>
                </form>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('tiendas.index') }}" class="cb-button-ghost">Explorar negocios</a>
                    <a href="{{ route('emprendimientos.create') }}" class="cb-button-secondary">Registrar mi emprendimiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cb-shell cb-section pt-0" id="destacados">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="cb-subheading">Negocios destacados</h2>
                <p class="mt-2 text-(--cb-muted)">Conoce algunos emprendimientos aprobados que ya forman parte de la comunidad.</p>
            </div>

            <a href="{{ route('tiendas.index', ['scope' => 'featured']) }}" class="text-sm font-semibold text-(--cb-primary) transition hover:underline">
                Ver directorio completo
            </a>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            @forelse ($featuredStores as $store)
                @include('partials.public.store-card', ['store' => $store])
            @empty
                @include('partials.public.empty-state', [
                    'title' => 'Todavía no hay negocios destacados',
                    'description' => 'Cuando se aprueben nuevos emprendimientos, aparecerán aquí con prioridad.',
                    'actionLabel' => 'Registrar emprendimiento',
                    'actionUrl' => route('emprendimientos.create'),
                ])
            @endforelse
        </div>
    </section>

    <section class="cb-section bg-[rgba(244,242,255,0.8)]">
        <div class="cb-shell">
            <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="cb-subheading">Enlaces rápidos</h2>
                    <p class="mt-2 text-(--cb-muted)">Un acceso directo a las secciones públicas más importantes de CB Tiendas.</p>
                </div>

                <span class="text-sm font-medium text-(--cb-outline)">{{ $storesCount }} negocios visibles actualmente</span>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <a href="{{ route('sobre-nosotros') }}" class="cb-card flex h-full flex-col p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(177,240,206,0.55)] text-(--cb-primary)">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold tracking-tight">Sobre nosotros</h3>
                    <p class="mt-3 flex-1 leading-7 text-(--cb-muted)">
                        Nuestra misión es fortalecer la comunidad apoyando el talento y esfuerzo de los emprendedores locales.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-(--cb-primary)">
                        Conocer más <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>

                <a href="{{ route('categorias') }}" class="cb-card flex h-full flex-col p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(212,227,255,0.7)] text-(--cb-secondary)">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold tracking-tight">Categorías</h3>
                    <p class="mt-3 flex-1 leading-7 text-(--cb-muted)">
                        Explora negocios por sector y descubre {{ $categories->count() }} rubros visibles dentro del directorio local.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-(--cb-secondary)">
                        Explorar sectores <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>

                <a href="{{ route('tiendas.index') }}" class="cb-card flex h-full flex-col p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(222,224,255,0.85)] text-(--cb-text)">
                        <span class="material-symbols-outlined">storefront</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold tracking-tight">Negocios</h3>
                    <p class="mt-3 flex-1 leading-7 text-(--cb-muted)">
                        Accede al directorio completo de emprendedores aprobados y ubica fácilmente productos o servicios en la ciudad.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-(--cb-text)">
                        Ver directorio <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>
            </div>
        </div>
    </section>
@endsection
