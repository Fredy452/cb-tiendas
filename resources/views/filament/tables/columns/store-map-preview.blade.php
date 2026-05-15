@php
    $state = $getState();

    if (filled($state['latitude'] ?? null) && filled($state['longitude'] ?? null)) {
        $latitude = (float) $state['latitude'];
        $longitude = (float) $state['longitude'];
        $delta = 0.0035;
        $bbox = implode(',', [
            $longitude - $delta,
            $latitude - $delta,
            $longitude + $delta,
            $latitude + $delta,
        ]);

        $embedUrl = sprintf(
            'https://www.openstreetmap.org/export/embed.html?bbox=%s&layer=mapnik&marker=%s,%s',
            urlencode($bbox),
            $latitude,
            $longitude,
        );
    }
@endphp

@if (filled($state))
    <details class="rounded-xl border border-gray-200 bg-white/80 p-2 shadow-sm">
        <summary class="cursor-pointer text-sm font-medium text-gray-700">
            Ver mapa
        </summary>

        <div class="mt-3 overflow-hidden rounded-lg border border-gray-200">
            <iframe
                src="{{ $embedUrl }}"
                class="h-36 w-full"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Mapa de {{ $state['name'] ?? 'la tienda' }}"
            ></iframe>
        </div>
    </details>
@else
    <div class="rounded-xl border border-dashed border-gray-200 px-3 py-2 text-sm text-gray-500">
        Sin ubicación cargada
    </div>
@endif
