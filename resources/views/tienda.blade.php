<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Binvenidos al listado de tiendas de {{ config('app.name') }}</title>
</head>
<body>
    <h1>Bienvenidos al listado de tiendas de {{ config('app.name') }}</h1>
    <ul>
        @foreach($stores as $store)
            <li>{{ $store->name }} - {{ $store->description }}</li>
        @endforeach
</body>
</html>
