@php
    $backUrl = url()->previous() !== url()->current() ? url()->previous() : route('home');

    $links = [
        ['label' => 'Inicio', 'route' => route('home'), 'active' => request()->routeIs('home')],
        ['label' => 'Negocios', 'route' => route('tiendas.index'), 'active' => request()->routeIs('tiendas.*')],
        ['label' => 'Categorías', 'route' => route('categorias'), 'active' => request()->routeIs('categorias')],
        ['label' => 'Sobre nosotros', 'route' => route('sobre-nosotros'), 'active' => request()->routeIs('sobre-nosotros')],
    ];
@endphp

<header class="sticky top-0 z-40 border-b border-white/70 bg-white/90 backdrop-blur">
    <div class="cb-shell flex items-center justify-between gap-4 py-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3 text-(--cb-primary) transition hover:opacity-85">
            <span class="material-symbols-outlined text-[28px]">storefront</span>
            <span class="text-2xl font-bold tracking-tight" style="font-family: var(--cb-font-display);">CB Tiendas</span>
        </a>

        @if ($variant === 'minimal')
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-(--cb-text) transition hover:text-(--cb-primary)">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                Volver
            </a>
        @else
            <nav class="hidden items-center gap-8 md:flex">
                @foreach ($links as $link)
                    <a
                        href="{{ $link['route'] }}"
                        class="border-b-2 pb-1 text-sm font-semibold transition {{ $link['active'] ? 'border-(--cb-primary) text-(--cb-primary)' : 'border-transparent text-(--cb-muted) hover:text-(--cb-primary)' }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden md:block">
                <a href="{{ route('emprendimientos.create') }}" class="cb-button-primary">Registrar emprendimiento</a>
            </div>

            <details class="relative md:hidden">
                <summary class="flex list-none cursor-pointer items-center rounded-full border border-(--cb-border) bg-white p-2 text-(--cb-primary)">
                    <span class="material-symbols-outlined">menu</span>
                </summary>

                <div class="cb-panel absolute right-0 mt-3 w-72 overflow-hidden p-2">
                    @foreach ($links as $link)
                        <a
                            href="{{ $link['route'] }}"
                            class="block rounded-2xl px-4 py-3 text-sm font-medium transition {{ $link['active'] ? 'bg-[rgba(177,240,206,0.45)] text-(--cb-primary)' : 'text-(--cb-text) hover:bg-[rgba(244,242,255,0.85)]' }}"
                        >
                            {{ $link['label'] }}
                        </a>
                    @endforeach

                    <a href="{{ route('emprendimientos.create') }}" class="cb-button-primary mt-2 w-full rounded-2xl">Registrar emprendimiento</a>
                </div>
            </details>
        @endif
    </div>
</header>
