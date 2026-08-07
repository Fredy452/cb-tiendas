@extends('layouts.public')

@section('title', 'CB Tiendas | Directorio oficial de emprendedores')
@section('meta_description', 'La plaza digital de Coronel Bogado para descubrir, apoyar y registrar emprendimientos locales.')

@section('content')
    <section class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-150 bg-[#F0EFFF]"></div>

        <div class="cb-shell cb-section relative py-10 md:p-28">
            <div class="mx-auto max-w-4xl text-center">
                <h1 class="cb-display mx-auto max-w-4xl">
                    Descubre y apoya lo nuestro.
                </h1>
                <p class="mx-auto mt-6 max-w-2xl font-medium text-lg leading-8 text-(--cb-muted) sm:text-xl">
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

                    <button type="submit" class="cb-button-primary px-8">Explorar</button>
                </form>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('tiendas.index') }}" class="cb-button-ghost text-[1.2rem]">Explorar negocios</a>
                    <a href="{{ route('emprendimientos.create') }}" class="cb-button-secondary text-[1.2rem]">Registrar mi emprendimiento</a>
                </div>
            </div>
        </div>
    </section>

    <section class="cb-shell cb-section" id="destacados" data-featured-section>
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="cb-subheading text-3xl font-medium">Negocios destacados</h2>
                <p class="mt-2 text-xl font-light">Conoce algunos emprendimientos aprobados que ya forman parte de la comunidad.</p>
            </div>

            <div class="flex items-center gap-3">
                <button
                    type="button"
                    data-featured-prev
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[rgba(22,26,50,0.08)] bg-white text-(--cb-text) shadow-sm transition hover:-translate-y-0.5 hover:shadow"
                    aria-label="Anterior"
                >
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </button>
                <button
                    type="button"
                    data-featured-next
                    class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[rgba(22,26,50,0.08)] bg-white text-(--cb-text) shadow-sm transition hover:-translate-y-0.5 hover:shadow"
                    aria-label="Siguiente"
                >
                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                </button>
            </div>
        </div>

        @if ($featuredStores->isEmpty())
            <div class="cb-panel mt-8 p-8 text-center">
                <h3 class="text-xl font-semibold text-(--cb-text)">Todavía no hay negocios destacados</h3>
                <p class="mt-3 text-(--cb-muted)">Cuando se aprueben nuevos emprendimientos, aparecerán aquí con prioridad.</p>
                <a href="{{ route('emprendimientos.create') }}" class="cb-button-secondary mt-6">Registrar emprendimiento</a>
            </div>
        @else
            <div class="cb-featured-carousel mt-8" data-featured-carousel>
                <div class="cb-featured-viewport" data-featured-viewport>
                    <div class="cb-featured-track" data-featured-track>
                    @foreach ($featuredStores as $store)
                        @php
                            $primaryCategory = $store->categories->first();
                            $descriptionExcerpt = $store->description
                                ? Illuminate\Support\Str::limit(
                                    trim(html_entity_decode(strip_tags($store->description), ENT_QUOTES, 'UTF-8')),
                                    125,
                                )
                                : 'Emprendimiento local registrado dentro del directorio de Coronel Bogado.';
                            $detailRouteKey = $store->slug ?: $store->getKey();
                        @endphp

                        <article class="cb-featured-slide" data-featured-slide>
                            <a href="{{ route('tiendas.show', $detailRouteKey) }}" class="group cb-featured-card">
                                <div class="cb-featured-media">
                                    @if ($store->cover_url)
                                        <img
                                            src="{{ $store->cover_url }}"
                                            alt="{{ $store->name }}"
                                            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                        >
                                        <div class="absolute inset-0 bg-linear-to-t from-[rgba(22,26,50,0.3)] to-transparent"></div>
                                    @else
                                        <div class="absolute inset-0"></div>
                                    @endif

                                    <span class="cb-featured-category">
                                        {{ $primaryCategory?->name ?: 'General' }}
                                    </span>
                                </div>

                                <div class="cb-featured-body">
                                    <h3 class="text-[2rem] font-semibold leading-tight tracking-tight text-(--cb-text)">{{ $store->name }}</h3>
                                    <p class="mt-3 text-lg leading-8 text-(--cb-muted) line-clamp-2">
                                        {{ $descriptionExcerpt }}
                                    </p>

                                    <span class="mt-7 flex justify-end items-center gap-2 text-[1.15rem] font-semibold text-[#0d6d84]">
                                        <span class="material-symbols-outlined text-[22px] text-[#b88a15]">visibility</span>
                                        {{ $store->views_count }} visitas
                                    </span>
                                </div>
                            </a>
                        </article>
                    @endforeach
                    </div>
                </div>
            </div>
        @endif
    </section>

    <section class="cb-section bg-[rgba(244,242,255,0.8)]">
        <div class="cb-shell">
            <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="cb-subheading text-3xl font-medium">Enlaces rápidos</h2>
                    <p class="mt-2 text-xl font-light">Un acceso directo a las secciones públicas más importantes de CB Tiendas.</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <a href="{{ route('sobre-nosotros') }}" class="cb-card flex h-full flex-col p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(177,240,206,0.55)]">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-medium tracking-tight">Sobre nosotros</h3>
                    <p class="mt-3 flex-1 leading-7 text-xl font-light">
                        Nuestra misión es fortalecer la comunidad apoyando el talento y esfuerzo de los emprendedores locales.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 text-[1.125rem] font-medium text-(--cb-primary-strong)">
                        Conocer más <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>

                <a href="{{ route('categorias') }}" class="cb-card flex h-full flex-col p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(212,227,255,0.7)] text-(--cb-secondary)">
                        <span class="material-symbols-outlined">category</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-medium tracking-tight">Categorías</h3>
                    <p class="mt-3 flex-1 leading-7 text-xl font-light">
                        Explora negocios por sector y descubre {{ $categories->count() }} rubros visibles dentro del directorio local.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 text-[1.125rem] font-medium text-(--cb-primary-strong)">
                        Explorar sectores <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>

                <a href="{{ route('tiendas.index') }}" class="cb-card flex h-full flex-col p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(222,224,255,0.85)] text-(--cb-text)">
                        <span class="material-symbols-outlined">storefront</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-medium tracking-tight">Negocios</h3>
                    <p class="mt-3 flex-1 leading-7 text-xl font-light">
                        Accede al directorio completo de emprendedores aprobados y ubica fácilmente productos o servicios en la ciudad.
                    </p>
                    <span class="mt-6 inline-flex items-center gap-2 text-[1.125rem] font-medium text-(--cb-primary-strong)">
                        Ver directorio <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </span>
                </a>
            </div>
        </div>
    </section>
@endsection
