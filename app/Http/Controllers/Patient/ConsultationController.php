<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    // Historique des consultations du patient
    public function index()
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $consultations = Consultation::where('patient_id', $patient->id)
            ->with(['medecin.user', 'medecin.specialite', 'documents'])
            ->orderBy('date_consultation', 'desc')
            ->paginate(10);

        return view('patient.consultations', compact('consultations'));
    }

    // Détails d'une consultation terminée
    public function show($id)
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $consultation = Consultation::where('patient_id', $patient->id)
            ->with([
                'medecin.user',
                'medecin.specialite',
                'rendezVous',
                'documents',
            ])
            ->findOrFail($id);

        return view('patient.consultations.show', compact('consultation'));
    }
}