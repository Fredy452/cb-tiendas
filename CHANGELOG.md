# Changelog

Todos los cambios relevantes de este proyecto seran documentados en este archivo.

El formato se basa en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/)
y el proyecto sigue [Semantic Versioning](https://semver.org/lang/es/).

## [Sin publicar]

## [1.0.0] - 2026-05-15

### Agregado
- Panel administrativo basado en Filament 5 con autenticacion, registro y descubrimiento automatico de recursos y widgets.
- Recursos CRUD para usuarios, categorias y tiendas dentro del panel administrativo.
- Gestion de categorias con slug, descripcion, estado, orden de visualizacion e imagen.
- Gestion de tiendas con categorias multiples, descripcion enriquecida, logo, imagen de portada y geolocalizacion mediante mapa.
- Modulo centralizado de auditoria con filtros por usuario, modulo, evento y rango de fechas.
- Dashboard administrativo con widget de conteo para usuarios, tiendas y categorias.
- Integracion de roles, permisos y policies mediante Filament Shield.
- Registro de actividad para usuarios, categorias y tiendas mediante Spatie Activitylog.
- Estructura inicial del proyecto Laravel 13 con Blade, Vite y Tailwind CSS 4.
- Vista de bienvenida (`welcome.blade.php`).
- Vista de listado de tiendas (`tienda.blade.php`).
- Controlador `TiendaController` con stubs CRUD.
- Ruta `GET /tiendas` con nombre `tiendas.index`.
- Configuracion de Docker Compose con MySQL 8.4 y Laravel Sail.
- Documentacion: README.md, CONTRIBUTING.md, descripcion y roles del proyecto.

### Cambiado
- Presentacion del panel administrativo para tiendas y categorias con tablas mas visuales, badges, imagenes y vistas detalladas.
- Visualizacion de cambios en auditoria usando los valores anteriores y nuevos almacenados en attribute_changes.

### Seguridad
- Control de acceso del panel administrativo basado en roles y permisos.

### Infraestructura
- Nuevas migraciones para tablas de permisos, categorias, tiendas, relacion category_store y activity_log.
- Incorporacion de dependencias para permisos, geolocalizacion y auditoria: Filament Shield, Filament Pinpoint, Spatie Activitylog y Filament Activity Log.

## [0.1.0] - 2026-04-19

### Agregado
- Scaffolding inicial de Laravel 13.
- Modelo `User` con atributos PHP 8 (`#[Fillable]`, `#[Hidden]`).
- Migraciones base: users, cache, jobs.
- Configuracion de entorno (`.env.example`).
- Suite de tests con PHPUnit (Feature + Unit).
