<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado de categorias</title>
</head>
<body>
    <h1>Listado de categorias</h1>
    <ul>
        @foreach($categorias as $category)
            <li>
                {{ $category->name }} -
                {{ $category->description }}
            </li>
        @endforeach
    </ul>
</body>
</html>
