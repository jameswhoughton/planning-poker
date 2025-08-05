<?php

namespace Routes;

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

Route::patch('/room/{room}', [RoomController::class, 'update'])->name('room:update');
Route::put('/room/{room}/reset', [RoomController::class, 'reset'])->name('room:reset');
Route::patch('/room/{room}/player/{playerId}', [PlayerController::class, 'update'])->name('player:update');
