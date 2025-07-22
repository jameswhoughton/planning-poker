<?php

namespace Tests\Feature;

use App\Events\PlayerCreatedUpdated;
use App\Models\Room;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class PatchPlayerEndpointTest extends TestCase
{
    use RefreshMongoDatabase;

    const ENDPOINT = '/room/%s/player/%s';

    public function test_return_forbidden_if_playerId_does_not_match_cookie(): void
    {
        $room = Room::factory()->create();

        $room->players()->create();

        $this->patch(sprintf(self::ENDPOINT, $room->uuid, $room->players[0]->id), ['name' => 'John Smith'])
            ->assertForbidden();
    }

    public static function validationCases(): array
    {
        return [
            'empty payload' => [
                'payload' => [],
                'expectedErrors' => ['name'],
            ],
            'empty name' => [
                'payload' => ['name' => ''],
                'expectedErrors' => ['name'],
            ],
            'long name' => [
                'payload' => ['name' => Str::repeat('a', 101)],
                'expectedErrors' => ['name'],
            ],
        ];
    }

    /**
     * @param $payload array<string, array>
     * @param $expectedErrors array<string>
     **/
    #[DataProvider('validationCases')]
    public function test_update_name_validation(array $payload, array $expectedErrors): void
    {
        $room = Room::factory()->create();

        $player = $room->players()->create();

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])
            ->patch(sprintf(self::ENDPOINT, $room->uuid, $player->id), $payload)
            ->assertInvalid($expectedErrors);
    }

    public function test_player_can_update_their_name(): void
    {
        Event::fake();

        $room = Room::factory()->create();

        $player = $room->players()->create(['name' => 'Jon Smit']);

        $payload = ['name' => 'John Smith'];

        $this->withSession([
            'roomId' => $room->id,
            'playerId' => $player->id,
        ])
            ->patch(sprintf(self::ENDPOINT, $room->uuid, $player->id), $payload)
            ->assertRedirectToRoute('room:view', ['room' => $room->uuid]);

        $room->refresh();

        $this->assertCount(1, $room->players);

        $this->assertEquals($player->id, $room->players[0]->id);
        $this->assertEquals($payload['name'], $room->players[0]->name);

        Event::assertDispatched(PlayerCreatedUpdated::class, 1);
    }
}
