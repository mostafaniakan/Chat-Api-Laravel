<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Key extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'room_id'
    ];

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function room(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
