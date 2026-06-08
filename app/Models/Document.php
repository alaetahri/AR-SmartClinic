<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'patient_id',
        'consultation_id',
        'type',
        'titre',
        'description',
        'fichier',
        'date_document',
    ];

    // Un document appartient à un patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Un document appartient à une consultation
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}