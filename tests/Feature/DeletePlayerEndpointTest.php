<?php

namespace Tests\Feature;

use App\Events\PlayerDeleted;
use App\Models\Room;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class DeletePlayerEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    const ENDPOINT = '/room/%s/player/%s';

    public function test_return_forbidden_if_player_id_does_not_match_cookie(): void
    {
        $room = Room::factory()->create();

        $room->players()->create();

        $this->delete(sprintf(self::ENDPOINT, $room->uuid, $room->players[0]->id))
            ->assertForbidden();
    }

    public function test_player_can_delete_themselves(): void
    {
        Event::fake();

        $room = Room::factory()->create();

        $player = $room->players()->create();

        // Additional player who should not be deleted
        $room->players()->create();

        $resp = $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])
            ->delete(sprintf(self::ENDPOINT, $room->uuid, $player->id));

        $resp->assertSessionHas('playerId', fn ($v) => $v === null);
        $resp->assertRedirectToRoute('home');

        $room->refresh();

        $this->assertCount(1, $room->players);

        Event::assertDispatched(PlayerDeleted::class, 1);
    }

    public function test_room_deleted_when_the_final_player_is_deleted(): void
    {
        Event::fake();

        $room = Room::factory()->create();

        $player = $room->players()->create();

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])
            ->delete(sprintf(self::ENDPOINT, $room->uuid, $player->id));

        $this->assertNull(Room::where('uuid', $room->uuid)->first());
    }
}
