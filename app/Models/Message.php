<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
