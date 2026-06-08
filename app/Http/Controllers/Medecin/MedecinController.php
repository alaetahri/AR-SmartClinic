<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Consultation;

class MedecinController extends Controller
{
    public function dashboard()
    {
        $medecin = Medecin::with('specialite')
            ->where('user_id', session('user_id'))
            ->firstOrFail();

        $rendezVousAujourdhui = RendezVous::where('medecin_id', $medecin->id)
            ->where('date_rendez_vous', today())
            ->whereIn('statut', ['confirme', 'en_attente'])
            ->orderBy('heure_debut')
            ->with('patient.user')
            ->get();

        $rendezVousEnAttente = RendezVous::where('medecin_id', $medecin->id)
            ->where('statut', 'en_attente')->count();

        $totalConsultations = Consultation::where('medecin_id', $medecin->id)
            ->where('statut', 'terminee')->count();

        $consultationEnCours = Consultation::where('medecin_id', $medecin->id)
            ->where('statut', 'en_cours')
            ->with('patient.user')
            ->first();

        $prochainsRendezVous = RendezVous::where('medecin_id', $medecin->id)
            ->where('date_rendez_vous', '>=', today())
            ->where('statut', 'confirme')
            ->orderBy('date_rendez_vous')
            ->orderBy('heure_debut')
            ->with('patient.user')
            ->take(5)
            ->get();

        return view('dashboard.medecin', compact(
            'medecin', 'rendezVousAujourdhui', 'rendezVousEnAttente',
            'totalConsultations', 'consultationEnCours', 'prochainsRendezVous'
        ));
    }
}