<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptome extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'specialite_id',
    ];

    // Un symptôme appartient à une spécialité
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    // Un symptôme apparaît dans plusieurs conversations (relation N-N)
    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_symptome');
    }
}