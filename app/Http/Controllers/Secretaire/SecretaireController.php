<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\RendezVous;

class SecretaireController extends Controller
{
    public function dashboard()
    {
        $totalPatients        = Patient::count();
        $rendezVousAujourdhui = RendezVous::where('date_rendez_vous', today())->count();
        $rendezVousEnAttente  = RendezVous::where('statut', 'en_attente')->count();
        $rendezVousConfirmes  = RendezVous::where('statut', 'confirme')->count();

        $derniersRendezVous = RendezVous::with(['patient.user', 'medecin.user'])
            ->whereIn('statut', ['en_attente', 'confirme'])
            ->orderBy('date_rendez_vous')
            ->orderBy('heure_debut')
            ->take(5)
            ->get();

        return view('dashboard.secretaire', compact(
            'totalPatients', 'rendezVousAujourdhui',
            'rendezVousEnAttente', 'rendezVousConfirmes', 'derniersRendezVous'
        ));
    }
}