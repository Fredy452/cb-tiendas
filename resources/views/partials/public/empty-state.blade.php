@php
    $actionLabel = $actionLabel ?? null;
    $actionUrl = $actionUrl ?? null;
@endphp

<div class="cb-panel px-6 py-10 text-center sm:px-10">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-[rgba(177,240,206,0.45)] text-(--cb-primary)">
        <span class="material-symbols-outlined">search_off</span>
    </div>

    <h3 class="mt-5 text-2xl font-semibold tracking-tight text-(--cb-text)">{{ $title }}</h3>
    <p class="mx-auto mt-3 max-w-2xl leading-8 text-(--cb-muted)">{{ $description }}</p>

    @if ($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="cb-button-primary mt-6">{{ $actionLabel }}</a>
    @endif
</div>
