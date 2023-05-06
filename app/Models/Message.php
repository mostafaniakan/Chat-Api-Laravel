<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    public $fillable=[
        'messages',
        'user_id',
        'room_id'
    ];
    use HasFactory;
    public function room():BelongsTo{
        return $this->belongsTo(Room::class);
    }
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
