<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'cin',
        'date_naissance',
        'sexe',
        'adresse',
        'groupe_sanguin',
        'contact_urgence_nom',
        'contact_urgence_telephone',
    ];

    // Un patient appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un patient a un seul dossier médical
    public function dossierMedical()
    {
        return $this->hasOne(DossierMedical::class);
    }

    // Un patient a plusieurs rendez-vous
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    // Un patient a plusieurs consultations
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    // Un patient a plusieurs documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // Un patient a plusieurs conversations IA
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}