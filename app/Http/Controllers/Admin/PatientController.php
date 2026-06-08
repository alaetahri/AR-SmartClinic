<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Liste des patients ayant eu des consultations
    public function index(Request $request)
    {
        $query = Patient::with('user')
            ->whereHas('consultations');

        // Recherche par nom, prénom ou CIN
        $query->when($request->search, function ($q) use ($request) {
            return $q->whereHas('user', function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%');
            })->orWhere('cin', 'like', '%' . $request->search . '%');
        });

        $patients = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.patients.index', compact('patients'));
    }

    // Afficher détails d'un patient
    public function show($id)
    {
        $patient = Patient::with([
            'user',
            'dossierMedical',
            'consultations.medecin.user',
            'consultations.medecin.specialite',
            'rendezVous.medecin.user',
        ])->findOrFail($id);

        return view('admin.patients.show', compact('patient'));
    }
}