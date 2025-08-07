<?php

namespace App\Http\Controllers;

use App\Events\RoomDeleted;
use App\Events\RoomUpdated;
use App\Http\Requests\PatchRoomRequest;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
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
            'players' => $room->players,
            'playerId' => session('playerId', null),
        ]);
    }

    public function update(PatchRoomRequest $request, Room $room): HttpResponse
    {
        $player = $room->players()->where('_id', session('playerId', null))->first();

        if ($player === null) {
            abort(403);
        }

        if ($request->has('showScores')) {
            $room->showScores = $request->validated('showScores');
        }

        $room->save();

        broadcast(new RoomUpdated($room));

        return response()->noContent();
    }

    public function reset(Room $room): HttpResponse
    {
        $player = $room->players()->where('_id', session('playerId', null))->first();

        if ($player === null) {
            abort(403);
        }

        // Can't use ->update(...) on the instance here because the raw mongoDB selector $[] is not supported.
        Room::where('_id', $room->id)->update(['players.$[].score' => null]);

        $room->refresh();

        broadcast(new RoomUpdated($room, 'scores have been reset'));

        return response()->noContent();
    }

    public function destroy(Request $request, Room $room): RedirectResponse
    {
        if (session('roomId', null) !== $room->id) {
            abort(403);
        }

        broadcast(new RoomDeleted($room));
        $room->delete();

        $request->session()->forget('roomId');
        $request->session()->forget('playerId');

        return to_route('home');
    }
}
