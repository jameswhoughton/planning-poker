<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\EmbedsMany;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'uuid',
        'players',
    ];

    protected $attributes = [
        'players' => [],
        'showScores' => false,
    ];

    protected $primaryKey = 'uuid';

    public function players(): EmbedsMany
    {
        return $this->embedsMany(Player::class);
    }

    public function getPlayerLimitAttribute(): int
    {
        return 10;
    }
}
