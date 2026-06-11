<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $configuredSiteName = (string) config('app.name', 'CB Tiendas');
            $siteName = $configuredSiteName === 'Laravel' ? 'CB Tiendas' : $configuredSiteName;
            $defaultDescription = 'CB Tiendas, La tienda digital de Coronel Bogado para descubrir, apoyar y registrar emprendimientos locales.';
            $rawAppUrl = rtrim((string) config('app.url', ''), '/') ?: url('/');
            $appUrl = Illuminate\Support\Str::startsWith($rawAppUrl, ['http://', 'https://']) ? $rawAppUrl : 'http://' . ltrim($rawAppUrl, '/');
            $currentPath = request()->path();
            $currentUrl = $appUrl . ($currentPath === '/' ? '' : '/' . ltrim($currentPath, '/'));
            $defaultImage = $appUrl . '/img/placeholders/store_placeholder.jpg';

            $normalizeUrl = function (?string $url) use ($appUrl): string {
                $url = trim((string) $url);

                if ($url === '') {
                    return '';
                }

                if (Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) {
                    return $url;
                }

                return $appUrl . '/' . ltrim($url, '/');
            };

            $metaTitle = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('title', $siteName))));
            $metaDescription = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('meta_description', $defaultDescription))));
            $canonicalUrl = $normalizeUrl($__env->yieldContent('canonical_url')) ?: $currentUrl;
            $metaImage = $normalizeUrl($__env->yieldContent('meta_image')) ?: $defaultImage;
            $metaImageAlt = trim(preg_replace('/\s+/', ' ', strip_tags($__env->yieldContent('meta_image_alt', 'Vista previa de CB Tiendas'))));
            $metaImageWidth = trim($__env->yieldContent('meta_image_width', '1200'));
            $metaImageHeight = trim($__env->yieldContent('meta_image_height', '630'));
            $metaImageType = trim($__env->yieldContent('meta_image_type', $metaImage === $defaultImage ? 'image/jpeg' : ''));
            $ogType = trim($__env->yieldContent('og_type', 'website'));
            $twitterCard = trim($__env->yieldContent('twitter_card', 'summary_large_image'));
            $schemaData = [
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => $appUrl,
                'description' => $defaultDescription,
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => $appUrl . '/tiendas?search={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ];
        @endphp
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $metaDescription }}">
        <meta name="application-name" content="{{ $siteName }}">
        <meta name="theme-color" content="#0f5238">
        <link rel="canonical" href="{{ $canonicalUrl }}">

        <meta property="og:site_name" content="{{ $siteName }}">
        <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
        <meta property="og:type" content="{{ $ogType }}">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:url" content="{{ $canonicalUrl }}">
        <meta property="og:image" content="{{ $metaImage }}">
        <meta property="og:image:alt" content="{{ $metaImageAlt }}">
        <meta property="og:image:width" content="{{ $metaImageWidth }}">
        <meta property="og:image:height" content="{{ $metaImageHeight }}">
        @if ($metaImageType !== '')
            <meta property="og:image:type" content="{{ $metaImageType }}">
        @endif

        <meta name="twitter:card" content="{{ $twitterCard }}">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">
        <meta name="twitter:image:alt" content="{{ $metaImageAlt }}">
        <title>{{ $metaTitle }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script type="application/ld+json">
            {!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
        @stack('head')
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
