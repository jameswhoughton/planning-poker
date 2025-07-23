<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RootController;
use Illuminate\Support\Facades\Route;

Route::get('/', RootController::class)->name('home');

// Rooms
Route::post('/', [RoomController::class, 'create'])->middleware(['throttle:roomCreate'])->name('room:create');
Route::get('/room/{room}', [RoomController::class, 'view'])->name('room:view');

// Players
Route::post('/room/{room}/player', [PlayerController::class, 'create'])->name('player:create');
Route::delete('/room/{room}/player/{playerId}', [PlayerController::class, 'destroy'])->name('player:destroy');
