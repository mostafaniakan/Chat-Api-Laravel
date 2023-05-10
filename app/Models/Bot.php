<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bot extends Model
{
    use HasFactory;
public $fillable=[
    'user_id',
    'Code',
    'Currency',
    'Sell',
    'Buy',
];
    public function user():HasMany{
        return $this->hasMany(User::class);
    }

}
