<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Liste des patients ayant consulté ce médecin
    public function index(Request $request)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $query = Patient::with('user')
            ->whereHas('consultations', function ($q) use ($medecin) {
                $q->where('medecin_id', $medecin->id);
            });

        $query->when($request->search, function ($q) use ($request) {
            return $q->whereHas('user', function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%');
            })->orWhere('cin', 'like', '%' . $request->search . '%');
        });

        $patients = $query->paginate(10);

        return view('medecin.patients.index', compact('patients'));
    }

    // Afficher détails d'un patient (non utilisé mais requis par resource)
    public function show($id)
    {
        return $this->dossier($id);
    }

    // Dossier médical d'un patient
    public function dossier($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $patient = Patient::with([
            'user',
            'dossierMedical',
            'consultations' => function ($q) use ($medecin) {
                $q->where('medecin_id', $medecin->id)
                  ->orderBy('date_consultation', 'desc');
            },
            'consultations.documents',
        ])->findOrFail($id);

        return view('medecin.patients.dossier', compact('patient'));
    }
}