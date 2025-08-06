<?php

namespace Tests\Feature;

use App\Events\RoomDeleted;
use App\Models\Room;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class DeleteRoomEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    const ENDPOINT = '/room/%s';

    public function test_return_forbidden_if_player_id_missing_in_cookie(): void
    {
        $room = Room::factory()->create();

        $this->followingRedirects()
            ->delete(sprintf(self::ENDPOINT, $room->uuid))
            ->assertForbidden();
    }

    public function test_delete_room_endpoint_deletes_room_and_redirects(): void
    {
        Event::fake();

        $room = Room::factory()->create()->refresh();

        $this->withSession([
            'roomId' => $room->id,
        ])
            ->delete(sprintf(self::ENDPOINT, $room->uuid))
            ->assertRedirectToRoute('home')
            ->assertSessionMissing('roomId');

        Event::assertDispatched(RoomDeleted::class, 1);
    }
}
