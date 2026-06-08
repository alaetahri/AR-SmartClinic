<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medecin extends Model
{
    protected $fillable = [
        'user_id',
        'specialite_id',
        'numero_ordre',
        'biographie',
    ];

    // Un médecin appartient à un user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Un médecin appartient à une spécialité
    public function specialite()
    {
        return $this->belongsTo(Specialite::class);
    }

    // Un médecin a plusieurs rendez-vous
    public function rendezVous()
    {
        return $this->hasMany(RendezVous::class);
    }

    // Un médecin a plusieurs consultations
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    // Un médecin a plusieurs indisponibilités
    public function indisponibilites()
    {
        return $this->hasMany(Indisponibilite::class);
    }
}