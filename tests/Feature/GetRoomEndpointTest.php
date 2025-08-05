<?php

namespace Tests\Feature;

use App\Models\Room;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class GetRoomEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    public function test_endpoint_returns_room_data(): void
    {
        $room = Room::factory()->create();

        $room->players()->create(['name' => 'John']);

        $resp = $this->get('/room/'.$room->uuid);

        $resp->assertOk()
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Room')
                    ->where('room.uuid', $room->uuid)
                    ->where('room.showScores', false)
                    ->has('playerId')
                    ->where('players.0', [
                        'id' => $room->players[0]->id,
                        'name' => 'John',
                        'score' => null,
                    ])
            );
    }

    public function test_should_return_404_if_room_does_not_exist(): void
    {
        $this->get('/room/aaa')->assertNotFound();
    }

    public function test_should_reset_player_id_when_visiting_a_new_room(): void
    {
        $room = Room::factory()->create();

        $room->players()->create(['name' => 'John']);

        $resp = $this->withSession([
            'roomId' => 'AAA',
            'playerId' => '111',
        ])->get('/room/'.$room->uuid);

        $resp->assertOk();

        $resp->assertSessionHas('playerId', fn ($v) => $v === null);
        $resp->assertSessionHas('roomId', $room->id);
    }
}
