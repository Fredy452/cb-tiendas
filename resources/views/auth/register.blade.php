@extends('layouts.public')

@section('title', 'Crear cuenta de emprendedor | CB Tiendas')
@section('meta_description', 'Creá tu cuenta de emprendedor y administrá tus tiendas en CB Tiendas.')
@section('nav_variant', 'minimal')

@section('content')
    <section class="min-h-[calc(100dvh-5rem)] bg-[#F0EFFF] py-10 sm:py-16">
        <div class="cb-shell grid items-center gap-10 lg:grid-cols-[minmax(0,0.82fr)_minmax(24rem,0.72fr)] lg:gap-20">
            <div class="max-w-xl">
                <span class="cb-pill">Cuenta de emprendedor</span>
                <h1 class="cb-display mt-5 text-(--cb-text)">Hacé crecer tu presencia local.</h1>
                <p class="mt-5 text-lg leading-8 text-(--cb-muted)">
                    Creá una cuenta para registrar más de una tienda, mantener sus datos actualizados y seguir cada solicitud.
                </p>

                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined mt-0.5 text-(--cb-primary)" aria-hidden="true">store</span>
                        <p class="text-sm leading-6 text-(--cb-muted)"><strong class="text-(--cb-text)">Tus tiendas.</strong> Solo vos y los administradores podrán gestionarlas.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined mt-0.5 text-(--cb-secondary)" aria-hidden="true">mark_email_read</span>
                        <p class="text-sm leading-6 text-(--cb-muted)"><strong class="text-(--cb-text)">Correo verificado.</strong> Confirmaremos tu identidad antes de habilitar el panel.</p>
                    </div>
                </div>
            </div>

            <div class="cb-panel p-6 sm:p-8">
                <h2 class="cb-subheading text-(--cb-text)">Crear cuenta</h2>
                <p class="mt-2 text-sm leading-6 text-(--cb-muted)">Los datos del negocio se completan después, dentro del panel.</p>

                <form action="{{ route('register.store') }}" method="POST" class="mt-7 space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block font-medium text-(--cb-text)">Tu nombre</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" class="cb-input" autocomplete="name" maxlength="255" required autofocus aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                        @error('name')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block font-medium text-(--cb-text)">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="cb-input" autocomplete="email" inputmode="email" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                        @error('email')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block font-medium text-(--cb-text)">Contraseña</label>
                        <div class="relative">
                            <input id="password" name="password" type="password" class="cb-input tracking-widest" autocomplete="new-password" minlength="8" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                            <button type="button" class="password-toggle absolute inset-y-0 right-3 flex items-center text-(--cb-muted) transition hover:text-(--cb-text)" data-target="password" aria-label="Mostrar contraseña" aria-pressed="false">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block font-medium text-(--cb-text)">Confirmar contraseña</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" class="cb-input tracking-widest" autocomplete="new-password" minlength="8" required>
                            <button type="button" class="password-toggle absolute inset-y-0 right-3 flex items-center text-(--cb-muted) transition hover:text-(--cb-text)" data-target="password_confirmation" aria-label="Mostrar contraseña" aria-pressed="false">
                                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="cb-button-primary min-h-12 w-full">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">person_add</span>
                        Crear mi cuenta
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-(--cb-muted)">
                    ¿Ya tenés una cuenta?
                    <a href="{{ route('login') }}" class="font-semibold text-(--cb-primary) hover:underline">Iniciá sesión</a>
                </p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.password-toggle').forEach(function (button) {
                const input = document.getElementById(button.dataset.target);

                if (!input) {
                    return;
                }

                const icon = button.querySelector('.material-symbols-outlined');

                button.addEventListener('click', function () {
                    const isHidden = input.type === 'password';
                    input.type = isHidden ? 'text' : 'password';
                    input.classList.toggle('font-mono', !isHidden);
                    input.classList.toggle('tracking-[0.14em]', !isHidden);
                    button.setAttribute('aria-label', isHidden ? 'Ocultar contraseña' : 'Mostrar contraseña');
                    button.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
                    icon.textContent = isHidden ? 'visibility_off' : 'visibility';
                });
            });
        });
    </script>
@endpush
