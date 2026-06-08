<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DossierMedical extends Model
{
    protected $table = 'dossiers_medicaux';

    protected $fillable = [
        'patient_id',
        'numero_dossier',
        'allergies',
        'maladies_chroniques',
        'antecedents_medicaux',
        'traitements_en_cours',
        'observations_generales',
        'date_ouverture',
    ];

    // Un dossier médical appartient à un patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}