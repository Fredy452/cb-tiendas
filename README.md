# CB Tiendas — Repositorio de Emprendedores de Coronel Bogado

Aplicacion web educativa desarrollada como proyecto de extension universitaria (FaCyT) bajo la metodologia Scrum. Permite registrar, clasificar y visibilizar emprendimientos locales de Coronel Bogado mediante software libre.

> **Contexto academico:** este repositorio es mantenido por estudiantes como parte de la materia Practicas de Ingenieria de Software. Toda contribucion debe seguir las reglas descritas en [CONTRIBUTING.md](CONTRIBUTING.md).

## Stack

| Capa | Tecnologia |
|------|-----------|
| Backend | Laravel 13 · PHP 8.3+ |
| Frontend | Blade · Tailwind CSS 4 · Vite 8 |
| Base de datos | MySQL 8.4 (Docker) / SQLite (local) |
| Contenedores | Laravel Sail · Docker Compose |

## Inicio rapido

### Con Docker (recomendado)

```bash
# 1. Instalar dependencias PHP dentro del contenedor
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" \
  -w /var/www/html \
  laravelsail/php84-composer:latest \
  composer install

# 2. Levantar los servicios
docker compose up -d

# 3. Setup inicial (key, migraciones, assets)
./vendor/bin/sail composer run setup
```

Puertos expuestos (ver `compose.yaml`): **App 3002** · **Vite 5173** · **MySQL 3307**.

### Sin Docker

```bash
composer run setup   # instala deps, genera key, migra BD, compila assets
composer run dev     # servidor + queue + logs + Vite en paralelo
```

### Tests

```bash
composer run test
```

## Documentacion del proyecto

| Documento | Ubicacion |
|-----------|-----------|
| Descripcion y alcance | [`.github/project/description.md`](.github/project/description.md) |
| Roles de usuario | [`.github/project/roles-description.md`](.github/project/roles-description.md) |
| Guia de contribucion | [CONTRIBUTING.md](CONTRIBUTING.md) |
| Registro de cambios | [CHANGELOG.md](CHANGELOG.md) |
| Guia inicial (Notion) | [Abrir en Notion](https://apricot-temper-c0d.notion.site/Gu-a-Inicial-343187172b308025ba26f25148a88d79?source=copy_link) |

## Licencia

Software libre bajo la licencia [MIT](https://opensource.org/licenses/MIT).
