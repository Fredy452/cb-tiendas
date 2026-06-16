@extends('layouts.public')

@section('title', $store->name . ' | CB Tiendas')
@section('meta_description', Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($store->description ?: 'Detalle público del emprendimiento en CB Tiendas.'))), 150))
@section('canonical_url', route('tiendas.show', $store->slug ?: $store->getKey(), false))
@section('meta_image', $store->cover_url ?: $store->logo_url ?: '')
@section('meta_image_alt', 'Vista previa de ' . $store->name . ' en CB Tiendas')
@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

@section('content')
    @php
        $primaryCategory = $store->categories->first();
        $hasCoordinates = filled($store->latitude) && filled($store->longitude);
        $mapsQuery = $hasCoordinates
            ? $store->latitude . ',' . $store->longitude
            : trim(($store->address ?: $store->name) . ', Coronel Bogado, Paraguay');
        $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($mapsQuery);
        $socialLinks = collect([
            ['label' => 'Facebook', 'url' => $store->facebook_url],
            ['label' => 'Instagram', 'url' => $store->instagram_url],
            ['label' => 'TikTok', 'url' => $store->tiktok_url],
        ])->filter(fn ($link) => filled($link['url']))
            ->map(fn ($link) => [
                'label' => $link['label'],
                'url' => Illuminate\Support\Str::startsWith($link['url'], ['http://', 'https://']) ? $link['url'] : 'https://' . $link['url'],
            ]);
        $detailInitials = collect(preg_split('/\s+/', trim($store->name) ?: 'N L'))
            ->filter()
            ->take(2)
            ->map(fn ($segment) => Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($segment, 0, 1)))
            ->implode('');
    @endphp

    <section class="cb-shell cb-section pb-12">
        <div class="relative overflow-hidden rounded-4xl bg-(--cb-surface-strong) shadow-[0_28px_58px_rgba(22,26,50,0.08)]">
            <div class="h-72 sm:h-96">
                @if ($store->cover_url)
                    <img src="{{ $store->cover_url }}" alt="{{ $store->name }}" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-linear-to-t from-[rgba(22,26,50,0.5)] via-transparent to-transparent"></div>
                @else
                    <div class="h-full w-full bg-[radial-gradient(circle_at_top_left,rgba(177,240,206,0.65),transparent_38%),linear-gradient(135deg,rgba(15,82,56,0.96),rgba(45,106,79,0.85))]"></div>
                @endif
            </div>
        </div>

        <div class="relative z-10 -mt-14 grid gap-8 lg:grid-cols-[minmax(0,1.9fr)_360px] lg:items-start">
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
                                <span class="cb-pill cursor-pointer">
                                    <span class="material-symbols-outlined text-[16px]">star</span>
                                    Negocio destacado
                                </span>
                            @endif
                            <span class="cb-pill cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">visibility</span>
                                Contador de visitas: {{ $store->views_count }}
                            </span>
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
                            <p>{!! nl2br(e($store->description)) !!}</p>
                        @else
                            <p>
                                Este emprendimiento local ya forma parte de la comunidad de CB Tiendas y pronto compartirá más detalles sobre su propuesta de valor.
                            </p>
                        @endif
                    </div>
                </div>
                <div class="cb-panel mt-6 p-7 sm:p-8">
                    <h2 class="cb-subheading pb-5">Ubicación</h2>
                    <div class="flex items-start gap-3 text-(--cb-muted)">
                        <span class="material-symbols-outlined mt-0.5 text-(--cb-primary)">location_on</span>
                        <span>{{ $store->address ?: 'Coronel Bogado, Itapúa' }}</span>
                    </div>

                    <div class="mt-5 overflow-hidden rounded-3xl">
                        <div class="rounded-[1.25rem] border border-white/80 bg-white/55 backdrop-blur-sm">
                            @if ($hasCoordinates)
                                <div id="map" class="mt-4 h-48 w-full rounded-xl border border-white/50 shadow-inner z-10"></div>
                            @endif

                            <div class="flex items-center justify-between mt-4 flex-wrap gap-2">
                                <a href="{{ $mapsUrl }}" target="_blank" rel="noreferrer" class="inline-flex items-center ml-2 gap-2 text-sm font-semibold text-(--cb-secondary) transition hover:underline">
                                    Abrir en mapas
                                    <span class="material-symbols-outlined text-[18px]">north_east</span>
                                </a>

                                @if ($hasCoordinates)
                                    <p class="text-xs text-(--cb-outline)">
                                        {{ $store->latitude }}, {{ $store->longitude }}
                                    </p>
                                @endif
                            </div>
                        </div>
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
                        @if ($socialLinks->isNotEmpty())
                            <li class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-(--cb-primary)">share</span>
                                <span class="flex flex-wrap gap-2">
                                    @foreach ($socialLinks as $socialLink)
                                        <a href="{{ $socialLink['url'] }}" target="_blank" rel="noreferrer" class="font-semibold text-(--cb-secondary) transition hover:underline">
                                            {{ $socialLink['label'] }}
                                        </a>
                                    @endforeach
                                </span>
                            </li>
                        @endif
                    </ul>

                    <div class="mt-6 flex flex-col gap-3 border-t border-[rgba(222,224,255,0.85)] pt-5">
                        @if ($store->phone)
                            <a href="tel:{{ preg_replace('/\s+/', '', $store->phone) }}" class="cb-button-primary w-full rounded-2xl">Llamar ahora</a>
                        @endif

                        @if ($store->email)
                            <a href="mailto:{{ $store->email }}" class="cb-button-secondary w-full rounded-2xl">Enviar mensaje</a>
                        @endif

                        @if ($store->website)
                            <a href="{{ Illuminate\Support\Str::startsWith($store->website, ['http://', 'https://']) ? $store->website : 'https://' . $store->website }}" target="_blank" rel="noreferrer" class="cb-button-secondary w-full rounded-2xl">
                                Visitar sitio web
                            </a>
                        @endif
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
@push('scripts')
    @if ($hasCoordinates)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // 1. Obtener las coordenadas del backend de Laravel de forma segura
                const lat = {{ $store->latitude }};
                const lng = {{ $store->longitude }};
                const storeName = "{{ e($store->name ?? 'Tu Tienda') }}";

                // 2. Inicializar el mapa centrado en la localización de la tienda (Zoom 15 es ideal)
                const map = L.map('map').setView([lat, lng], 15);

                // 3. Cargar las capas de diseño (Tiles) desde OpenStreetMap de forma gratuita
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // 4. Agregar el marcador visual en el punto exacto
                const marker = L.marker([lat, lng]).addTo(map);

                // 5. Opcional: Un globo emergente (Popup) al hacer clic en el marcador
                marker.bindPopup(`<b>${storeName}</b><br>¡Te esperamos aquí!`);
            });
        </script>
    @endif
@endpush
