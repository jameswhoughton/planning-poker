<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Models\Room;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    public function create(): RedirectResponse
    {
        $room = Room::create([
            'uuid' => Str::uuid()->toString(),
        ]);

        session(['roomId' => $room->id]);

        return to_route('room:view', [
            'room' => $room,
            'playerId' => null,
        ]);
    }

    public function view(Room $room): Response
    {
        // If the roomId doesn't match the session value, the user
        // has moved rooms so update the value and reset the playerId
        if (session('roomId', null) !== $room->id) {
            session([
                'roomId' => $room->id,
                'playerId' => null,
            ]);
        }

        return Inertia::render('Room', [
            'room' => $room->toResource(),
            'playerId' => session('playerId', null),
        ]);
    }
}
