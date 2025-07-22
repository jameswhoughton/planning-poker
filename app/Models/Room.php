<?php

namespace App\Models;

use App\Models\Player;
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
    ];

    protected $primaryKey = 'uuid';

    public function players(): EmbedsMany
    {
        return $this->embedsMany(Player::class);
    }
}
