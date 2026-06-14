@php
    $primaryCategory = $store->categories->first();
    $initials = collect(preg_split('/\s+/', trim($store->name) ?: 'N L'))
        ->filter()
        ->take(2)
        ->map(fn ($segment) => Illuminate\Support\Str::upper(Illuminate\Support\Str::substr($segment, 0, 1)))
        ->implode('');

    $descriptionExcerpt = $store->description
        ? Illuminate\Support\Str::limit(
            trim(html_entity_decode(strip_tags($store->description), ENT_QUOTES, 'UTF-8')),
            140,
        )
        : 'Emprendimiento local registrado dentro del directorio de Coronel Bogado.';

    $detailRouteKey = $store->slug ?: $store->getKey();
@endphp

<article class="cb-card flex h-full flex-col">
    <a href="{{ route('tiendas.show', $detailRouteKey) }}">
        {{-- Wrapper relativo: permite que el logo sobresalga hacia abajo --}}
        <div class="relative">

            {{-- Imagen con overflow-hidden propio para recortar solo la foto --}}
            <div class="h-56 overflow-hidden rounded-t-[inherit] bg-[radial-gradient(circle_at_top_left,_rgba(149,212,179,0.75),_rgba(15,82,56,0.94))]">
                @if ($store->cover_url)
                    <img src="{{ $store->cover_url }}" alt="{{ $store->name }}"
                         class="h-full w-full object-cover transition duration-500 hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(22,26,50,0.38)] via-transparent to-transparent"></div>
                @else
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(177,240,206,0.88),_transparent_38%),linear-gradient(135deg,_rgba(15,82,56,0.95),_rgba(45,106,79,0.84))]"></div>
                    <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-[rgba(22,26,50,0.35)] to-transparent"></div>
                @endif
            </div>

            {{-- Badges sobre la imagen --}}
            <div class="absolute right-5 top-5 flex flex-wrap justify-end gap-2">
                @if ($store->is_featured)
                    <span class="cb-pill bg-[rgba(255,255,255,0.9)] text-(--cb-primary)">
                        <span class="material-symbols-outlined text-[16px]">star</span>
                        Destacado
                    </span>
                @endif
                @if ($primaryCategory)
                    <span class="cb-pill bg-[rgba(255,255,255,0.82)] text-(--cb-muted)">{{ $primaryCategory->name }}</span>
                @endif
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
        <div class="flex flex-1 flex-col p-5 pt-12">
            <h3 class="text-[1.75rem] font-semibold leading-tight tracking-tight text-(--cb-text)">{{ $store->name }}</h3>

            <p class="mt-3 flex-1 text-base leading-8 text-(--cb-muted) line-clamp-3">
                {{ $descriptionExcerpt }}
            </p>

            <div class="mt-5 flex items-center justify-between gap-4">
                <span class="inline-flex items-center gap-2 text-sm text-(--cb-outline)">
                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                    {{ $store->address ?: 'Coronel Bogado, Itapúa' }}
                </span>
            </div>
        </div>
    </a>

</article>
