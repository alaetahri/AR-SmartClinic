<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indisponibilite extends Model
{
    protected $fillable = [
        'medecin_id',
        'date_debut',
        'date_fin',
        'motif',
    ];

    // Une indisponibilité appartient à un médecin
    public function medecin()
    {
        return $this->belongsTo(Medecin::class);
    }
}