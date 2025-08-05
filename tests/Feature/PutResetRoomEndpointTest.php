<?php

namespace Tests\Feature;

use App\Events\RoomUpdated;
use App\Models\Room;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class PutResetRoomEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    const ENDPOINT = 'api/room/%s/reset';

    public function test_return_forbidden_if_player_id_missing_in_cookie(): void
    {
        $room = Room::factory()->create();

        $room->players()->create();

        $this->put(sprintf(self::ENDPOINT, $room->uuid))
            ->assertForbidden();
    }

    public function test_return_forbidden_if_player_id_is_not_a_member_of_the_room(): void
    {
        $room = Room::factory()->create();

        $room->players()->create();

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => 'abc',
        ])->put(sprintf(self::ENDPOINT, $room->uuid))
            ->assertForbidden();
    }

    public function test_can_reset_room(): void
    {
        Event::fake();

        $room = Room::factory([
            'showScores' => false,
        ])->create()->refresh();

        $player = $room->players()->create([
            'score' => '5',
        ]);

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])->put(sprintf(self::ENDPOINT, $room->uuid))
            ->assertNoContent();

        Event::assertDispatched(function (RoomUpdated $event) {
            foreach ($event->room->players as $player) {
                if ($player->score !== null) {
                    return false;
                }
            }

            return true;
        });
    }
}
