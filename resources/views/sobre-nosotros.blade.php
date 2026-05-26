@extends('layouts.public')

@section('title', 'Sobre nosotros | CB Tiendas')
@section('meta_description', 'Propósito, funcionamiento e impacto comunitario de la plataforma pública CB Tiendas.')

@section('content')
    <section class="cb-shell cb-section text-center">
        <p class="cb-kicker mb-4">Comunidad y propósito</p>
        <h1 class="cb-heading text-(--cb-primary) sm:text-5xl">El corazón digital de Coronel Bogado</h1>
        <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-(--cb-muted)">
            Nacimos con un propósito claro: fortalecer la economía local y crear un espacio donde la comunidad y los emprendedores se encuentren. CB Tiendas es la plaza digital de nuestra ciudad, diseñada para dar visibilidad al esfuerzo local y fomentar el crecimiento mutuo.
        </p>

        <div class="mt-10 overflow-hidden rounded-[2rem] bg-[radial-gradient(circle_at_top,_rgba(177,240,206,0.55),_transparent_35%),linear-gradient(135deg,_rgba(15,82,56,0.94),_rgba(45,106,79,0.72))] p-8 shadow-[0_26px_58px_rgba(22,26,50,0.08)]">
            <div class="rounded-[1.75rem] border border-white/25 bg-white/10 px-8 py-16 backdrop-blur-sm">
                <div class="mx-auto flex max-w-3xl flex-col items-center text-white">
                    <span class="material-symbols-outlined text-5xl">diversity_3</span>
                    <p class="mt-6 text-lg leading-8 text-white/88">
                        Una plataforma pública pensada para visitantes, emprendedores y administradores que quieren impulsar el ecosistema local desde una experiencia más clara, cálida y accesible.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="cb-section bg-[rgba(244,242,255,0.82)]">
        <div class="cb-shell">
            <div class="text-center">
                <p class="cb-kicker mb-4">Impacto esperado</p>
                <h2 class="cb-subheading">¿Por qué existe CB Tiendas?</h2>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <div class="cb-card p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(177,240,206,0.55)] text-(--cb-primary)">
                        <span class="material-symbols-outlined">visibility</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold tracking-tight">Visibilidad local</h3>
                    <p class="mt-3 leading-8 text-(--cb-muted)">Acercamos los productos y servicios de nuestra gente a cada rincón de la ciudad, facilitando que los vecinos encuentren lo que necesitan.</p>
                </div>

                <div class="cb-card p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(212,227,255,0.8)] text-(--cb-secondary)">
                        <span class="material-symbols-outlined">groups</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold tracking-tight">Sentido de comunidad</h3>
                    <p class="mt-3 leading-8 text-(--cb-muted)">Más que un directorio, buscamos unir compradores y vendedores, construyendo confianza y orgullo por lo nuestro.</p>
                </div>

                <div class="cb-card p-7">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[rgba(222,224,255,0.85)] text-(--cb-text)">
                        <span class="material-symbols-outlined">trending_up</span>
                    </div>
                    <h3 class="mt-5 text-2xl font-semibold tracking-tight">Crecimiento económico</h3>
                    <p class="mt-3 leading-8 text-(--cb-muted)">Impulsamos la profesionalización de los emprendimientos locales con una herramienta digital que potencia su alcance.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cb-shell cb-section" id="comunidad">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="cb-panel p-7 sm:p-8">
                <div class="flex items-center gap-3 text-(--cb-primary)">
                    <span class="material-symbols-outlined">travel_explore</span>
                    <h2 class="cb-subheading text-(--cb-primary)">Para visitantes</h2>
                </div>

                <div class="mt-8 space-y-6">
                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-(--cb-primary) text-lg font-semibold text-(--cb-primary)">1</div>
                        <div>
                            <h3 class="text-lg font-semibold text-(--cb-text)">Explora</h3>
                            <p class="mt-2 leading-8 text-(--cb-muted)">Busca por categorías o utiliza el buscador para encontrar el negocio o servicio que necesitás en la ciudad.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-(--cb-primary) text-lg font-semibold text-(--cb-primary)">2</div>
                        <div>
                            <h3 class="text-lg font-semibold text-(--cb-text)">Descubre detalles</h3>
                            <p class="mt-2 leading-8 text-(--cb-muted)">Revisa la información del emprendimiento, su ubicación, datos de contacto y rubros vinculados.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-(--cb-primary) text-lg font-semibold text-(--cb-primary)">3</div>
                        <div>
                            <h3 class="text-lg font-semibold text-(--cb-text)">Conecta</h3>
                            <p class="mt-2 leading-8 text-(--cb-muted)">Contacta directamente con el emprendedor por los canales publicados dentro del directorio.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="cb-panel p-7 sm:p-8">
                <div class="flex items-center gap-3 text-(--cb-secondary)">
                    <span class="material-symbols-outlined">storefront</span>
                    <h2 class="cb-subheading text-(--cb-secondary)">Para emprendedores</h2>
                </div>

                <div class="mt-8 space-y-6">
                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-(--cb-secondary) text-lg font-semibold text-(--cb-secondary)">1</div>
                        <div>
                            <h3 class="text-lg font-semibold text-(--cb-text)">Regístrate</h3>
                            <p class="mt-2 leading-8 text-(--cb-muted)">Completa la postulación pública con los datos básicos de tu actividad para iniciar la revisión.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-(--cb-secondary) text-lg font-semibold text-(--cb-secondary)">2</div>
                        <div>
                            <h3 class="text-lg font-semibold text-(--cb-text)">Prepara tu perfil</h3>
                            <p class="mt-2 leading-8 text-(--cb-muted)">Una vez aprobado, tu emprendimiento podrá sumar más datos, imágenes y canales de contacto dentro de la plataforma.</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-(--cb-secondary) text-lg font-semibold text-(--cb-secondary)">3</div>
                        <div>
                            <h3 class="text-lg font-semibold text-(--cb-text)">Crece</h3>
                            <p class="mt-2 leading-8 text-(--cb-muted)">Alcanza a más clientes locales y forma parte de una red comercial más visible y organizada.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cb-shell cb-section pt-0" id="contacto">
        <div class="overflow-hidden rounded-[2.5rem] bg-[linear-gradient(135deg,_rgba(222,224,255,0.85),_rgba(244,242,255,0.95))] px-6 py-14 text-center shadow-[0_26px_56px_rgba(22,26,50,0.06)] sm:px-10">
            <p class="cb-kicker mb-4">Seguimos creciendo</p>
            <h2 class="cb-heading mx-auto max-w-3xl">Sé parte de nuestra comunidad</h2>
            <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-(--cb-muted)">
                Ya sea que busques apoyar lo local o quieras impulsar tu negocio, hay un lugar para ti en CB Tiendas.
            </p>

            <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ route('emprendimientos.create') }}" class="cb-button-primary">Registrar mi emprendimiento</a>
                <a href="{{ route('tiendas.index') }}" class="cb-button-ghost">Explorar directorio</a>
            </div>
        </div>
    </section>
@endsection
