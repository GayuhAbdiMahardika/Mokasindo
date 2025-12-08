# Mokasindo

Mokasindo is a Laravel 12 marketplace/auction platform for used vehicles (cars & motorcycles). It supports company/individual registration, listings, auctions with deposits, membership tiers (anggota vs member), Midtrans payments, Telegram notifications, and location-aware search. Below are concise run instructions for Docker (recommended) and XAMPP/Laragon.

## Environments
- For Docker: copy `.env.docker.example` to `.env` and fill secrets (APP_KEY, DB_PASSWORD if any, Midtrans/Telegram/etc.).
- For local (XAMPP/Laragon): copy `.env.example` to `.env` and fill secrets (APP_KEY, DB creds, Midtrans/Telegram/etc.).

## Run with Docker
1) Build & start: `docker compose build` then `docker compose up -d`
2) First time in the running container:
   - `docker compose exec app php artisan key:generate`
   - `docker compose exec app php artisan migrate --force`
   - `docker compose exec app php artisan storage:link`
3) App URL: http://localhost:8088 (DB on host port 3307). Update `APP_URL` in `.env` if you change ports.
4) Cloudflare Tunnel (optional): set `CLOUDFLARE_TUNNEL_TOKEN` in `.env` and bring up the stack; `cloudflared` service will expose it.

## Run locally (XAMPP/Laragon)
1) Copy `.env.example` to `.env` and set `APP_KEY` via `php artisan key:generate`.
2) Configure DB in `.env` (default: host `127.0.0.1`, port `3306`, user `root`, db `mokasindo`).
3) Install dependencies: `composer install` then `npm install`.
4) Build assets: `npm run build` (or `npm run dev` for hot reload).
5) Migrate: `php artisan migrate`.
6) Serve: `php artisan serve` (or through your web server’s document root pointing to `public/`).

## Important settings
- Deposits: `deposit_percentage`, `deposit_deadline_hours` in Admin > Settings.
- Membership: `member_monthly_price`, `member_unlimited_posting`, `anggota_weekly_post_limit` in Admin > Settings.
- Payments/Integrations: set Midtrans keys (`MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY`, etc.), Telegram bot tokens, Google Maps keys, WhatsApp webhook, Cloudflare tunnel token in `.env`.

## Core features (summary)
- Vehicle marketplace & auctions (cars/motorcycles) with deposits and reserve price support.
- Membership tiers: Anggota (weekly listing quota) vs Member (configurable unlimited), admin overrides.
- Listings: gallery, search/filter/sort by brand/category/price/location; wishlist/favorites.
- Auctions: bidding flow, deposit handling, winner selection, manual refund status for non-winners.
- User accounts: registration/login, profile management, My Ads, My Bids.
- Payments: Midtrans integration placeholders, deposit lifecycle with manual refund status.
- Notifications: Telegram bot hooks (welcome/new user), optional Cloudflare Tunnel exposure.
- Location-aware search: province/city/district/subdistrict and geocoding helpers.

## Common commands
- Clear caches: `php artisan config:clear && php artisan cache:clear`
- Run tests: `php artisan test`

## Notes
- Asset build output is consumed by both Docker and local runs; rerun `npm run build` after frontend changes for production/static serving.
- Deposit refunds for non-winners are currently status-only (manual settlement).<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
