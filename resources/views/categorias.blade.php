@extends('layouts.public')

@section('title', 'Explorar por categorías | CB Tiendas')
@section('meta_description', 'Sectores y rubros visibles dentro del directorio público de emprendedores de Coronel Bogado.')

@section('content')
    <section class="cb-shell cb-section">
        <div class="max-w-4xl">
            <p class="cb-kicker mb-4">Rutas por rubro</p>
            <h1 class="cb-heading">Explorar por categorías</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-(--cb-muted)">
                Descubrí la variedad de emprendimientos, productos y servicios que conforman nuestra comunidad. Encontrá exactamente lo que necesitás apoyando el comercio en Coronel Bogado.
            </p>
        </div>

        @if ($categories->isEmpty())
            <div class="mt-10">
                @include('partials.public.empty-state', [
                    'title' => 'Todavía no hay categorías publicadas',
                    'description' => 'En cuanto se activen nuevos rubros dentro del catálogo, aparecerán en esta sección.',
                    'actionLabel' => 'Ver tiendas',
                    'actionUrl' => route('tiendas.index'),
                ])
            </div>
        @else
            <div class="mt-10 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($categories as $category)
                    @include('partials.public.category-card', ['category' => $category, 'index' => $loop->index])
                @endforeach
            </div>
        @endif
    </section>
@endsection
