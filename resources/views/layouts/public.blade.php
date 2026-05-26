<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta_description', 'CB Tiendas, la plaza digital de Coronel Bogado.')">
        <title>@yield('title', 'CB Tiendas')</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen antialiased">
        <div class="flex min-h-screen flex-col">
            @include('partials.public.nav', ['variant' => trim($__env->yieldContent('nav_variant')) ?: 'full'])

            @if (session('status'))
                <div class="cb-shell pt-6">
                    <div class="cb-panel flex items-start gap-3 border-[rgba(177,240,206,0.85)] bg-[rgba(177,240,206,0.35)] px-5 py-4 text-sm leading-7 text-(--cb-primary)">
                        <span class="material-symbols-outlined mt-0.5">verified</span>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            <main class="flex-1">
                @yield('content')
            </main>

            @include('partials.public.footer')
        </div>
    </body>
</html>
