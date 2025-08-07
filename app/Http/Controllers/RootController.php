<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RootController extends Controller
{
    private function getLastRoomId(Request $request): string
    {
        $session = $request->session();

        if (! $session->has('roomId')) {
            return '';
        }

        $room = Room::where('_id', session('roomId'))->first();

        if ($room === null) {
            $request->session()->forget('roomId');

            return '';
        }

        return $room->uuid;
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        return Inertia::render('Welcome', [
            'lastRoomUuid' => $this->getLastRoomId($request),
        ]);
    }
}
