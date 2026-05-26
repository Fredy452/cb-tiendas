@extends('layouts.public')

@section('title', 'Registrar emprendimiento | CB Tiendas')
@section('meta_description', 'Formulario público para postular un emprendimiento al directorio de CB Tiendas.')
@section('nav_variant', 'minimal')

@section('content')
    <section class="cb-section overflow-hidden bg-[radial-gradient(circle_at_top_left,_rgba(177,240,206,0.28),_transparent_34%),radial-gradient(circle_at_top_right,_rgba(212,227,255,0.36),_transparent_36%)]">
        <div class="cb-shell grid gap-10 lg:grid-cols-[minmax(0,1fr)_minmax(0,1.15fr)] lg:items-start">
            <div>
                <p class="cb-kicker mb-4">Sumate a la comunidad</p>
                <h1 class="cb-display max-w-2xl">Impulsá tu negocio con CB Tiendas. Unite hoy mismo.</h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-(--cb-muted)">
                    Conectá tu emprendimiento con la comunidad de Coronel Bogado. Una plataforma diseñada para destacar lo mejor de nuestra gente.
                </p>

                <div class="mt-10 grid gap-6 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="flex gap-4 rounded-[1.5rem] bg-white/70 p-4 shadow-[0_12px_28px_rgba(22,26,50,0.05)] backdrop-blur-sm">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[rgba(177,240,206,0.55)] text-(--cb-primary)">
                            <span class="material-symbols-outlined">payments</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold tracking-tight">Es 100% gratis</h2>
                            <p class="mt-2 text-base leading-7 text-(--cb-muted)">Sin comisiones ni costos ocultos. Una iniciativa para fortalecer la economía local.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 rounded-[1.5rem] bg-white/70 p-4 shadow-[0_12px_28px_rgba(22,26,50,0.05)] backdrop-blur-sm">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[rgba(212,227,255,0.8)] text-(--cb-secondary)">
                            <span class="material-symbols-outlined">visibility</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold tracking-tight">Mayor visibilidad</h2>
                            <p class="mt-2 text-base leading-7 text-(--cb-muted)">Tu negocio estará disponible para todos los habitantes y visitantes de la ciudad en un solo lugar.</p>
                        </div>
                    </div>

                    <div class="flex gap-4 rounded-[1.5rem] bg-white/70 p-4 shadow-[0_12px_28px_rgba(22,26,50,0.05)] backdrop-blur-sm">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[rgba(222,224,255,0.85)] text-(--cb-text)">
                            <span class="material-symbols-outlined">trending_up</span>
                        </div>
                        <div>
                            <h2 class="text-2xl font-semibold tracking-tight">Más oportunidades</h2>
                            <p class="mt-2 text-base leading-7 text-(--cb-muted)">Facilitá que los clientes te encuentren y contacten directamente para conocer tus productos o servicios.</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 rounded-[1.75rem] border border-[rgba(222,224,255,0.85)] bg-[rgba(255,255,255,0.72)] p-6 shadow-[0_16px_36px_rgba(22,26,50,0.05)] backdrop-blur-sm">
                    <h2 class="cb-subheading">¿Necesitás ayuda?</h2>
                    <p class="mt-3 text-base leading-7 text-(--cb-muted)">Nuestro equipo está disponible para acompañarte con el alta inicial del emprendimiento.</p>
                    <div class="mt-5 space-y-3 text-sm font-semibold text-(--cb-primary)">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                            soporte@cbtiendas.com.py
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">call</span>
                            +595 9XX XXX XXX
                        </div>
                    </div>
                </div>
            </div>

            <div class="cb-panel p-6 sm:p-8">
                <h2 class="cb-subheading">Completá tus datos</h2>
                <p class="mt-3 text-base leading-7 text-(--cb-muted)">
                    Esta postulación queda pendiente de revisión administrativa antes de publicarse en el directorio.
                </p>

                @if ($errors->any())
                    <div class="mt-6 rounded-[1.5rem] border border-[rgba(186,26,26,0.18)] bg-[rgba(255,218,214,0.6)] px-5 py-4 text-sm leading-7 text-[#93000a]">
                        <p class="font-semibold">Revisá los datos cargados:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('emprendimientos.store') }}" method="POST" class="mt-8 space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-(--cb-text)">Nombre del negocio</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="cb-input" placeholder="Ej: Panadería La Abuela">
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="category_id" class="mb-2 block text-sm font-semibold text-(--cb-text)">Rubro / categoría</label>
                            <select id="category_id" name="category_id" class="cb-input">
                                <option value="">Seleccioná una categoría</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((int) old('category_id') === $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="phone" class="mb-2 block text-sm font-semibold text-(--cb-text)">Teléfono de contacto</label>
                            <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="cb-input" placeholder="Ej: 09XX XXX XXX">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="mb-2 block text-sm font-semibold text-(--cb-text)">Breve descripción</label>
                        <textarea id="description" name="description" rows="5" class="cb-input resize-none" placeholder="Contanos qué productos o servicios ofrecés...">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="cb-button-primary w-full rounded-2xl py-4 text-base">
                        Registrar mi emprendimiento
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>

                    <p class="text-center text-sm leading-7 text-(--cb-muted)">
                        Al registrarte, aceptás que la solicitud pase primero por una revisión administrativa.
                    </p>
                </form>
            </div>
        </div>
    </section>
@endsection
