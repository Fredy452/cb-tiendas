@foreach ($stores as $store)
    @include('partials.public.store-card', ['store' => $store])
@endforeach
