<?php

namespace Tests\Feature;

use App\Events\RoomUpdated;
use App\Models\Room;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class PatchRoomEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    const ENDPOINT = 'api/room/%s';

    public function test_return_forbidden_if_player_id_missing_in_cookie(): void
    {
        $room = Room::factory()->create();

        $room->players()->create();

        $this->patch(sprintf(self::ENDPOINT, $room->uuid))
            ->assertForbidden();
    }

    public function test_return_forbidden_if_player_id_is_not_a_member_of_the_room(): void
    {
        $room = Room::factory()->create();

        $room->players()->create();

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => 'abc',
        ])->patch(sprintf(self::ENDPOINT, $room->uuid))
            ->assertForbidden();
    }

    public static function validationCases(): array
    {
        return [
            'invalid showState' => [
                'payload' => ['showScores' => 'aaa'],
                'expectedErrors' => ['showScores'],
            ],
        ];
    }

    #[DataProvider('validationCases')]
    public function test_patch_room_validation(array $payload, array $expectedErrors): void
    {
        $room = Room::factory()->create()->refresh();

        $player = $room->players()->create();

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])->patch(sprintf(self::ENDPOINT, $room->uuid), $payload)
            ->assertInvalid($expectedErrors);
    }

    public function test_can_update_room(): void
    {
        Event::fake();

        $room = Room::factory([
            'showScores' => false,
        ])->create()->refresh();

        $player = $room->players()->create();

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])->patch(sprintf(self::ENDPOINT, $room->uuid), ['showScores' => true])
            ->assertNoContent();

        Event::assertDispatched(RoomUpdated::class, 1);
    }
}
