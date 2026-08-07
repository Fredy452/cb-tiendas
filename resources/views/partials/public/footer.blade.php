<footer class="border-t border-white/70 bg-white/75 backdrop-blur">
    <div class="cb-shell grid gap-10 py-12 md:grid-cols-[minmax(0,1.3fr)_1fr_1fr]">
        <div>
            <a href="{{ route('home') }}" class="flex w-full items-center justify-start gap-3 text-(--cb-primary) transition hover:opacity-85 sm:justify-center md:justify-start">
                <span class="material-symbols-outlined text-[28px]">storefront</span>
                <span class="text-2xl font-bold tracking-tight" style="font-family: var(--cb-font-display);">CB Tiendas</span>
            </a>

            <p class="mt-4 text-[1rem] leading-8 font-normal">
                Proyecto de Extensión "Aplicación Web de Repositorio y Visibilización de Emprendedores de Coronel Bogado mediante Software Libre"
            </p>

            <p class="mt-4 text-[1rem] leading-8 font-normal">
                Aprobado por Resolución CD N° 071/2026 - FaCyT UNI
            </p>

            {{-- <p class="mt-6 text-[1rem] text-(--cb-outline)">
                © {{ now()->year }} CB Tiendas - Orgullo de Coronel Bogado
            </p> --}}
        </div>

        <div>
            <h2 class="font-medium text-xl">Plataforma</h2>
            <div class="mt-4 space-y-3 text-[1rem] font-medium">
                <a href="{{ route('home') }}" class="block transition hover:text-(--cb-primary)">Inicio</a>
                <a href="{{ route('tiendas.index') }}" class="block transition hover:text-(--cb-primary)">Negocios</a>
                <a href="{{ route('categorias') }}" class="block transition hover:text-(--cb-primary)">Categorías</a>
            </div>
        </div>

        <div>
            <h2 class="font-medium text-xl">Comunidad</h2>
            <div class="mt-4 space-y-3 text-[1rem] font-medium">
                <a href="{{ route('sobre-nosotros') }}" class="block transition hover:text-(--cb-primary)">Sobre nosotros</a>
                <a href="{{ route('emprendimientos.create') }}" class="block transition hover:text-(--cb-primary)">Registrar emprendimiento</a>
                <a href="mailto:soporte@cbtiendas.com.py" class="block transition hover:text-(--cb-primary)">Contacto institucional</a>
            </div>
        </div>
    </div>
</footer>
