<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Monolog\Processor\HostnameProcessor;
use Symfony\Component\HttpKernel\Fragment\FragmentUriGenerator;

class Room extends Model
{

    use HasFactory;

    public $fillable=[
        'name',
        'type',
        'admin',

    ];

    public function user():HasMany{
        return  $this->hasMany(User::class);
    }
    public function message():HasMany{
        return $this->hasMany(Message::class);
    }
    public function key():HasMany{
        return $this->hasMany(Key::class);
    }
}
