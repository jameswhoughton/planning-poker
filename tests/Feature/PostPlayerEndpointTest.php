<?php

namespace Tests\Feature;

use App\Events\PlayerCreatedUpdated;
use App\Models\Room;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\RefreshMongoDatabase;

class PostPlayerEndpointTest extends TestCase
{

    use RefreshMongoDatabase;

    const ENDPOINT = '/room/%s/player';

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
    public function test_create_player_validation(array $payload, array $expectedErrors): void
    {
        $room = Room::factory()->create();

        $this->post(sprintf(self::ENDPOINT, $room->uuid), $payload)
            ->assertInvalid($expectedErrors);
    }

    public function test_player_can_be_created(): void
    {
        Event::fake();

        $room = Room::factory()->create();

        $payload = ['name' => 'John Smith'];

        $this->post(sprintf(self::ENDPOINT, $room->uuid), $payload)
            ->assertRedirectToRoute('room:view', ['room' => $room->uuid]);

        $room->refresh();

        $this->assertCount(1, $room->players);

        $this->assertEquals($payload['name'], $room->players[0]->name);

        Event::assertDispatched(PlayerCreatedUpdated::class, 1);
    }
}
