<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Indisponibilite;
use App\Models\Specialite;
use App\Models\Notification;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    // Liste des RDV du patient
    public function index(Request $request)
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $query = RendezVous::where('patient_id', $patient->id)
            ->with(['medecin.user', 'medecin.specialite', 'consultation']);

        $query->when($request->statut, function ($q) use ($request) {
            return $q->where('statut', $request->statut);
        });

        $query->when($request->date, function ($q) use ($request) {
            return $q->where('date_rendez_vous', $request->date);
        });

        // Afficher les plus récents en premier
        $rendezVous = $query->orderBy('date_rendez_vous', 'desc')
                            ->orderBy('heure_debut')
                            ->paginate(10);

        return view('patient.rendez-vous.index', compact('rendezVous'));
    }

    // Formulaire prise de RDV
    public function create(Request $request)
{
    $specialites = Specialite::all();

    // Filtrer médecins par spécialité si sélectionnée
    if ($request->specialite_id) {
        $medecins = Medecin::with(['user', 'specialite'])
            ->where('specialite_id', $request->specialite_id)
            ->get();
        $specialiteSelectionnee = Specialite::find($request->specialite_id);
    } else {
        $medecins = Medecin::with(['user', 'specialite'])->get();
        $specialiteSelectionnee = null;
    }

    return view('patient.rendez-vous.create', compact(
        'specialites', 'medecins', 'specialiteSelectionnee'
    ));
}

    // Sauvegarder RDV
    public function store(Request $request)
    {
        $request->validate([
            'medecin_id'       => 'required|exists:medecins,id',
            'date_rendez_vous' => 'required|date|after_or_equal:today',
            'heure_debut'      => 'required',
            'motif'            => 'nullable|string|max:255',
        ], [
            'medecin_id.required'             => 'Veuillez choisir un médecin.',
            'date_rendez_vous.required'       => 'La date est obligatoire.',
            'date_rendez_vous.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'heure_debut.required'            => 'L\'heure est obligatoire.',
        ]);

        $patient    = Patient::where('user_id', session('user_id'))->firstOrFail();
        $heureDebut = $request->heure_debut;
        $heureFin   = date('H:i', strtotime($heureDebut . ' +30 minutes'));

        // Vérifier créneau disponible
        $creneauPris = RendezVous::where('medecin_id', $request->medecin_id)
            ->where('date_rendez_vous', $request->date_rendez_vous)
            ->where('statut', '!=', 'annule')
            ->where('heure_debut', $heureDebut)
            ->exists();

        if ($creneauPris) {
            return redirect()->back()->withInput()
                             ->with('error', 'Ce créneau est déjà pris. Choisissez un autre horaire.');
        }

        // Vérifier indisponibilité médecin
        $indisponible = Indisponibilite::where('medecin_id', $request->medecin_id)
            ->where('date_debut', '<=', $request->date_rendez_vous . ' ' . $heureDebut)
            ->where('date_fin', '>=', $request->date_rendez_vous . ' ' . $heureDebut)
            ->exists();

        if ($indisponible) {
            return redirect()->back()->withInput()
                             ->with('error', 'Le médecin est indisponible à ce créneau.');
        }

        $rendezVous = RendezVous::create([
            'patient_id'       => $patient->id,
            'medecin_id'       => $request->medecin_id,
            'date_rendez_vous' => $request->date_rendez_vous,
            'heure_debut'      => $heureDebut,
            'heure_fin'        => $heureFin,
            'motif'            => $request->motif,
            'statut'           => 'en_attente',
        ]);

        // Notification de confirmation
        Notification::create([
            'user_id'    => session('user_id'),
            'titre'      => 'Rendez-vous créé',
            'message'    => 'Votre rendez-vous a été créé pour le ' . $rendezVous->date_rendez_vous . ' à ' . $rendezVous->heure_debut . '. En attente de confirmation.',
            'type'       => 'rendez_vous',
            'lu'         => false,
            'date_envoi' => now(),
        ]);

        return redirect()->route('patient.rendez-vous.index')
                         ->with('success', 'Rendez-vous demandé avec succès. En attente de confirmation.');
    }

    // Détails d'un RDV
    public function show($id)
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $rendezVous = RendezVous::where('patient_id', $patient->id)
            ->with(['medecin.user', 'medecin.specialite', 'consultation.documents'])
            ->findOrFail($id);

        return view('patient.rendez-vous.show', compact('rendezVous'));
    }

    // Annuler un RDV
    public function annuler($id)
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $rendezVous = RendezVous::where('patient_id', $patient->id)
            ->whereIn('statut', ['en_attente', 'confirme'])
            ->findOrFail($id);

        $rendezVous->update(['statut' => 'annule']);

        return redirect()->route('patient.rendez-vous.index')
                         ->with('success', 'Rendez-vous annulé.');
    }
}