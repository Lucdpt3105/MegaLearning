<!-- .github/copilot-instructions.md - guidance for AI coding agents working on MegaLearning -->

# MegaLearning — Copilot Instructions

Purpose: quickly orient an AI coding agent to this Laravel + Vite e-learning project so it can make safe, useful changes.

Quick start (commands you'll use):
- Setup: `composer install; npm install; copy .env.example .env; php artisan key:generate; php artisan migrate --seed`
- Dev: `php artisan serve` and in another shell `npm run dev` (or `npm run dev` via `composer dev` which runs multiple services)
- Build: `npm run build` then `php artisan view:clear` (if needed)
- Tests: `composer test` or `php artisan test`

Big picture architecture (short):
- Laravel MVC backend (app/) with a thin-controller → service pattern. Business logic lives in `app/Services`.
- Frontend uses Blade templates + Tailwind + Vite (resources/css/app.css, resources/js/app.js). Admin UI assets are in `assets/`.
- Realtime chat uses Pusher/Laravel Echo + database-backed chat models (`app/Models/ChatRoom.php`, `ChatMessage.php`).
- AI integration via a service at `app/Services/AIService.php` (Google Gemini). Look in `config/services.php` and `scripts/setup-gemini.bat` for setup.

Key files & locations (examples you can open directly):
- Chat API: `app/Http/Controllers/Api/ChatApiController.php`
- Chat UI (Blade): `resources/views/chat/index.blade.php`
- Admin layout example: `resources/views/admin/layout.blade.php`
- Routes: `routes/web.php` and `routes/api.php` (API is versioned/prefixed)
- Migrations/seeders: `database/migrations/`, `database/seeders/ChatDemoSeeder.php`
- Services & integrations: `app/Services/AIService.php`, `config/services.php`, `config/broadcasting.php`
- Scripts/helpers: `scripts/` (contains `setup-gemini.bat`, `test-gemini.php`, chat test scripts)

Project-specific conventions and patterns:
- Controllers: API controllers live under `app/Http/Controllers/Api/` and return JSON; web controllers render Blade views.
- Services: external integrations and complex logic are moved to `app/Services/*` (use these instead of placing heavy logic in controllers).
- Blade layouts: master layouts live under `resources/views/layouts/` and feature folders (admin, chat, teacher) group view files.
- Naming: Models are singular PascalCase (e.g., `ChatMessage`); migrations use snake_case `create_xxx_table.php`.
- Routes: `web.php` for UI, `api.php` for machine clients; routes use middleware groups (`auth`, `role:teacher`, etc.).

External integrations to be careful with:
- Google Gemini AI: initialization and API keys are controlled by `scripts/setup-gemini.bat` and `config/services.php` — do not commit secrets.
- Pusher & Echo: `pusher/pusher-php-server` is installed; broadcasting channels defined in `routes/channels.php`.
- Spatie permissions: middleware aliases registered in `bootstrap/app.php` (`role`, `permission`). Changes to role/permission require database seeding/migrations.
- Google Drive filesystem extension: `masbug/flysystem-google-drive-ext` used for file storage.

Developer workflows and gotchas:
- Composer `scripts.setup` will run migrations with `--force` — do not run on production without review.
- If `composer install` fails on Windows, ensure PHP `zip` extension is enabled (see README troubleshooting).
- To run background listeners locally, `composer dev` uses `concurrently` to run `php artisan queue:listen`, `php artisan pail` and `npm run dev` together. On Windows use PowerShell and verify `npx` availability.
- Database: team uses migrations and seeders — always create migrations for schema changes and push them; run `php artisan migrate` after pulling.

How to change UI assets safely:
- Edit `resources/css/app.css` / `resources/js/app.js` and use `@vite([...])` in Blade templates. Admin-specific overrides appear in `assets/css/admin.css` and `assets/js/admin.js`.

Safety and tests:
- Run `php artisan test` after backend changes. Unit tests live in `tests/Unit` and feature tests in `tests/Feature`.
- Avoid changing `.env.example` secrets; use `.env` only. Do not commit `.env`.

When you see this (examples of task-specific notes):
- Adding chat behaviour → update `app/Services/AIService.php`, `ChatApiController`, `database/migrations/*chat*`, and `resources/views/chat/*`.
- Adding API endpoints → place controller under `app/Http/Controllers/Api/`; update `routes/api.php` and `tests/Feature`.

If anything is unclear or you'd like more examples (e.g., common refactor patterns, preferred response shapes, or test examples), tell me which area and I'll extend this file.
