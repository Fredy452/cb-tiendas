# Changelog

Todos los cambios relevantes de este proyecto seran documentados en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/)
y el proyecto sigue [Semantic Versioning](https://semver.org/lang/es/).

## [Sin publicar]

### Agregado
- Estructura inicial del proyecto Laravel 13 con Blade, Vite y Tailwind CSS 4.
- Vista de bienvenida (`welcome.blade.php`).
- Vista de listado de tiendas (`tienda.blade.php`).
- Controlador `TiendaController` con stubs CRUD.
- Ruta `GET /tiendas` con nombre `tiendas.index`.
- Configuracion de Docker Compose con MySQL 8.4 y Laravel Sail.
- Documentacion: README.md, CONTRIBUTING.md, descripcion y roles del proyecto.

## [0.1.0] - 2026-04-19

### Agregado
- Scaffolding inicial de Laravel 13.
- Modelo `User` con atributos PHP 8 (`#[Fillable]`, `#[Hidden]`).
- Migraciones base: users, cache, jobs.
- Configuracion de entorno (`.env.example`).
- Suite de tests con PHPUnit (Feature + Unit).
