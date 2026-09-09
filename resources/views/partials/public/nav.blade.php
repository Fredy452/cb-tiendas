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
                        class="border-b-2 pb-1 text-lg font-medium transition {{ $link['active'] ? 'border-(--cb-primary) text-(--cb-primary)' : 'border-transparent text-(--cb-muted) hover:text-(--cb-primary)' }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hidden md:block">
                <a href="{{ route('emprendimientos.create') }}" class="cb-button-primary">Registrar emprendimiento</a>
            </div>

            <button
                type="button"
                class="flex cursor-pointer items-center rounded-full border border-(--cb-border) bg-white p-2 text-(--cb-primary) shadow-[0_10px_24px_rgba(22,27,45,0.08)] md:hidden"
                aria-controls="mobile-nav-drawer"
                aria-expanded="false"
                data-mobile-nav-toggle
            >
                <span class="material-symbols-outlined">menu</span>
            </button>

            <div class="pointer-events-none fixed inset-0 z-50 transition-opacity duration-300 ease-out md:hidden" style="width: 100vw; height: 100dvh; overflow: hidden; opacity: 0;" data-mobile-nav-layer>
                <button
                    type="button"
                    class="absolute inset-0 bg-[#111827]/55"
                    aria-label="Cerrar menú"
                    data-mobile-nav-overlay
                ></button>

                <aside
                    id="mobile-nav-drawer"
                    class="absolute top-0 right-0 h-dvh w-[min(24rem,92vw)] bg-white shadow-[-12px_0_32px_rgba(15,23,42,0.18)] transition-transform duration-300 ease-out"
                    style="top: 0; right: 0; bottom: 0; width: min(92vw, 24rem); max-width: 100vw; overflow-x: hidden; background: #ffffff; box-shadow: -12px 0 32px rgba(15, 23, 42, 0.18); translate: 100%;"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Menú de navegación"
                    data-mobile-nav-drawer
                >
                    <div class="relative border-b border-(--cb-border) px-5 pt-5 pb-4">

                        <button
                            type="button"
                            class="ml-auto inline-flex items-center gap-1 rounded-full border border-(--cb-border) px-3 py-1.5 text-sm font-medium text-(--cb-text)"
                            data-mobile-nav-close
                        >
                            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                            Volver
                        </button>
                    </div>

                    <div class="h-[calc(100dvh-88px)] overflow-y-auto px-5 py-5" style="height: calc(100dvh - 88px); overflow-y: auto;">
                        <div class="space-y-2 border-b border-(--cb-border) pb-5">
                            @foreach ($links as $link)
                                <a
                                    href="{{ $link['route'] }}"
                                    class="block rounded-2xl px-4 py-3.5 text-base font-medium transition {{ $link['active'] ? 'bg-[rgba(177,240,206,0.45)] text-(--cb-primary)' : 'text-(--cb-text) hover:bg-[rgba(244,242,255,0.85)]' }}"
                                >
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('emprendimientos.create') }}" class="cb-button-primary mt-5 hidden w-full lg:block">Registrar emprendimiento</a>
                    </div>
                </aside>
            </div>
        @endif
    </div>
</header>

@unless ($variant === 'minimal')
    @push('scripts')
        <script>
            (() => {
                const setupMobileNavOverride = () => {
                    document.querySelectorAll('[data-mobile-nav-toggle]').forEach((toggle) => {
                        if (toggle.dataset.inlineNavReady === 'true') {
                            return;
                        }

                        const layer = document.querySelector('[data-mobile-nav-layer]');
                        const drawer = layer?.querySelector('[data-mobile-nav-drawer]');
                        const overlay = layer?.querySelector('[data-mobile-nav-overlay]');
                        const closeButton = layer?.querySelector('[data-mobile-nav-close]');

                        if (!layer || !drawer || !overlay || !closeButton) {
                            return;
                        }

                        const setExpanded = (expanded) => {
                            toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
                            layer.classList.toggle('pointer-events-none', !expanded);
                            layer.style.opacity = expanded ? '1' : '0';
                            drawer.style.translate = expanded ? '0' : '100%';
                            document.body.classList.toggle('overflow-hidden', expanded);
                        };

                        const handleToggle = (event) => {
                            event.preventDefault();
                            event.stopImmediatePropagation();
                            const expanded = toggle.getAttribute('aria-expanded') === 'true';
                            setExpanded(!expanded);
                        };

                        const handleClose = (event) => {
                            event.preventDefault();
                            event.stopImmediatePropagation();
                            setExpanded(false);
                        };

                        toggle.dataset.inlineNavReady = 'true';
                        setExpanded(false);

                        toggle.addEventListener('click', handleToggle, true);
                        overlay.addEventListener('click', handleClose, true);
                        closeButton.addEventListener('click', handleClose, true);

                        document.addEventListener('keydown', (event) => {
                            if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
                                setExpanded(false);
                            }
                        });
                    });
                };

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', setupMobileNavOverride, { once: true });
                } else {
                    setupMobileNavOverride();
                }
            })();
        </script>
    @endpush
@endunless
