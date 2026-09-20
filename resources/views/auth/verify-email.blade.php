@extends('layouts.public')

@section('title', 'Verificá tu correo | CB Tiendas')
@section('meta_description', 'Confirmá tu correo electrónico para ingresar al panel de emprendedores.')
@section('nav_variant', 'minimal')

@section('content')
    <section class="flex min-h-[calc(100dvh-5rem)] items-center bg-(--cb-surface-soft) py-12">
        <div class="cb-shell w-full">
            <div class="cb-panel mx-auto max-w-xl p-6 text-center sm:p-10">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-(--cb-primary-soft) text-(--cb-primary)">
                    <span class="material-symbols-outlined text-[32px]" aria-hidden="true">mark_email_unread</span>
                </div>
                <h1 class="cb-subheading mt-6 text-(--cb-text)">Revisá tu correo</h1>
                <p class="mt-4 leading-7 text-(--cb-muted)">
                    Enviamos un enlace de activación a <strong class="text-(--cb-text)">{{ auth()->user()->email }}</strong>. Abrilo para habilitar tu panel de tiendas.
                </p>

                <form action="{{ route('verification.send') }}" method="POST" class="mt-8">
                    @csrf
                    <button type="submit" class="cb-button-secondary min-h-12 w-full sm:w-auto">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">refresh</span>
                        Reenviar enlace
                    </button>
                </form>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="min-h-11 text-sm font-semibold text-(--cb-muted) hover:text-(--cb-primary)">Usar otra cuenta</button>
                </form>
            </div>
        </div>
    </section>
@endsection
