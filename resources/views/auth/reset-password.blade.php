@extends('layouts.public')

@section('title', 'Elegir nueva contraseña | CB Tiendas')
@section('meta_description', 'Elegí una nueva contraseña para tu cuenta de CB Tiendas.')
@section('nav_variant', 'minimal')

@section('content')
    <section class="min-h-[calc(100dvh-5rem)] bg-(--cb-surface-soft) py-10 sm:py-16">
        <div class="cb-shell grid items-center gap-10 lg:grid-cols-[minmax(0,0.9fr)_minmax(24rem,0.7fr)] lg:gap-20">
            <div class="order-2 max-w-xl lg:order-1">
                <span class="cb-pill">Panel de emprendedores</span>
                <h1 class="cb-display mt-5 text-(--cb-text)">Protegé tu cuenta.</h1>
                <p class="mt-5 text-lg leading-8 text-(--cb-muted)">
                    Elegí una contraseña segura para volver a administrar tus emprendimientos.
                </p>
            </div>

            <div class="cb-panel order-1 p-6 sm:p-8 lg:order-2">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-(--cb-primary-soft) text-(--cb-primary)">
                    <span class="material-symbols-outlined" aria-hidden="true">lock_reset</span>
                </div>
                <h2 class="cb-subheading mt-5 text-(--cb-text)">Crear nueva contraseña</h2>
                <p class="mt-2 text-sm leading-6 text-(--cb-muted)">Usá una contraseña segura que no hayas usado antes.</p>

                <form action="{{ route('password.update') }}" method="POST" class="mt-7 space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="mb-2 block font-medium text-(--cb-text)">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" class="cb-input" autocomplete="email" inputmode="email" required autofocus aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                        @error('email')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-2 block font-medium text-(--cb-text)">Nueva contraseña</label>
                        <input id="password" name="password" type="password" class="cb-input" autocomplete="new-password" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                        @error('password')
                            <p class="cb-field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block font-medium text-(--cb-text)">Confirmar contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="cb-input" autocomplete="new-password" required>
                    </div>

                    <button type="submit" class="cb-button-primary min-h-12 w-full">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">check</span>
                        Guardar contraseña
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-(--cb-muted)">
                    <a href="{{ route('login') }}" class="min-h-11 inline-flex items-center font-semibold text-(--cb-primary) hover:underline">Volver a iniciar sesión</a>
                </p>
            </div>
        </div>
    </section>
@endsection
