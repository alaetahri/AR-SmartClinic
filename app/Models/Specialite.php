<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialite extends Model
{
    protected $fillable = [
        'nom',
        'description',
    ];

    // Une spécialité a plusieurs médecins
    public function medecins()
    {
        return $this->hasMany(Medecin::class);
    }

    // Une spécialité a plusieurs symptômes
    public function symptomes()
    {
        return $this->hasMany(Symptome::class);
    }

    // Une spécialité a été proposée dans plusieurs conversations
    public function specialitesProposees()
    {
        return $this->hasMany(SpecialiteProposee::class);
    }

    // Une spécialité a été choisie dans plusieurs conversations
    public function conversationsChoisies()
    {
        return $this->hasMany(Conversation::class, 'specialite_choisie_id');
    }
}