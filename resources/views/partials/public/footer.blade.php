<footer class="border-t border-white/70 bg-white/75 backdrop-blur">
    <div class="cb-shell grid gap-10 py-12 md:grid-cols-[minmax(0,1.3fr)_1fr_1fr]">
        <div>
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-(--cb-primary) transition hover:opacity-85">
                <span class="material-symbols-outlined text-[28px]">storefront</span>
                <span class="text-2xl font-bold tracking-tight" style="font-family: var(--cb-font-display);">CB Tiendas</span>
            </a>

            <p class="mt-4 max-w-md text-base leading-8 text-(--cb-muted)">
                La plaza digital de Coronel Bogado. Conectando a nuestra comunidad con los mejores emprendimientos locales.
            </p>

            <p class="mt-6 text-sm text-(--cb-outline)">
                © {{ now()->year }} CB Tiendas - Orgullo de Coronel Bogado
            </p>
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.22em] text-(--cb-outline)">Plataforma</h2>
            <div class="mt-4 space-y-3 text-sm font-medium text-(--cb-muted)">
                <a href="{{ route('home') }}" class="block transition hover:text-(--cb-primary)">Inicio</a>
                <a href="{{ route('tiendas.index') }}" class="block transition hover:text-(--cb-primary)">Negocios</a>
                <a href="{{ route('categorias') }}" class="block transition hover:text-(--cb-primary)">Categorías</a>
            </div>
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.22em] text-(--cb-outline)">Comunidad</h2>
            <div class="mt-4 space-y-3 text-sm font-medium text-(--cb-muted)">
                <a href="{{ route('sobre-nosotros') }}" class="block transition hover:text-(--cb-primary)">Sobre nosotros</a>
                <a href="{{ route('emprendimientos.create') }}" class="block transition hover:text-(--cb-primary)">Registrar emprendimiento</a>
                <a href="mailto:soporte@cbtiendas.com.py" class="block transition hover:text-(--cb-primary)">Contacto institucional</a>
            </div>
        </div>
    </div>
</footer>
