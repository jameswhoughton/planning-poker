<?php

namespace App\Http\Controllers;

use App\Events\PlayerCreatedUpdated;
use App\Events\PlayerDeleted;
use App\Http\Requests\PatchPlayerRequest;
use App\Http\Requests\PostPlayerRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use MongoDB\BSON\ObjectId;

class PlayerController extends Controller
{
    public function create(PostPlayerRequest $request, Room $room): RedirectResponse
    {
        $newPlayer = $room->players()->create(['name' => $request->validated('name')]);

        session(['playerId' => $newPlayer->id]);

        broadcast(new PlayerCreatedUpdated($room->id, $newPlayer));

        return to_route('room:view', ['room' => $room]);
    }

    public function update(PatchPlayerRequest $request, Room $room, string $playerId): Response
    {
        $player = $room->players()->where('_id', $playerId)->first();

        if (! $player) {
            abort(404);
        }

        if (session('playerId', null) !== $player->id) {
            abort(403);
        }

        if ($request->has('name')) {
            $player->name = $request->validated('name');
        }

        if ($request->has('score')) {
            $player->score = $request->validated('score');
        }

        $player->save();

        broadcast(new PlayerCreatedUpdated($room->id, $player));

        return response()->noContent();
    }

    public function destroy(Room $room, string $playerId): Response|RedirectResponse
    {
        $player = $room->players()->where('id', $playerId)->first();

        if (! $player) {
            abort(404);
        }

        if (session('playerId', null) !== $playerId) {
            abort(403);
        }

        $r = Room::where('_id', $room->_id)->update([
            '$pull' => [
                'players' => [
                    'id' => new ObjectId($playerId),
                ],
            ],
        ]);

        broadcast(new PlayerDeleted($room->id, $player));

        session(['playerId' => null]);

        return to_route('home');
    }
}
