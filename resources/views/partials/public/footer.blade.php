<footer class="border-t border-white/70 bg-white/75 backdrop-blur">
    <div class="cb-shell grid gap-10 pt-12 pb-6 md:grid-cols-[minmax(0,1.3fr)_1fr_1fr]">
        <div class="text-center md:text-left">
            <a href="{{ route('home') }}" class="flex w-full items-center justify-center gap-3 text-(--cb-primary) transition hover:opacity-85 md:justify-start">
                <span class="material-symbols-outlined text-[28px]">storefront</span>
                <span class="text-2xl font-bold tracking-tight" style="font-family: var(--cb-font-display);">CB Tiendas</span>
            </a>

            <p class="mt-4 text-lg leading-8 font-normal">
                Proyecto de Extensión Universitaria desarrollado por estudiantes de Lic. En Informática Empresarial de la Universidad Nacional de Itapúa (UNI) - Sede Coronel Bogado.
            </p>

        </div>

        <div class="text-center md:text-left">
            <h2 class="text-xl uppercase font-bold">Plataforma</h2>
            <div class="mt-4 space-y-3 text-lg font-normal">
                <a href="{{ route('home') }}" class="block transition hover:text-(--cb-primary)">Inicio</a>
                <a href="{{ route('tiendas.index') }}" class="block transition hover:text-(--cb-primary)">Negocios</a>
                <a href="{{ route('categorias') }}" class="block transition hover:text-(--cb-primary)">Categorías</a>
            </div>
        </div>

        <div class="text-center md:text-left">
            <h2 class="text-xl uppercase font-bold">Comunidad</h2>
            <div class="mt-4 space-y-3 text-lg font-normal">
                <a href="{{ route('sobre-nosotros') }}" class="block transition hover:text-(--cb-primary)">Sobre nosotros</a>
                <a href="{{ route('emprendimientos.create') }}" class="block transition hover:text-(--cb-primary)">Registrar emprendimiento</a>
                <a href="mailto:soporte@cbtiendas.com.py" class="block transition hover:text-(--cb-primary)">Contacto institucional</a>
            </div>
        </div>

    </div>
    <div class="cb-shell text-center pb-12">
        <hr class="text-(--cb-outline)/30" />
        <p class="mt-4 text-[1rem] leading-8 font-normal text-(--cb-outline)">
            Aprobado por Resolución CD N° 071/2026 - FaCyT UNI
        </p>
        <p class="text-[1rem] text-(--cb-outline)">
            © {{ now()->year }} CB Tiendas - Coronel Bogado, Itapúa, Paraguay.
        </p>
    </div>
</footer>
