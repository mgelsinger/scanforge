ScanForge
=========

Forge power from the world around you. ScanForge is a Laravel-powered scanning → crafting → autobattler RPG where UPC barcodes become materials, blueprints, and forged units. Players scan, craft, assemble teams, and run auto-battles with rating-based leaderboards.

## Features
- **Auth + Breeze (Blade)**
- **Scanning System:** UPC → category (Food/Tools/Electronics/Books/Health/Toys) → materials + blueprint fragments.
- **Inventory:** Materials (stackable), blueprints (fragment completion), fragments, crafted gear.
- **Crafting Stations:** Unit Foundry, Gear Forge, Essence Vault (upgrades). Transactional validation of recipes, material consumption, and outputs.
- **Units & Teams:** Forged units with stats/rarity/traits; teams up to 5 with ordered positions.
- **Auto-Battle Simulator:** Fastest-first turn loop, dmg = atk - def (min 1), death handling, battle logs.
- **Matches & Ratings:** Elo-like rating changes for teams and users; battle logs stored.
- **Leaderboard:** Cached top users by rating.
- **Policies:** Users can only access their own units/teams.

## Project Structure
- `app/Services`: `ScanService`, `CraftingService`, `BattleSimulatorService`
- `app/Http/Controllers`: Scan, Crafting, Inventory, Unit, Team, Match, Leaderboard
- `app/Jobs`: `ResolveMatchJob` (simulation + ratings + logs)
- `database/migrations`: Core game entities, matches, logs, ratings
- `database/seeders`: `RecipeSeeder`, idempotent `DatabaseSeeder`
- `resources/views`: Blade pages for scanning, crafting, inventory, teams, battles, leaderboard
- `tests/`: Unit + feature coverage for scanning, crafting, teams, matches, leaderboard, simulator

## Setup
```powershell
# ensure PHP via Scoop shims
$env:PATH="$env:USERPROFILE\scoop\shims;$env:PATH"
$env:PHPRC="$env:USERPROFILE\scoop\persist\php\cli"

composer install
npm install
npm run build        # or npm run dev
php artisan migrate --seed
php artisan serve
```

## Key Routes (after login)
- `/dashboard` (home)
- `/scan` (scan form/results)
- `/inventory`
- `/craft/unit`, `/craft/gear`, `/craft/essence`
- `/teams` (list/create/edit)
- `/matches/create` (queue), `/matches/{id}`, `/matches/{id}/log`
- `/leaderboard`

## Testing
```bash
php artisan test
```
Notes: Warnings about deprecated PDO MySQL constants may appear with PHP 8.5; tests otherwise pass (uses sqlite in-memory).

## Seeding
```bash
php artisan db:seed
```
Creates a default user if missing (`test@example.com` / `password`) and seeds base recipes.

## Notes
- Landing `/` redirects to `/dashboard`.
- Queue worker: use `php artisan queue:work` for async match resolution (or `sync` driver to resolve immediately).
