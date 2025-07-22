<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', RootController::class);
Route::post('/', [RoomController::class, 'create'])->middleware(['throttle:roomCreate'])->name('room:create');
Route::get('/room/{room}', [RoomController::class, 'view'])->name('room:view');
Route::post('/room/{room}/player', [PlayerController::class, 'create'])->name('player:create');
Route::patch('/room/{room}/player/{playerId}', [PlayerController::class, 'update'])->name('player:update');
