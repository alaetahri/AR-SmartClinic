<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RendezVous extends Model
{
    protected $table = 'rendez_vous';

    protected $fillable = [
        'patient_id',
        'medecin_id',
        'date_rendez_vous',
        'heure_debut',
        'heure_fin',
        'motif',
        'statut',
    ];

    // Un rendez-vous appartient à un patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Un rendez-vous appartient à un médecin
    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    // Un rendez-vous a au plus une consultation
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }
}