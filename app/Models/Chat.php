<?php

namespace App\Models;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_group' => 'boolean',  // Konvertiere is_group zu boolean
    ];

    function messages()
    {
        return $this->hasMany(Message::class);
    }

    function users()
    {
        // return $this->belongsTo(User::class, 'creator_id');
        // return $this->belongsToMany(User::class)->withPivot('is_admin');
        return $this->belongsToMany(User::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }
}
