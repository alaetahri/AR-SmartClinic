<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\User;
use App\Models\Specialite;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPatients      = Patient::count();
        $totalMedecins      = Medecin::count();
        $totalSecretaires   = User::where('role', 'secretaire')->count();
        $totalSpecialites   = Specialite::count();

        $totalRendezVous        = RendezVous::count();
        $rendezVousEnAttente    = RendezVous::where('statut', 'en_attente')->count();
        $rendezVousConfirmes    = RendezVous::where('statut', 'confirme')->count();
        $rendezVousAnnules      = RendezVous::where('statut', 'annule')->count();
        $rendezVousTermines     = RendezVous::where('statut', 'termine')->count();
        $rendezVousAujourdhui   = RendezVous::where('date_rendez_vous', now()->toDateString())->count();

        $totalConsultations     = Consultation::count();
        $consultationsEnCours   = Consultation::where('statut', 'en_cours')->count();
        $consultationsTerminees = Consultation::where('statut', 'terminee')->count();

        $derniersRendezVous = RendezVous::with(['patient.user', 'medecin.user'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fix : withCount avec date correcte et statuts actifs
        $medecins = Medecin::with(['user', 'specialite'])
            ->withCount(['rendezVous as rdv_aujourdhui' => function ($q) {
                $q->where('date_rendez_vous', now()->toDateString())
                  ->whereIn('statut', ['en_attente', 'confirme']);
            }])
            ->orderBy('rdv_aujourdhui', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'totalPatients', 'totalMedecins', 'totalSecretaires',
            'totalSpecialites', 'totalRendezVous', 'rendezVousEnAttente',
            'rendezVousConfirmes', 'rendezVousAnnules', 'rendezVousTermines',
            'rendezVousAujourdhui', 'totalConsultations', 'consultationsEnCours',
            'consultationsTerminees', 'derniersRendezVous', 'medecins'
        ));
    }
}