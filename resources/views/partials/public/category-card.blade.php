@php
    $palettes = [
        ['from' => '#0f5238', 'to' => '#2d6a4f', 'soft' => '#b1f0ce', 'icon' => 'restaurant'],
        ['from' => '#005fad', 'to' => '#58a3fe', 'soft' => '#d4e3ff', 'icon' => 'spa'],
        ['from' => '#484743', 'to' => '#605f5b', 'soft' => '#e5e2dc', 'icon' => 'construction'],
        ['from' => '#2b2f48', 'to' => '#404943', 'soft' => '#dee0ff', 'icon' => 'workspaces'],
        ['from' => '#7c4a22', 'to' => '#c78c57', 'soft' => '#f3ddc8', 'icon' => 'checkroom'],
        ['from' => '#373c7a', 'to' => '#5b7cf4', 'soft' => '#dce4ff', 'icon' => 'headphones'],
    ];

    $palette = $palettes[$index % count($palettes)];
    $categoryKey = $category->slug ?: $category->id;
@endphp

<a href="{{ route('tiendas.index', ['category' => $categoryKey]) }}" class="cb-card group flex h-full flex-col overflow-hidden">
    <div class="relative h-52 overflow-hidden">
        @if ($category->cover_url)
            <img src="{{ $category->cover_url }}" alt="{{ $category->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-linear-to-t from-[rgba(22,26,50,0.62)] via-[rgba(22,26,50,0.08)] to-transparent"></div>
        @else
            @php($bgGradient = "background: radial-gradient(circle at top right, {$palette['soft']}, transparent 35%), linear-gradient(135deg, {$palette['from']}, {$palette['to']});")
            <div class="absolute inset-0" style="{{ $bgGradient }}"></div>
            <div class="absolute inset-y-0 right-[-18%] w-1/2 rounded-full bg-white/10 blur-3xl"></div>
        @endif

        <div class="absolute inset-x-0 bottom-0 p-5 text-white">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/18 px-3 py-1 text-xs font-semibold backdrop-blur-sm">
                <span class="material-symbols-outlined text-[16px]">storefront</span>
                {{ $category->public_stores_count }} {{ Illuminate\Support\Str::plural('negocio', $category->public_stores_count) }}
            </span>

            <h3 class="mt-3 text-3xl font-semibold tracking-tight">{{ $category->name }}</h3>
        </div>

        @if (! $category->cover_url)
            <div class="absolute left-5 top-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-white/18 text-white backdrop-blur-sm">
                <span class="material-symbols-outlined">{{ $palette['icon'] }}</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-6">
        <p class="flex-1 text-base leading-8 text-(--cb-muted) line-clamp-3">
            {!! $category->description ?: 'Rubros visibles dentro de la plaza digital para que la comunidad encuentre servicios y productos locales con rapidez.' !!}
        </p>

        <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-(--cb-primary)">
            Ver negocios <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </span>
    </div>
</a>
