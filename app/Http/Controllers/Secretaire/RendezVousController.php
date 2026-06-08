<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Indisponibilite;
use App\Models\Notification;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    // Liste tous les RDV du clinique
    public function index(Request $request)
    {
        $query = RendezVous::with(['patient.user', 'medecin.user', 'medecin.specialite']);

        $query->when($request->statut, function ($q) use ($request) {
            return $q->where('statut', $request->statut);
        });

        $query->when($request->date, function ($q) use ($request) {
            return $q->where('date_rendez_vous', $request->date);
        });

        $query->when($request->search, function ($q) use ($request) {
            return $q->whereHas('patient.user', function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%');
            });
        });

        $rendezVous = $query->orderBy('date_rendez_vous', 'desc')
                            ->orderBy('heure_debut')
                            ->paginate(15);

        return view('secretaire.rendez-vous.index', compact('rendezVous'));
    }

    // Formulaire création RDV
    public function create(Request $request)
{
    $medecins = Medecin::with(['user', 'specialite'])->get();

    $patientSelectionne = null;
    if ($request->cin) {
        $patientSelectionne = Patient::with('user')
            ->whereHas('user') // sécurité
            ->where('cin', strtoupper(trim($request->cin)))
            ->first();
    }

    // Compatibilité avec patient_id passé depuis patients/index
    if (!$patientSelectionne && $request->patient_id) {
        $patientSelectionne = Patient::with('user')->find($request->patient_id);
    }

    return view('secretaire.rendez-vous.create', compact('medecins', 'patientSelectionne'));
}

    // Sauvegarder RDV
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'medecin_id'       => 'required|exists:medecins,id',
            'date_rendez_vous' => 'required|date|after_or_equal:today',
            'heure_debut'      => 'required',
            'motif'            => 'nullable|string|max:255',
        ], [
            'patient_id.required'        => 'Le patient est obligatoire.',
            'medecin_id.required'        => 'Le médecin est obligatoire.',
            'date_rendez_vous.required'  => 'La date est obligatoire.',
            'date_rendez_vous.after_or_equal' => 'La date doit être aujourd\'hui ou dans le futur.',
            'heure_debut.required'       => 'L\'heure est obligatoire.',
        ]);

        // Calculer heure_fin (30 minutes après)
        $heureDebut = $request->heure_debut;
        $heureFin   = date('H:i', strtotime($heureDebut . ' +30 minutes'));

        // Vérifier si le créneau est disponible
        $creneauPris = RendezVous::where('medecin_id', $request->medecin_id)
            ->where('date_rendez_vous', $request->date_rendez_vous)
            ->where('statut', '!=', 'annule')
            ->where('heure_debut', $heureDebut)
            ->exists();

        if ($creneauPris) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Ce créneau est déjà pris. Choisissez un autre horaire.');
        }

        // Vérifier indisponibilité médecin
        $indisponible = Indisponibilite::where('medecin_id', $request->medecin_id)
            ->where('date_debut', '<=', $request->date_rendez_vous . ' ' . $heureDebut)
            ->where('date_fin', '>=', $request->date_rendez_vous . ' ' . $heureDebut)
            ->exists();

        if ($indisponible) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Le médecin est indisponible à ce créneau.');
        }

        $rendezVous = RendezVous::create([
            'patient_id'       => $request->patient_id,
            'medecin_id'       => $request->medecin_id,
            'date_rendez_vous' => $request->date_rendez_vous,
            'heure_debut'      => $heureDebut,
            'heure_fin'        => $heureFin,
            'motif'            => $request->motif,
            'statut'           => 'en_attente',
        ]);

        // Envoyer notification au patient
        $patient = Patient::with('user')->find($request->patient_id);
        Notification::create([
            'user_id'    => $patient->user->id,
            'titre'      => 'Rendez-vous créé',
            'message'    => 'Votre rendez-vous a été créé pour le ' . $rendezVous->date_rendez_vous . ' à ' . $rendezVous->heure_debut,
            'type'       => 'rendez_vous',
            'lu'         => false,
            'date_envoi' => now(),
        ]);

        return redirect()->route('secretaire.rendez-vous.index')
                         ->with('success', 'Rendez-vous créé avec succès.');
    }

    // Confirmer un RDV
    public function confirmer($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update(['statut' => 'confirme']);

        // Notifier le patient
        $patient = Patient::with('user')->find($rendezVous->patient_id);
        Notification::create([
            'user_id'    => $patient->user->id,
            'titre'      => 'Rendez-vous confirmé',
            'message'    => 'Votre rendez-vous du ' . $rendezVous->date_rendez_vous . ' à ' . $rendezVous->heure_debut . ' a été confirmé.',
            'type'       => 'rendez_vous',
            'lu'         => false,
            'date_envoi' => now(),
        ]);

        return redirect()->back()->with('success', 'Rendez-vous confirmé.');
    }

    // Annuler un RDV
    public function annuler($id)
    {
        $rendezVous = RendezVous::findOrFail($id);
        $rendezVous->update(['statut' => 'annule']);

        // Notifier le patient
        $patient = Patient::with('user')->find($rendezVous->patient_id);
        Notification::create([
            'user_id'    => $patient->user->id,
            'titre'      => 'Rendez-vous annulé',
            'message'    => 'Votre rendez-vous du ' . $rendezVous->date_rendez_vous . ' à ' . $rendezVous->heure_debut . ' a été annulé.',
            'type'       => 'rendez_vous',
            'lu'         => false,
            'date_envoi' => now(),
        ]);

        return redirect()->back()->with('success', 'Rendez-vous annulé.');
    }
}