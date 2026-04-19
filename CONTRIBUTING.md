# Guia de Contribucion

> **Este es un proyecto educativo.** El repositorio es mantenido por estudiantes de la materia Practicas de Ingenieria de Software (FaCyT). Seguir estas reglas es parte del aprendizaje y de la evaluacion del curso.

---

## Tabla de contenidos

1. [Requisitos previos](#1-requisitos-previos)
2. [Estrategia de ramas (Gitflow simplificado)](#2-estrategia-de-ramas-gitflow-simplificado)
3. [Flujo de trabajo paso a paso](#3-flujo-de-trabajo-paso-a-paso)
4. [Convenciones de commits (Conventional Commits)](#4-convenciones-de-commits-conventional-commits)
5. [Control de versiones (SemVer)](#5-control-de-versiones-semver)
6. [Merge Requests (MR)](#6-merge-requests-mr)
7. [Estandares de codigo](#7-estandares-de-codigo)
8. [Preguntas frecuentes](#8-preguntas-frecuentes)

---

## 1. Requisitos previos

Antes de empezar a contribuir asegurate de tener:

- Git instalado y configurado con tu nombre y correo institucional:

```bash
git config --global user.name "Tu Nombre"
git config --global user.email "tu.correo@universidad.edu"
```

- Acceso al repositorio remoto (GitLab/GitHub) con tu cuenta de estudiante.
- Entorno local funcional. Ver la seccion **Inicio rapido** del [README.md](README.md).

---

## 2. Estrategia de ramas (Gitflow simplificado)

El proyecto utiliza un Gitflow acortado con **tres ramas principales** y **ramas de trabajo** individuales.

### 2.1 Ramas principales

```
main          Produccion estable, lista para despliegue.
 ^
 |  MR
test          Validacion y QA. Se revisan los cambios antes de produccion.
 ^
 |  MR
develop       Integracion. Recibe los aportes de todas las features.
```

| Rama | Proposito | Proteccion |
|------|-----------|------------|
| `main` | Versiones estables del sistema. Lista para despliegue. | **Protegida** — no se permite push directo. Solo Merge Request aprobado. |
| `test` | Entorno de validacion y pruebas. Aqui se revisan los cambios antes de pasar a produccion. | **Protegida** — solo mediante Merge Request aprobado. |
| `develop` | Rama de integracion de desarrollo. Recibe los aportes de todas las ramas `feature/*`. | **Protegida** — solo mediante Merge Request aprobado. |

> **Importante:** Nunca hagas `git push` directo a `main`, `test` o `develop`. Todo cambio entra unicamente a traves de un Merge Request revisado y aprobado.

### 2.2 Ramas de trabajo

Cada estudiante trabaja en su propia rama de feature. La convencion de nombres es:

```
feature/<descripcion-corta>
```

**Ejemplos validos:**

```
feature/registro-emprendedor
feature/catalogo-categorias
feature/panel-administracion
feature/sistema-busqueda
feature/geolocalizacion
feature/calificacion-estrellas
feature/modulo-auditoria
```

**Ejemplos invalidos:**

```
mi-rama              # No tiene el prefijo feature/
feature/cosas        # Descripcion muy vaga
Feature/Login        # Mayusculas incorrectas
feature/arreglar bug # Espacios no permitidos
```

### 2.3 Diagrama del flujo

```
feature/registro-emprendedor ─┐
feature/catalogo-categorias ──┤
feature/panel-admin ──────────┼──► develop ──► test ──► main
feature/busqueda-filtros ─────┤       MR         MR       MR
feature/calificaciones ───────┘
```

El recorrido de cada cambio es siempre:

```
feature/* → develop → test → main
```

---

## 3. Flujo de trabajo paso a paso

### 3.1 Comenzar una nueva feature

```bash
# 1. Asegurate de estar en develop y actualizado
git checkout develop
git pull origin develop

# 2. Crea tu rama de feature
git checkout -b feature/registro-emprendedor
```

### 3.2 Trabajar en la feature

```bash
# Haz cambios en tu codigo...

# 3. Agrega los archivos modificados
git add .

# 4. Haz un commit siguiendo las convenciones (ver seccion 4)
git commit -m "feat: agregar formulario de registro de emprendedor"

# 5. Sube tu rama al repositorio remoto
git push origin feature/registro-emprendedor
```

### 3.3 Mantener tu rama actualizada

Mientras trabajas, otros companeros pueden haber integrado cambios en `develop`. Mantene tu rama al dia:

```bash
# Traer los ultimos cambios de develop
git checkout develop
git pull origin develop

# Volver a tu feature y aplicar los cambios
git checkout feature/registro-emprendedor
git merge develop
```

> Si hay conflictos, resolvelos en tu rama de feature, **nunca en develop**.

### 3.4 Crear el Merge Request

1. Subi tu rama con `git push origin feature/registro-emprendedor`.
2. Anda a la interfaz web del repositorio (GitLab/GitHub).
3. Crea un **Merge Request** (o Pull Request) desde `feature/registro-emprendedor` hacia `develop`.
4. Completa la descripcion explicando **que** cambiaste y **por que**.
5. Asigna al menos un revisor (companero o Scrum Master).
6. Espera la aprobacion antes de hacer merge.

### 3.5 Despues del merge

```bash
# Volver a develop y actualizar
git checkout develop
git pull origin develop

# Eliminar la rama local de feature (ya no se necesita)
git branch -d feature/registro-emprendedor
```

### 3.6 Promocion a test y main

La promocion de `develop` a `test` y de `test` a `main` la realiza el **Scrum Master** o un responsable designado mediante Merge Request, nunca un estudiante de forma individual.

```
develop → test    Lo hace el Scrum Master al cierre de sprint o cuando hay un incremento listo.
test    → main    Lo hace el Scrum Master despues de validar que las pruebas pasan correctamente.
```

---

## 4. Convenciones de commits (Conventional Commits)

Todos los mensajes de commit **deben** seguir el formato [Conventional Commits](https://www.conventionalcommits.org/). Un commit que no siga esta convencion sera rechazado en la revision.

### 4.1 Formato

```
<tipo>(<alcance opcional>): <descripcion corta>

<cuerpo opcional>

<pie opcional>
```

La primera linea (encabezado) es **obligatoria**. El cuerpo y el pie son opcionales.

### 4.2 Tipos permitidos

| Tipo | Cuando usarlo | Ejemplo |
|------|--------------|---------|
| `feat` | Nueva funcionalidad para el usuario | `feat: agregar listado de emprendedores por categoria` |
| `fix` | Correccion de un error | `fix: corregir validacion de email en registro` |
| `docs` | Cambios solo en documentacion | `docs: actualizar instrucciones de instalacion en README` |
| `style` | Formato, punto y coma faltante, espacios (sin cambio de logica) | `style: aplicar formato PSR-12 a TiendaController` |
| `refactor` | Cambio de codigo que no agrega funcionalidad ni corrige un error | `refactor: extraer logica de busqueda a un servicio` |
| `test` | Agregar o corregir tests | `test: agregar test de creacion de emprendedor` |
| `chore` | Tareas de mantenimiento, dependencias, configuracion | `chore: actualizar dependencias de npm` |
| `build` | Cambios en el sistema de build o dependencias externas | `build: configurar Vite para assets de produccion` |
| `ci` | Cambios en la configuracion de integracion continua | `ci: agregar pipeline de pruebas automaticas` |
| `perf` | Mejora de rendimiento | `perf: optimizar consulta de emprendedores con eager loading` |
| `revert` | Revertir un commit anterior | `revert: revertir feat de geolocalizacion` |

### 4.3 Reglas del mensaje

1. **Usar imperativo en espanol:** "agregar", "corregir", "eliminar" (no "agregado", "corregido").
2. **Primera linea de maximo 72 caracteres.**
3. **No terminar la primera linea con punto.**
4. **Escribir en minusculas** (excepto nombres propios o siglas).
5. **Ser especifico:** describir *que* cambia, no como.

### 4.4 Ejemplos completos

**Commit simple:**

```
feat: agregar formulario de registro de emprendedor
```

**Commit con alcance:**

```
fix(auth): corregir redireccion despues del login
```

**Commit con cuerpo explicativo:**

```
refactor(models): migrar Tienda a atributos PHP 8

Se reemplaza la propiedad $fillable por el atributo #[Fillable]
y $casts por el metodo casts() para mantener consistencia
con el patron usado en User.php.
```

**Commit con breaking change:**

```
feat(api)!: cambiar estructura de respuesta del endpoint de tiendas

BREAKING CHANGE: el campo "shop_name" ahora se llama "name"
en la respuesta JSON.
```

### 4.5 Que NO hacer

```
# Demasiado vago
git commit -m "cambios"
git commit -m "fix bug"
git commit -m "update"
git commit -m "wip"

# Sin tipo
git commit -m "agregar boton de busqueda"

# Tipo incorrecto
git commit -m "feature: agregar login"     # Correcto: feat
git commit -m "fixed: error en registro"   # Correcto: fix
```

---

## 5. Control de versiones (SemVer)

El proyecto sigue [Semantic Versioning 2.0.0](https://semver.org/lang/es/) en el formato:

```
MAJOR.MINOR.PATCH
```

### 5.1 Significado de cada numero

| Componente | Cuando se incrementa | Ejemplo |
|-----------|---------------------|---------|
| **MAJOR** | Cambios incompatibles con versiones anteriores (breaking changes). Cambios que rompen funcionalidad existente. | `1.0.0` → `2.0.0` |
| **MINOR** | Se agrega funcionalidad nueva **compatible** con lo anterior. | `1.0.0` → `1.1.0` |
| **PATCH** | Correcciones de errores que no cambian la funcionalidad. | `1.0.0` → `1.0.1` |

### 5.2 Reglas de versionado

1. La version inicial de desarrollo es `0.1.0`.
2. Mientras la version sea `0.x.x`, la API y las funcionalidades pueden cambiar libremente (fase de desarrollo activo).
3. La primera version estable sera `1.0.0` y se publica cuando el sistema tenga las funcionalidades base completas.
4. Al incrementar MAJOR, MINOR y PATCH se reinician a cero. Al incrementar MINOR, PATCH se reinicia a cero.

```
0.1.0   Primera feature funcional (listado basico de emprendedores)
0.2.0   Agregar registro de emprendedor
0.3.0   Agregar panel de administracion
0.3.1   Corregir error en validacion de registro
0.4.0   Agregar sistema de busqueda y filtros
...
1.0.0   Primera version estable con todas las features del alcance
1.0.1   Correccion de errores post-lanzamiento
1.1.0   Agregar geolocalizacion (feature nueva compatible)
2.0.0   Reestructuracion de base de datos (breaking change)
```

### 5.3 Como etiquetar una version

Las versiones se marcan con tags de Git al hacer la promocion a `main`:

```bash
# El Scrum Master etiqueta la version despues del merge a main
git checkout main
git pull origin main
git tag -a v0.2.0 -m "v0.2.0: registro de emprendedores"
git push origin v0.2.0
```

### 5.4 Relacion entre commits y version

| Tipo de commit | Incremento de version |
|---------------|----------------------|
| `fix`, `style`, `perf`, `refactor`, `chore`, `docs`, `test`, `build`, `ci` | PATCH |
| `feat` | MINOR |
| Cualquier commit con `BREAKING CHANGE` o `!` despues del tipo | MAJOR |

---

## 6. Merge Requests (MR)

### 6.1 Checklist antes de crear un MR

- [ ] Mi rama esta actualizada con `develop` (sin conflictos).
- [ ] Todos mis commits siguen la convencion de [Conventional Commits](#4-convenciones-de-commits-conventional-commits).
- [ ] El codigo funciona localmente (`composer run dev` levanta sin errores).
- [ ] Los tests pasan (`composer run test`).
- [ ] No hay archivos innecesarios (`.env`, `node_modules/`, `vendor/`).
- [ ] La descripcion del MR explica que se hizo y por que.

### 6.2 Plantilla de descripcion del MR

```markdown
## Que se hizo
Breve descripcion de los cambios realizados.

## Por que
Justificacion o referencia al requerimiento/tarea del Sprint Backlog.

## Como probarlo
Pasos para verificar que el cambio funciona:
1. ...
2. ...
3. ...

## Capturas (si aplica)
Adjuntar screenshots de cambios visuales.
```

### 6.3 Proceso de revision

1. El autor crea el MR y asigna al menos **un revisor**.
2. El revisor revisa el codigo, deja comentarios si encuentra problemas.
3. El autor corrige y responde los comentarios.
4. Cuando el revisor aprueba, el MR puede ser mergeado.
5. **No se hace merge sin aprobacion.** Esto aplica a todas las ramas protegidas.

---

## 7. Estandares de codigo

### 7.1 PHP / Laravel

- Seguir las convenciones [PSR-12](https://www.php-fig.org/psr/psr-12/).
- Usar el estilo moderno de Eloquent con atributos PHP 8 (`#[Fillable]`, `#[Hidden]`) y el metodo `casts()`.
- Nombrar rutas con `->name('recurso.accion')`.
- Ejecutar `composer run test` antes de cada push.

### 7.2 Frontend (Blade / Tailwind)

- Las vistas van en `resources/views/`.
- Usar clases de Tailwind CSS directamente; evitar CSS custom salvo excepciones justificadas.
- Compilar assets con `npm run build` y verificar que no haya errores de Vite.

### 7.3 Base de datos

- Todo cambio de esquema se hace mediante migraciones (`php artisan make:migration`).
- Nunca modificar una migracion que ya fue aplicada en `develop`. Crear una nueva migracion para correcciones.
- Si un test toca la base de datos, usar el trait `RefreshDatabase`.

### 7.4 Archivos que NO deben subirse

Estos archivos/carpetas ya estan en `.gitignore`, pero como recordatorio:

| Archivo/Carpeta | Razon |
|----------------|-------|
| `.env` | Contiene credenciales locales |
| `vendor/` | Se regenera con `composer install` |
| `node_modules/` | Se regenera con `npm install` |
| `storage/logs/*` | Logs locales |
| `.idea/`, `.vscode/` | Configuracion personal del editor |

---

## 8. Preguntas frecuentes

### Me equivoque en el mensaje de commit, como lo corrijo?

Si el commit **aun no fue pusheado**:

```bash
git commit --amend -m "feat: mensaje corregido"
```

Si ya fue pusheado, **no uses `--force`**. Crea un nuevo commit con la correccion.

### Tengo conflictos al hacer merge con develop, que hago?

```bash
git checkout develop
git pull origin develop
git checkout feature/mi-feature
git merge develop
# Resolver conflictos en los archivos marcados
git add .
git commit -m "chore: resolver conflictos con develop"
```

### Puedo hacer push directo a develop/test/main?

**No.** Las tres ramas estan protegidas. Todo cambio entra exclusivamente mediante Merge Request aprobado.

### Cada cuanto debo hacer commit?

Haz commits pequenos y frecuentes. Cada commit debe representar un cambio logico y completo. Evita commits gigantes que mezclen multiples cambios no relacionados.

### Que pasa si mi MR es rechazado?

Es normal y parte del proceso de aprendizaje. Lee los comentarios del revisor, corrige lo necesario en tu rama de feature, haz push de los cambios y el MR se actualizara automaticamente.
