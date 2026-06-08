<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    // Liste toutes les consultations (lecture seulement)
    public function index(Request $request)
    {
        $query = Consultation::with([
            'patient.user',
            'medecin.user',
            'medecin.specialite',
        ]);

        $query->when($request->statut, function ($q) use ($request) {
            return $q->where('statut', $request->statut);
        });

        $query->when($request->date, function ($q) use ($request) {
            return $q->where('date_consultation', $request->date);
        });

        $query->when($request->search, function ($q) use ($request) {
            return $q->whereHas('patient.user', function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%');
            });
        });

        $consultations = $query->orderBy('date_consultation', 'desc')->paginate(15);

        return view('secretaire.consultations', compact('consultations'));
    }
}