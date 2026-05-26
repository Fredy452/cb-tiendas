@extends('layouts.public')

@section('title', $store->name . ' | CB Tiendas')
@section('meta_description', Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($store->description ?: 'Detalle público del emprendimiento en CB Tiendas.'))), 150))
@section('canonical_url', route('tiendas.show', $store->slug ?: $store->getKey(), false))
@section('meta_image', $store->cover_url ?: $store->logo_url ?: '')
@section('meta_image_alt', 'Vista previa de ' . $store->name . ' en CB Tiendas')

@section('content')
    @php
        $primaryCategory = $store->categories->first();
        $mapsQuery = trim(($store->address ?: $store->name) . ', Coronel Bogado, Paraguay');
        $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($mapsQuery);
        $detailInitials = collect(preg_split('/\s+/', trim($store->name) ?: 'N L'))
            ->filter()
            ->take(2)
            ->map(fn ($segment) => Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($segment, 0, 1)))
            ->implode('');
    @endphp

    <section class="cb-shell cb-section pb-12">
        <div class="relative overflow-hidden rounded-[2rem] bg-(--cb-surface-strong) shadow-[0_28px_58px_rgba(22,26,50,0.08)]">
            <div class="h-[18rem] sm:h-[24rem]">
                @if ($store->cover_url)
                    <img src="{{ $store->cover_url }}" alt="{{ $store->name }}" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(22,26,50,0.5)] via-transparent to-transparent"></div>
                @else
                    <div class="h-full w-full bg-[radial-gradient(circle_at_top_left,_rgba(177,240,206,0.65),_transparent_38%),linear-gradient(135deg,_rgba(15,82,56,0.96),_rgba(45,106,79,0.85))]"></div>
                @endif
            </div>
        </div>

        <div class="relative z-10 mt-[-3.5rem] grid gap-8 lg:grid-cols-[minmax(0,1.9fr)_360px] lg:items-start">
            <div>
                <div class="flex flex-col gap-6 rounded-[1.8rem] bg-white/95 p-6 shadow-[0_22px_48px_rgba(22,26,50,0.08)] backdrop-blur sm:flex-row sm:items-end">
                    <div class="shrink-0">
                        @if ($store->logo_url)
                            <div class="h-24 w-24 overflow-hidden rounded-full border-4 border-white bg-white shadow-lg sm:h-28 sm:w-28">
                                <img src="{{ $store->logo_url }}" alt="Logo de {{ $store->name }}" class="h-full w-full object-cover">
                            </div>
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white bg-(--cb-secondary-soft) text-2xl font-bold text-(--cb-secondary) shadow-lg sm:h-28 sm:w-28">
                                {{ $detailInitials }}
                            </div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <h1 class="cb-heading text-[2.6rem] leading-none">{{ $store->name }}</h1>

                            @if ($store->is_featured)
                                <span class="cb-pill">
                                    <span class="material-symbols-outlined text-[16px]">star</span>
                                    Negocio destacado
                                </span>
                            @endif
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @forelse ($store->categories as $category)
                                <span class="rounded-full bg-[rgba(222,224,255,0.85)] px-3 py-1 text-xs font-semibold text-(--cb-text)">
                                    {{ $category->name }}
                                </span>
                            @empty
                                <span class="rounded-full bg-[rgba(222,224,255,0.85)] px-3 py-1 text-xs font-semibold text-(--cb-text)">
                                    Comunidad local
                                </span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="cb-panel mt-6 p-7 sm:p-8">
                    <h2 class="cb-subheading">Nuestra historia</h2>
                    <div class="cb-prose mt-5 text-lg">
                        @if ($store->description)
                            {!! $store->description !!}
                        @else
                            <p>
                                Este emprendimiento local ya forma parte de la comunidad de CB Tiendas y pronto compartirá más detalles sobre su propuesta de valor.
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="cb-panel p-6">
                    <h2 class="cb-subheading">Contacto</h2>

                    <ul class="mt-5 space-y-4 text-(--cb-muted)">
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-(--cb-primary)">call</span>
                            <span>{{ $store->phone ?: 'Dato no disponible' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-(--cb-primary)">mail</span>
                            <span>{{ $store->email ?: 'Sin correo publicado' }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-(--cb-primary)">language</span>
                            <span>{{ $store->website ?: 'Sin sitio web publicado' }}</span>
                        </li>
                    </ul>

                    <div class="mt-6 flex flex-col gap-3 border-t border-[rgba(222,224,255,0.85)] pt-5">
                        @if ($store->phone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $store->phone) }}" class="cb-button-primary w-full rounded-2xl">Llamar ahora</a>
                        @endif

                        @if ($store->email)
                            <a href="mailto:{{ $store->email }}" class="cb-button-secondary w-full rounded-2xl">Enviar mensaje</a>
                        @elseif ($store->website)
                            <a href="{{ Illuminate\Support\Str::startsWith($store->website, ['http://', 'https://']) ? $store->website : 'https://' . $store->website }}" target="_blank" rel="noreferrer" class="cb-button-secondary w-full rounded-2xl">
                                Visitar sitio web
                            </a>
                        @endif
                    </div>
                </div>

                <div class="cb-panel overflow-hidden p-6">
                    <div class="flex items-start gap-3 text-(--cb-muted)">
                        <span class="material-symbols-outlined mt-0.5 text-(--cb-primary)">location_on</span>
                        <span>{{ $store->address ?: 'Coronel Bogado, Itapúa' }}</span>
                    </div>

                    <div class="mt-5 overflow-hidden rounded-[1.5rem] bg-[radial-gradient(circle_at_top,_rgba(212,227,255,0.9),_transparent_42%),linear-gradient(135deg,_rgba(222,224,255,0.95),_rgba(244,242,255,0.92))] p-6">
                        <div class="rounded-[1.25rem] border border-white/80 bg-white/55 p-5 backdrop-blur-sm">
                            <p class="text-sm leading-7 text-(--cb-muted)">
                                Ubicación referencial del negocio dentro del directorio público.
                            </p>

                            <a href="{{ $mapsUrl }}" target="_blank" rel="noreferrer" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-(--cb-secondary) transition hover:underline">
                                Abrir en mapas
                                <span class="material-symbols-outlined text-[18px]">north_east</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cb-shell cb-section pt-8">
        <div class="border-t border-[rgba(222,224,255,0.9)] pt-12">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="cb-subheading">
                        Otros negocios{{ $primaryCategory ? ' de ' . $primaryCategory->name : ' de la comunidad' }}
                    </h2>
                    <p class="mt-2 text-(--cb-muted)">Más emprendimientos públicos para seguir recorriendo el catálogo local.</p>
                </div>

                <a href="{{ route('tiendas.index', $primaryCategory ? ['category' => $primaryCategory->slug ?: $primaryCategory->id] : []) }}" class="text-sm font-semibold text-(--cb-primary) transition hover:underline">
                    Ver más negocios
                </a>
            </div>

            @if ($relatedStores->isEmpty())
                <div class="mt-8">
                    @include('partials.public.empty-state', [
                        'title' => 'No hay negocios relacionados por ahora',
                        'description' => 'Cuando se aprueben más emprendimientos del mismo rubro, aparecerán sugerencias en esta sección.',
                        'actionLabel' => 'Explorar directorio',
                        'actionUrl' => route('tiendas.index'),
                    ])
                </div>
            @else
                <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($relatedStores as $relatedStore)
                        @include('partials.public.store-card', ['store' => $relatedStore])
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
