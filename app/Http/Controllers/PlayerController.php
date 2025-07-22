<?php

namespace App\Http\Controllers;

use App\Events\PlayerCreatedUpdated;
use App\Http\Requests\PlayerRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class PlayerController extends Controller
{
    public function create(PlayerRequest $request, Room $room): RedirectResponse
    {
        $newPlayer = $room->players()->create(['name' => $request->name]);

        session(['playerId' => $newPlayer->id]);

        broadcast(new PlayerCreatedUpdated($room->id, $newPlayer));

        return to_route('room:view', ['room' => $room]);
    }

    public function update(PlayerRequest $request, Room $room, string $playerId): Response|RedirectResponse
    {
        $player = $room->players()->where('_id', $playerId)->first();

        if (! $player) {
            abort(404);
        }

        if (session('playerId', null) !== $player->id) {
            abort(403);
        }

        $player->name = $request->name;
        $player->save();

        broadcast(new PlayerCreatedUpdated($room->id, $player));

        return to_route('room:view', ['room' => $room]);
    }
}
