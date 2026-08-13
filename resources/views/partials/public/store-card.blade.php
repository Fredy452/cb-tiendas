@php
    $categoryBadges = $store->categories->take(2);
    $initials = collect(preg_split('/\s+/', trim($store->name) ?: 'N L'))
        ->filter()
        ->take(2)
        ->map(fn ($segment) => Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($segment, 0, 1)))
        ->implode('');

    $descriptionExcerpt = $store->description
        ? Illuminate\Support\Str::limit(
            trim(html_entity_decode(strip_tags($store->description), ENT_QUOTES, 'UTF-8')),
            50,
        )
        : 'Emprendimiento local registrado dentro del directorio de Coronel Bogado.';

    $addressExcerpt = $store->address
        ? Illuminate\Support\Str::limit(
            trim(html_entity_decode(strip_tags($store->address), ENT_QUOTES, 'UTF-8')),
            20,
        )
        : 'Coronel Bogado, Itapúa';

    $nameExcerpt = $store->name
        ? Illuminate\Support\Str::limit(
            trim(html_entity_decode(strip_tags($store->name), ENT_QUOTES, 'UTF-8')),
            15,
        )
        : 'Negocio sin nombre';

    $detailRouteKey = $store->slug ?: $store->getKey();
@endphp

<article class="cb-card flex h-full flex-col">
    <a href="{{ route('tiendas.show', $detailRouteKey) }}">
        {{-- Wrapper relativo: permite que el logo sobresalga hacia abajo --}}
        <div class="relative">

            {{-- Imagen con overflow-hidden propio para recortar solo la foto --}}
            <div class="h-56 overflow-hidden rounded-t-[inherit] bg-[radial-gradient(circle_at_top_left,rgba(149,212,179,0.75),rgba(15,82,56,0.94))]">
                @if ($store->cover_url)
                    <img src="{{ $store->cover_url }}" alt="{{ $store->name }}"
                         class="h-full w-full object-cover transition duration-500 hover:scale-105">
                    <div class="absolute inset-0 bg-linear-to-t from-[rgba(22,26,50,0.38)] via-transparent to-transparent"></div>
                @else
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(177,240,206,0.88),transparent_38%),linear-gradient(135deg,rgba(15,82,56,0.95),rgba(45,106,79,0.84))]"></div>
                    <div class="absolute inset-x-0 bottom-0 h-28 bg-linear-to-t from-[rgba(22,26,50,0.35)] to-transparent"></div>
                @endif
            </div>

            {{-- Badges sobre la imagen --}}
            <div class="absolute right-5 top-5 flex flex-wrap justify-end gap-2">
                @if ($store->is_featured)
                    <span class="group relative cb-pill bg-[rgba(255,255,255,0.9)] text-(--cb-primary)" tabindex="0" aria-label="Destacado">
                        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">star</span>
                        <span class="pointer-events-none absolute -bottom-9 left-1/2 z-20 -translate-x-1/2 rounded-md bg-(--cb-text) px-2 py-1 text-xs whitespace-nowrap text-white opacity-0 shadow-md transition-opacity duration-150 group-hover:opacity-100 group-focus:opacity-100">
                            Destacado
                        </span>
                    </span>
                @endif
                @foreach ($categoryBadges as $categoryBadge)
                    <span class="cb-pill bg-[rgba(255,255,255,0.82)] text-(--cb-muted)">{{ $categoryBadge->name }}</span>
                @endforeach
            </div>

            {{-- Logo: posicionado sobre el wrapper, sobresaliendo hacia abajo desde la imagen --}}
            <div class="absolute -bottom-8 ml-4 z-10">
                @if ($store->logo_url)
                    <div class="h-16 w-16 overflow-hidden rounded-2xl border-4 border-white bg-white shadow-lg">
                        <img src="{{ $store->logo_url }}" alt="Logo de {{ $store->name }}" class="h-full w-full object-cover">
                    </div>
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl border-4 border-white bg-(--cb-secondary-soft) text-lg font-bold text-(--cb-secondary) shadow-lg">
                        {{ $initials }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Contenido: pt-12 para dejar espacio al logo que sobresale --}}
        <div class="flex flex-1 flex-col p-5 pt-10">
            <h3 class="text-2xl font-medium leading-tight tracking-tight hidden lg:flex">{{ $nameExcerpt }}</h3>
            <h3 class="text-2xl font-medium leading-tight tracking-tight lg:hidden">{{ $store->name }}</h3>

            <p class="mt-3 flex-1 text-base leading-8 font-light line-clamp-3">
                {{ $descriptionExcerpt }}
            </p>

            <div class="mt-5 flex items-center justify-between gap-4">
                <span class="inline-flex items-center gap-2 text-sm text-(--cb-outline)">
                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                    {{ $addressExcerpt }}
                </span>
            </div>
        </div>
    </a>

</article>
