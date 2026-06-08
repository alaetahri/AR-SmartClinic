<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'medecin_id',
        'rendez_vous_id',
        'date_consultation',
        'motif',
        'diagnostic',
        'notes_medecin',
        'statut',
    ];

    // Une consultation appartient à un patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Une consultation appartient à un médecin
    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }

    // Une consultation appartient à un rendez-vous
    public function rendezVous()
    {
        return $this->belongsTo(RendezVous::class);
    }

    // Une consultation a plusieurs documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}