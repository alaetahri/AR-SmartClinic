<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    // Afficher le dossier médical du patient connecté
    public function index()
    {
        $patient = Patient::where('user_id', session('user_id'))
            ->with(['dossierMedical', 'user'])
            ->firstOrFail();

        return view('patient.dossier', compact('patient'));
    }
}