# Soccer Aficionado

## Setup & Dev

```sh
composer run setup        # fresh install (composer install, .env, key, migrate, npm install, build)
composer run dev          # concurrently: serve + queue:listen + pail + vite
npm run build             # Vite production build
```

## Lint & Test

```sh
composer run lint         # ./vendor/bin/pint --parallel
composer run test         # pint test + php artisan test (sqlite :memory:)
php artisan test --filter="TestClass|test_method"
```

## Critical: Flux Icon Bug

The app uses `<flux:icon.home>`, `<flux:icon.fire>`, `<flux:icon.user-group>`, `<flux:icon.bolt>`, `<flux:icon.user>` etc. in `resources/views/components/bottom-nav.blade.php` (and sidebar via `icon=""` prop).

Only 4 Flux icon stubs exist: `resources/views/flux/icon/{layout-grid,chevrons-up-down,book-open-text,folder-git-2}.blade.php`. Every other `flux:icon.*` reference causes: **"Flux component [icon.X] does not exist"**.

Two approaches:
1. **Publish full Flux icon set**: `php artisan flux:publish-icons` (requires livewire/flux-pro license)
2. **Create stubs**: Add `resources/views/flux/icon/{name}/index.blade.php` per missing icon

A custom `<x-icon name="..." />` component exists (`resources/views/components/icon.blade.php`) using Material Symbols — use this as alternative.

## Architecture

- **Laravel 12** + **Livewire 4** + **Flux 2** + **Tailwind CSS v4** + **Vite**
- **Auth**: Laravel Fortify (login, 2FA, email verification)
- **DB**: SQLite default; MySQL in production; tests use `sqlite :memory:` (phpunit.xml)
- **External API**: `App\Services\FootballApiService` → TheSportsDB (30 req/min), cached
- **Queue**: Default `database` driver; `php artisan queue:listen` in dev script
- **Admin check**: `User::isAdmin()` via `role_user` pivot with `slug = 'admin'`; middleware alias `'admin'`

## Routes

| Area | Middleware | Prefix |
|------|-----------|--------|
| Public | guest/web | `/`, `/matches`, `/clubs`, `/competitions`, `/users/{username}`, `/search` |
| Authenticated | `auth,verified` | `/dashboard`, `/feed`, `/trending`, `/posts/*`, `/communities/*`, `/polls/*`, `/leaderboard` |
| Admin | `auth,admin` | `/admin/*` |

Middleware aliases in `bootstrap/app.php`: `'admin'`, `'not-banned'`. The `EnsureUserIsNotBanned` middleware runs on all web routes.

## Key Directories

| Path | Purpose |
|------|---------|
| `app/Livewire/` | Livewire components (Matches, Feed, Communities, Profile, Trending) |
| `app/Http/Controllers/` | Route controllers + `Admin/` subdirectory |
| `app/Models/` | Eloquent models (Post, Comment, Like, FootballMatch, Club, etc.) |
| `app/Services/` | `FootballApiService`, `GamificationService`, `NotificationService` |
| `resources/views/flux/` | Flux component overrides (icons, navlist) |
| `docs/` | Product/UX/design documentation |

## Conventions

- Use `composer run *` scripts as canonical entry points
- Vite + Tailwind v4 (`@tailwindcss/vite` plugin, no `tailwind.config.js`)
- Dark theme only: `<html class="dark">` in layout
- Custom CSS in `resources/css/app.css` (2400+ lines of utilities, animations, components)
- `User` model has `is_banned` flag checked globally via middleware
- All new features need corresponding Flux icon blade stubs or use `<x-icon>` instead
- Tests in `tests/Feature/` and `tests/Unit/`, sqlite in-memory
