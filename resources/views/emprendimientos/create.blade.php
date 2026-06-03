@extends('layouts.public')

@section('title', 'Registrar emprendimiento | CB Tiendas')
@section('meta_description', 'Formulario público para postular un emprendimiento al directorio de CB Tiendas.')
@section('nav_variant', 'minimal')

@section('content')
    @php
        $heroImageUrl = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBCa4lTc83jvrC6ZTSIWAg3cdOHFd3AgiIizbpMr0-DgLWzYs9dOHz8X1Fg5lVCLvCPdLWYe8FLl2hOuTXhZDqjxms-YmHv_OIz_kNHWFXlBXxx0eAM1C6aHfPbfp-RIgX1mf5-eiXlCrLQy19qDumy-iciQujHroC2nX2XOPrgNrOZOF8-2KpYAueh9XhMF88Vy9AbFrT4fDPIqnZ6b45XKuvCKuDP9KPvWCdy5Pkt49p51_rMbuYHvWvLdlbSlw6Wu3ebgcBtC-w';
        $selectedCategoryId = (string) old('category_id', '');
        $selectedCategory = $categories->first(fn ($category) => (string) $category->id === $selectedCategoryId);
    @endphp

    <section class="overflow-hidden py-14 sm:py-16" style="background: radial-gradient(circle at 100% 0%, rgba(15, 82, 56, 0.1), transparent 46%), var(--cb-surface-soft);">
        <div class="cb-shell grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
            <div class="max-w-2xl">
                <h1 class="cb-display text-(--cb-text)">Impulsá tu negocio con CB Tiendas. Unite hoy mismo.</h1>
                <p class="mt-5 max-w-xl text-lg leading-8 text-(--cb-muted)">
                    Conectá tu emprendimiento con la comunidad de Coronel Bogado. Una plataforma diseñada para destacar lo mejor de nuestra gente.
                </p>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow-[0_20px_30px_rgba(0,0,0,0.08)]">
                <img
                    src="{{ $heroImageUrl }}"
                    alt="Emprendedor sonriendo en su negocio local"
                    class="h-72 w-full object-cover sm:h-75"
                    loading="eager"
                    fetchpriority="high"
                    onerror="this.onerror=null;this.src='{{ asset('img/placeholders/store_placeholder.jpg') }}';"
                >
            </div>
        </div>
    </section>

    <section class="cb-section">
        <div class="cb-shell grid gap-12 lg:grid-cols-[minmax(0,0.78fr)_minmax(0,1.12fr)] lg:items-start">
            <div class="space-y-10">
                <div>
                    <h2 class="cb-heading text-(--cb-text)">¿Por qué registrarte?</h2>

                    <div class="mt-8 space-y-7">
                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-(--cb-primary-strong) text-(--cb-primary-soft)">
                                <span class="material-symbols-outlined">price_check</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-semibold tracking-tight text-(--cb-text)">Es 100% Gratis</h3>
                                <p class="mt-1 max-w-md leading-7 text-(--cb-muted)">Sin comisiones ni costos ocultos. Una iniciativa para fortalecer nuestra economía local.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-[rgba(88,163,254,0.95)] text-[#003869]">
                                <span class="material-symbols-outlined">visibility</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-semibold tracking-tight text-(--cb-text)">Mayor Visibilidad</h3>
                                <p class="mt-1 max-w-md leading-7 text-(--cb-muted)">Tu negocio estará disponible para todos los habitantes y visitantes de la ciudad en un solo lugar.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-(--cb-border) text-(--cb-muted)">
                                <span class="material-symbols-outlined">trending_up</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-semibold tracking-tight text-(--cb-text)">Más Ventas</h3>
                                <p class="mt-1 max-w-md leading-7 text-(--cb-muted)">Facilitá que los clientes te encuentren y contacten directamente para adquirir tus productos o servicios.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-[rgba(191,201,193,0.42)] bg-(--cb-surface-strong) p-6">
                    <h2 class="cb-subheading">¿Necesitás ayuda?</h2>
                    <p class="mt-4 text-base leading-7 text-(--cb-muted)">Nuestro equipo está disponible para ayudarte con el registro de tu emprendimiento.</p>
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

            <div class="cb-panel rounded-2xl p-6 shadow-[0_20px_30px_rgba(0,0,0,0.04)] sm:p-8">
                <h2 class="cb-subheading">Completá tus datos</h2>

                @if ($errors->any())
                    <div class="mt-6 rounded-3xl border border-[rgba(186,26,26,0.18)] bg-[rgba(255,218,214,0.6)] px-5 py-4 text-sm leading-7 text-[#93000a]">
                        <p class="font-semibold">Revisá los datos cargados:</p>
                        <ul class="mt-2 list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('emprendimientos.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 space-y-8">
                    @csrf

                    <fieldset class="space-y-6">
                        <legend class="sr-only">Datos principales del emprendimiento</legend>

                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-(--cb-text)">Nombre del negocio</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" class="cb-input" placeholder="Ej: Panadería La Abuela">
                        </div>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="category_id" class="mb-2 block text-sm font-semibold text-(--cb-text)">Rubro / Categoría</label>
                                <div class="relative" data-category-combobox>
                                    <select id="category_id" name="category_id" class="cb-input" data-category-native>
                                        <option value="">Seleccioná una categoría</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($selectedCategoryId === (string) $category->id)>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <div class="hidden" data-category-enhanced>
                                        <button type="button" class="cb-input flex items-center justify-between gap-3 text-left" data-category-toggle aria-haspopup="listbox" aria-expanded="false">
                                            <span class="truncate {{ $selectedCategory ? 'text-(--cb-text)' : 'text-(--cb-outline)' }}" data-category-current>
                                                {{ $selectedCategory?->name ?? 'Seleccioná una categoría' }}
                                            </span>
                                            <span class="material-symbols-outlined text-[20px] text-(--cb-outline)">expand_more</span>
                                        </button>

                                        <div class="absolute z-30 mt-2 hidden w-full rounded-2xl border border-[rgba(222,224,255,0.95)] bg-white p-2 shadow-[0_20px_34px_rgba(22,26,50,0.12)]" data-category-panel>
                                            <label for="category-search" class="sr-only">Buscar categoría</label>
                                            <div class="flex items-center gap-2 rounded-xl bg-(--cb-surface-soft) px-3 py-2 text-(--cb-outline)">
                                                <span class="material-symbols-outlined text-[20px]">search</span>
                                                <input id="category-search" type="search" class="w-full border-0 bg-transparent p-0 text-sm text-(--cb-text) outline-none placeholder:text-(--cb-outline) focus:ring-0" placeholder="Buscar categoría..." autocomplete="off" data-category-search>
                                            </div>

                                            <ul class="mt-2 max-h-60 space-y-1 overflow-auto pr-1" role="listbox" data-category-list>
                                                @foreach ($categories as $category)
                                                    <li>
                                                        <button type="button" class="w-full rounded-xl px-3 py-2 text-left text-sm font-medium text-(--cb-text) transition hover:bg-(--cb-surface-soft) focus:bg-(--cb-surface-soft) focus:outline-none" role="option" data-category-option data-value="{{ $category->id }}" data-label="{{ $category->name }}" aria-selected="{{ $selectedCategoryId === (string) $category->id ? 'true' : 'false' }}">
                                                            {{ $category->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <p class="hidden px-3 py-4 text-sm text-(--cb-muted)" data-category-empty>No encontramos categorías con ese nombre.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="phone" class="mb-2 block text-sm font-semibold text-(--cb-text)">Teléfono de contacto (WhatsApp)</label>
                                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="cb-input" placeholder="Ej: 09XX XXX XXX">
                            </div>
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-semibold text-(--cb-text)">Breve descripción de lo que hacés</label>
                            <textarea id="description" name="description" rows="5" class="cb-input resize-none" placeholder="Contanos qué productos o servicios ofrecés...">{{ old('description') }}</textarea>
                        </div>
                    </fieldset>

                    <fieldset class="space-y-6 border-t border-[rgba(222,224,255,0.9)] pt-8">
                        <legend class="flex items-center gap-2 text-base font-semibold text-(--cb-text)">
                            <span class="material-symbols-outlined text-[20px] text-(--cb-primary)">contact_mail</span>
                            Contacto y redes
                        </legend>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email" class="mb-2 block text-sm font-semibold text-(--cb-text)">Correo electrónico</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" class="cb-input" placeholder="Ej: contacto@negocio.com">
                            </div>

                            <div>
                                <label for="website" class="mb-2 block text-sm font-semibold text-(--cb-text)">Sitio web o catálogo</label>
                                <input id="website" name="website" type="url" value="{{ old('website') }}" class="cb-input" placeholder="https://tu-negocio.com">
                            </div>
                        </div>

                        <div class="grid gap-6 lg:grid-cols-3">
                            <div>
                                <label for="facebook_url" class="mb-2 block text-sm font-semibold text-(--cb-text)">Facebook</label>
                                <input id="facebook_url" name="facebook_url" type="url" value="{{ old('facebook_url') }}" class="cb-input" placeholder="https://facebook.com/tu-negocio">
                            </div>

                            <div>
                                <label for="instagram_url" class="mb-2 block text-sm font-semibold text-(--cb-text)">Instagram</label>
                                <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url') }}" class="cb-input" placeholder="https://instagram.com/tu-negocio">
                            </div>

                            <div>
                                <label for="tiktok_url" class="mb-2 block text-sm font-semibold text-(--cb-text)">TikTok</label>
                                <input id="tiktok_url" name="tiktok_url" type="url" value="{{ old('tiktok_url') }}" class="cb-input" placeholder="https://tiktok.com/@tu-negocio">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="space-y-6 border-t border-[rgba(222,224,255,0.9)] pt-8" data-location-picker>
                        <legend class="flex items-center gap-2 text-base font-semibold text-(--cb-text)">
                            <span class="material-symbols-outlined text-[20px] text-(--cb-primary)">location_on</span>
                            Ubicación
                        </legend>

                        <div>
                            <label for="address" class="mb-2 block text-sm font-semibold text-(--cb-text)">Dirección o zona de referencia</label>
                            <input id="address" name="address" type="text" value="{{ old('address') }}" class="cb-input" placeholder="Ej: Centro, Coronel Bogado">
                        </div>

                        <div class="grid gap-6 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
                            <div>
                                <label for="latitude" class="mb-2 block text-sm font-semibold text-(--cb-text)">Latitud</label>
                                <input id="latitude" name="latitude" type="text" value="{{ old('latitude') }}" class="cb-input" placeholder="-27.160530" data-location-latitude>
                            </div>

                            <div>
                                <label for="longitude" class="mb-2 block text-sm font-semibold text-(--cb-text)">Longitud</label>
                                <input id="longitude" name="longitude" type="text" value="{{ old('longitude') }}" class="cb-input" placeholder="-56.241407" data-location-longitude>
                            </div>

                            <button type="button" class="cb-button-secondary rounded-2xl px-4 py-3 text-sm" data-location-button>
                                <span class="material-symbols-outlined text-[20px]">my_location</span>
                                Usar mi ubicación
                            </button>
                        </div>

                        <p class="hidden text-sm leading-6 text-(--cb-muted)" data-location-status></p>
                    </fieldset>

                    <fieldset class="space-y-6 border-t border-[rgba(222,224,255,0.9)] pt-8">
                        <legend class="flex items-center gap-2 text-base font-semibold text-(--cb-text)">
                            <span class="material-symbols-outlined text-[20px] text-(--cb-primary)">photo_camera</span>
                            Imagen del emprendimiento
                        </legend>

                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="logo" class="mb-2 block text-sm font-semibold text-(--cb-text)">Logo</label>
                                <input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp" class="cb-input file:mr-4 file:rounded-full file:border-0 file:bg-(--cb-primary-soft) file:px-4 file:py-2 file:text-sm file:font-semibold file:text-(--cb-primary)" data-image-input data-preview-target="logo-preview">
                                <div id="logo-preview" class="mt-3 hidden h-24 w-24 overflow-hidden rounded-2xl border border-[rgba(222,224,255,0.95)] bg-(--cb-surface-soft)" data-image-preview>
                                    <img src="" alt="Vista previa del logo" class="h-full w-full object-cover" data-preview-image>
                                </div>
                            </div>

                            <div>
                                <label for="cover_image" class="mb-2 block text-sm font-semibold text-(--cb-text)">Imagen de portada</label>
                                <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp" class="cb-input file:mr-4 file:rounded-full file:border-0 file:bg-(--cb-secondary-soft) file:px-4 file:py-2 file:text-sm file:font-semibold file:text-(--cb-secondary)" data-image-input data-preview-target="cover-preview">
                                <div id="cover-preview" class="mt-3 hidden h-48 overflow-hidden rounded-2xl border border-[rgba(222,224,255,0.95)] bg-(--cb-surface-soft) sm:h-56" data-image-preview>
                                    <img src="" alt="Vista previa de la portada" class="h-full w-full object-cover" data-preview-image>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <button type="submit" class="cb-button-primary w-full rounded-lg py-4 text-base">
                        Registrar mi Emprendimiento
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>

                    <p class="text-center text-xs leading-6 text-(--cb-muted)">
                        Al registrarte, aceptás nuestros Términos y Condiciones.
                    </p>
                </form>
            </div>
        </div>
    </section>
@endsection
