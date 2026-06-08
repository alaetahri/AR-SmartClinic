<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialiteProposee extends Model
{
    protected $table = 'specialites_proposees';

    protected $fillable = [
        'conversation_id',
        'specialite_id',
        'score_confiance',
        'choisie',
    ];

    // Une spécialité proposée appartient à une conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Une spécialité proposée appartient à une spécialité
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }
}