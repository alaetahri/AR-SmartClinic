<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\RendezVous;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    // Liste des rendez-vous du médecin
    public function index(Request $request)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $query = RendezVous::where('medecin_id', $medecin->id)
            ->with('patient.user');

        // Filtrage par date
        $query->when($request->date, function ($q) use ($request) {
            return $q->where('date_rendez_vous', $request->date);
        });

        // Filtrage par statut
        $query->when($request->statut, function ($q) use ($request) {
            return $q->where('statut', $request->statut);
        });

        // Par défaut : afficher aujourd'hui en premier
        if (!$request->date && !$request->statut) {
            $query->orderByRaw("CASE WHEN date_rendez_vous = CURDATE() THEN 0 ELSE 1 END")
                  ->orderBy('date_rendez_vous')
                  ->orderBy('heure_debut');
        } else {
            $query->orderBy('date_rendez_vous')->orderBy('heure_debut');
        }

        $rendezVous = $query->paginate(10);

        return view('medecin.rendez-vous.index', compact('rendezVous', 'medecin'));
    }

    // Détails d'un rendez-vous
    public function show($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $rendezVous = RendezVous::where('medecin_id', $medecin->id)
            ->with(['patient.user', 'patient.dossierMedical', 'consultation'])
            ->findOrFail($id);

        return view('medecin.rendez-vous.show', compact('rendezVous'));
    }

    // Confirmer un rendez-vous
    public function confirmer($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $rendezVous = RendezVous::where('medecin_id', $medecin->id)->findOrFail($id);
        $rendezVous->update(['statut' => 'confirme']);

        return redirect()->back()->with('success', 'Rendez-vous confirmé.');
    }

    // Terminer un rendez-vous
    public function terminer($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $rendezVous = RendezVous::where('medecin_id', $medecin->id)->findOrFail($id);
        $rendezVous->update(['statut' => 'termine']);

        return redirect()->back()->with('success', 'Rendez-vous terminé.');
    }
}