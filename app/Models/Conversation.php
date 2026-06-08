<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'patient_id',
        'titre',
        'resume',
        'specialite_choisie_id',
    ];

    // Une conversation appartient à un patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Une conversation a plusieurs messages
    public function messages()
    {
        return $this->hasMany(MessageConversation::class);
    }

    // Une conversation a plusieurs spécialités proposées
    public function specialitesProposees()
    {
        return $this->hasMany(SpecialiteProposee::class);
    }

    // La spécialité choisie par le patient
    public function specialiteChoisie()
    {
        return $this->belongsTo(Specialite::class, 'specialite_choisie_id');
    }

    // Les symptômes détectés dans la conversation (relation N-N)
    public function symptomes()
    {
        return $this->belongsToMany(Symptome::class, 'conversation_symptome');
    }
}