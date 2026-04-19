# Project Guidelines

## Stack And Scope

- This workspace is a Laravel 13 application on PHP 8.3+ with Blade views, Vite, and Tailwind CSS 4.
- Keep solutions aligned with the current server-rendered MVC structure. Do not introduce SPA, API-first, or extra service layers unless the task explicitly requires them.
- Web entry points live in routes/web.php and currently route to Blade views through controllers such as App\Http\Controllers\TiendaController.

## Build And Test

- Use composer run setup for first-time setup.
- Use composer run dev for local development; it starts the Laravel server, queue listener, pail, and Vite together.
- Use npm run dev or npm run build only when the task is frontend-only.
- Use composer run test to run the test suite.
- If working with Docker/Sail, use compose.yaml as the source of truth for exposed ports: app 3002, Vite 5173, MySQL 3307.

## Environment Pitfalls

- Reconcile database settings before running setup or migrations: .env.example defaults to sqlite, while compose.yaml provisions MySQL.
- Session, cache, and queue are configured to use the database in .env.example, so migrations must be in place before flows that depend on them.
- The current test example does not use RefreshDatabase. If a new test touches the database, add the proper database reset trait instead of relying on shared state.

## Project Conventions

- Follow Laravel and PSR-4 conventions under App\ unless the existing code shows a deliberate local pattern.
- Match the modern Eloquent style already used in app/Models/User.php, including PHP attributes such as #[Fillable] and #[Hidden] and a casts() method instead of a $casts property.
- Prefer named web routes and Blade views for UI work, consistent with the existing tiendas.index route and resources/views/*.blade.php files.
- Keep generated CRUD scaffolding focused and minimal; TiendaController currently contains stub methods that should only be filled in when a task needs them.

## Git Workflow

- See CONTRIBUTING.md for the full branching strategy, commit conventions, and versioning policy.
- Branches follow the flow: feature/* → develop → test → main. All three target branches are protected; changes enter only via approved Merge Requests.
- Commit messages must use Conventional Commits (feat, fix, docs, style, refactor, test, chore, build, ci, perf, revert).
- Versions follow SemVer (MAJOR.MINOR.PATCH) and are tagged on main by the Scrum Master.

## Documentation

- Do not duplicate generic Laravel documentation from README.md.
- Do not duplicate Git workflow rules already in CONTRIBUTING.md.
- Project description and roles live in .github/project/; link to them instead of restating their content.
