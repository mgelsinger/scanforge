ScanForge v0.1
==============

ScanForge is a Laravel 11 RPG prototype where players scan real-world UPCs to gather materials, craft forged units and gear, evolve and transmute resources, build teams, and auto-battle to climb a leaderboard. This repository captures the full v0.1 gameplay loop in a Blade-first UI.

## Feature set (v0.1)
- Authentication via Laravel Breeze (Blade).
- Starter selection flow that guarantees a first ForgedUnit and starter team.
- Scanning: UPC input → category detection → materials + blueprint fragments.
- Inventory: materials (stackable with type/rarity), blueprints, fragments, gear.
- Crafting: Unit Foundry, Gear Forge, Essence Vault upgrades.
- Evolution: Tier progression with stat boosts and material costs.
- Essence Transmuter: convert surplus materials into refined ones.
- Teams: create/edit up to 5-unit teams with positions; policies enforced.
- Battles: queued auto-battle simulator, match logs, rating change, leaderboard.
- Dashboard guidance and empty-state handling across major pages.

## Requirements
- PHP 8.2+ with sqlite extensions (pdo_sqlite, sqlite3), mbstring, openssl, curl.
- Composer
- Node/NPM (only if you need to rebuild frontend assets; shipped CSS works with Vite dev/build).

## Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
# optional: npm install && npm run dev (or build) if you want to rebuild assets
php artisan serve        # app
php artisan queue:work   # for match resolution (or use sync driver in .env)
```

## Gameplay loop
1) Register/login.
2) Choose a starter unit (auto-creates a default team).
3) Scan UPCs to earn materials and blueprint fragments.
4) Craft in the Unit Foundry / Gear Forge / Essence Vault.
5) Evolve units when requirements are met.
6) Transmute surplus materials into refined ones if needed.
7) Build or edit teams (up to 5 units, ordered).
8) Queue battles, view results and logs, climb the leaderboard.

## Testing
```bash
php artisan test
```

## Dev utilities
- Seeders provide recipes, evolutions, transmutation rules, and a default test user (`test@example.com` / `password`).
- Artisan helpers:
  - `php artisan scanforge:grant-materials {userId} {materialName} {amount}`
  - `php artisan scanforge:reset-user {userId}`

## Notes
- Running `php artisan migrate:fresh --seed` yields a database ready for the full loop once a user registers and selects a starter.
- This codebase is feature-frozen at v0.1; larger gameplay changes should be versioned as v0.2+.
