@extends('layouts.public')

@section('title', 'Ingresar como emprendedor | CB Tiendas')
@section('meta_description', 'Accedé a tu cuenta para administrar tus emprendimientos en CB Tiendas.')
@section('nav_variant', 'minimal')

@section('content')
    <section class="min-h-[calc(100dvh-5rem)] bg-(--cb-surface-soft) py-10 sm:py-16">
        <div class="cb-shell grid items-center gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(24rem,0.7fr)] lg:gap-20">
            <div class="order-2 max-w-xl lg:order-1">
                <span class="cb-pill">Panel de emprendedores</span>
                <h1 class="cb-display mt-5 text-(--cb-text)">Tu negocio, siempre a mano.</h1>
                <p class="mt-5 text-lg leading-8 text-(--cb-muted)">
                    Actualizá la información de tus tiendas, revisá su estado y enviá nuevos emprendimientos desde un solo lugar.
                </p>
                <div class="mt-8 flex items-center gap-3 text-sm font-semibold text-(--cb-primary)">
                    <span class="material-symbols-outlined" aria-hidden="true">verified_user</span>
                    Acceso protegido y verificación por correo
                </div>
            </div>

            <div class="cb-panel order-1 p-6 sm:p-8 lg:order-2">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-(--cb-primary-soft) text-(--cb-primary)">
                    <span class="material-symbols-outlined" aria-hidden="true">login</span>
                </div>
                <h2 class="cb-subheading mt-5 text-(--cb-text)">Iniciar sesión</h2>
                <p class="mt-2 text-sm leading-6 text-(--cb-muted)">Ingresá con el correo asociado a tu cuenta.</p>

                <form action="{{ route('login.store') }}" method="POST" class="mt-7 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block font-medium text-(--cb-text)">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" class="cb-input" autocomplete="email" inputmode="email" required autofocus aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                        @error('email')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between gap-3">
                            <label for="password" class="font-medium text-(--cb-text)">Contraseña</label>
                            <a href="{{ route('filament.admin.auth.password-reset.request') }}" class="text-sm font-semibold text-(--cb-primary) hover:underline">¿La olvidaste?</a>
                        </div>
                        <input id="password" name="password" type="password" class="cb-input" autocomplete="current-password" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                        @error('password')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex min-h-11 cursor-pointer items-center gap-3 text-sm text-(--cb-muted)">
                        <input name="remember" type="checkbox" value="1" class="h-5 w-5 rounded border-(--cb-border) text-(--cb-primary) focus:ring-(--cb-primary)">
                        Mantener mi sesión iniciada
                    </label>

                    <button type="submit" class="cb-button-primary min-h-12 w-full">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">arrow_forward</span>
                        Ingresar
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-(--cb-muted)">
                    ¿Todavía no tenés una cuenta?
                    <a href="{{ route('register') }}" class="font-semibold text-(--cb-primary) hover:underline">Registrate</a>
                </p>
            </div>
        </div>
    </section>
@endsection
