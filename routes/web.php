<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use App\Http\Controllers\CraftingController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
    Route::post('/scan', [ScanController::class, 'process'])->name('scan.process');
    Route::get('/scan/result', [ScanController::class, 'result'])->name('scan.result');

    Route::get('/craft/unit', [CraftingController::class, 'unitFoundry'])->name('craft.unit');
    Route::post('/craft/unit', [CraftingController::class, 'craftUnit'])->name('craft.unit.craft');

    Route::get('/craft/gear', [CraftingController::class, 'gearForge'])->name('craft.gear');
    Route::post('/craft/gear', [CraftingController::class, 'craftGear'])->name('craft.gear.craft');

    Route::get('/craft/essence', [CraftingController::class, 'essenceVault'])->name('craft.essence');
    Route::post('/craft/essence', [CraftingController::class, 'upgradeUnit'])->name('craft.essence.upgrade');

    Route::get('/units/{forgedUnit}', [UnitController::class, 'show'])->name('units.show');

    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{team}', [TeamController::class, 'update'])->name('teams.update');

    Route::get('/matches/create', [MatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [MatchController::class, 'store'])->name('matches.store');
    Route::get('/matches/{match}', [MatchController::class, 'show'])->name('matches.show');
    Route::get('/matches/{match}/log', [MatchController::class, 'log'])->name('matches.log');

    Route::get('/leaderboard', \App\Http\Controllers\LeaderboardController::class)->name('leaderboard.index');
    Route::get('/inventory', InventoryController::class)->name('inventory.index');
});

require __DIR__.'/auth.php';
