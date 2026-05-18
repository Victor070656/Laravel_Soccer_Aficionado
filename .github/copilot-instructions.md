# Copilot instructions for Laravel_Soccer_Aficionado

Purpose: quick, repository-specific notes to help Copilot-powered sessions be effective in this repo.

1) Build, test, and lint commands

- Full setup (installs deps, env, migrate, build assets):
  - composer run setup
  - (This script: composer install; copy .env; artisan key:generate; migrate; npm install; npm run build)

- Development (serves app, queue, pail, vite) (concurrent):
  - composer run dev
  - Starts: php artisan serve, php artisan queue:listen, php artisan pail, npm run dev (via concurrently)

- PHP lint / Pint:
  - composer run lint
  - Or: ./vendor/bin/pint --parallel
  - Test mode: composer run test:lint

- Tests:
  - composer run test (runs pint tests + php artisan test)
  - Run the test runner directly: php artisan test
  - Run a single test file:
    - php artisan test tests/Feature/ExampleTest.php
  - Run a single test class or method:
    - php artisan test --filter="ExampleTest"  (filters class or method)
    - php artisan test --filter="test_method_name"
  - Using phpunit directly:
    - ./vendor/bin/phpunit --filter ExampleTest

- Frontend / assets (Vite + Tailwind):
  - npm run dev    # local Vite dev server
  - npm run build  # production build

- Notes about environment and DB setup:
  - composer scripts auto-copy .env from .env.example where appropriate.
  - After project creation the composer "post-create-project-cmd" touches database/database.sqlite and runs migrations; tests use in-memory sqlite per phpunit.xml.

2) High-level architecture (big picture)

- Framework and main libraries:
  - Laravel 12 (PHP 8.2+), Livewire 4 and Livewire Flux (livewire/flux). Frontend built with Vite + Tailwind + axios.

- Where to look for responsibilities:
  - app/ contains core application code. Sub-folders used by this project include Actions, Concerns, Http (controllers), Livewire, Models, Policies, Providers, Services.
  - resources/views/ holds Blade templates. Flux components live under resources/views/flux and Livewire Blade fragments under resources/views/livewire and resources/views/components.
  - routes/ (standard Laravel routing) and controllers in app/Http/Controllers.
  - Tests are under tests/ with Feature and Unit suites; phpunit.xml configures an in-memory sqlite environment for CI/local test runs.
  - docs/ holds product, UX and design documentation; useful for understanding feature intent/design decisions.

- Common runtime pieces to be aware of:
  - Background work uses Laravel queues; dev script runs php artisan queue:listen.
  - Laravel Pail is used (php artisan pail) — seen in dev script for logging/workers.
  - Ads, polls, clubs, matches, posts, communities are first-class domain areas; check app/Models and resources/views/{clubs,posts,polls,matches,communities} for domain flow.

3) Key conventions and repo-specific patterns

- Composer scripts are the canonical developer entry points (setup, dev, lint, test). Prefer them for consistent behavior.

- Tests and CI use sqlite in-memory (phpunit.xml) — avoid relying on a local MySQL instance when writing/running tests.

- Pint is configured with the Laravel preset (pint.json). Use composer run lint or vendor/bin/pint for formatting checks.

- Livewire + Flux usage:
  - Flux components are referenced by name in Blade (e.g., flux/icon). Missing Flux components result in the "Flux component [name] does not exist" runtime error — check resources/views/flux/<component>/index.blade.php and app/Livewire for matching class names.
  - When adding Flux components ensure both the Blade view and any backing Livewire class (if applicable) are present.

- Asset workflow:
  - Vite is used for dev and build; npm run dev runs the dev server and npm run build produces production assets.

- Database file and migrations:
  - For quick local development the composer post-create hooks create database/database.sqlite and run migrations. For CI, tests use in-memory sqlite as configured.

4) Existing AI assistant or helper files incorporated

- CLAUDE.md exists and contains a recent runtime stack trace (Flux component missing). Consider consulting this file when debugging Livewire/Flux view issues.
- .github/workflows/ exists (CI workflows); inspect them for CI specifics (lint/test steps) before changing tests or CI-related tooling.
- No other recognized AI-config files (.cursorrules, AGENTS.md, CONVENTIONS.md, .windsurfrules, etc.) were present in the repository root when this file was created.

5) Quick troubleshooting pointers (short)

- Missing Flux component errors: confirm resources/views/flux/<name>/index.blade.php exists and backing Livewire class (if used) matches naming.
- Tests failing locally? Ensure .env.testing isn’t overriding DB_DRIVER; phpunit.xml forces sqlite in-memory.
- Formatting/lint issues: run composer run lint (pint) and fix before committing.

---

If you'd like, configure MCP servers (e.g., Playwright for E2E web checks) for this web project — should one be added? (ask if you want this configured)

Summary: created .github/copilot-instructions.md with repo-specific build/test/lint commands, architecture notes, conventions, and references to CLAUDE.md and CI workflows. Ask if adjustments or additions are needed.