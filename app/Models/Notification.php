<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'type',
        'lu',
        'date_envoi',
    ];

    // Une notification appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}