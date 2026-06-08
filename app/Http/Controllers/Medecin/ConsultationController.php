<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Consultation;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConsultationController extends Controller
{
    // Afficher une consultation
    public function show($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $consultation = Consultation::where('medecin_id', $medecin->id)
            ->with([
                'patient.user',
                'patient.dossierMedical',
                'documents',
                'rendezVous',
            ])
            ->findOrFail($id);

        return view('medecin.consultation.show', compact('consultation'));
    }

    // Créer une consultation depuis un rendez-vous
    public function creer($rendezVousId)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $rendezVous = RendezVous::where('medecin_id', $medecin->id)
            ->where('statut', 'confirme')
            ->findOrFail($rendezVousId);

        // Vérifier si consultation existe déjà
        if ($rendezVous->consultation) {
            return redirect()->route('medecin.consultations.show', $rendezVous->consultation->id)
                             ->with('info', 'La consultation existe déjà.');
        }

        $consultation = Consultation::create([
            'patient_id'       => $rendezVous->patient_id,
            'medecin_id'       => $medecin->id,
            'rendez_vous_id'   => $rendezVous->id,
            'date_consultation'=> today(),
            'motif'            => $rendezVous->motif,
            'statut'           => 'en_cours',
        ]);

        return redirect()->route('medecin.consultations.show', $consultation->id)
                         ->with('success', 'Consultation démarrée.');
    }

    // Mettre à jour diagnostic et notes
    public function update(Request $request, $id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $consultation = Consultation::where('medecin_id', $medecin->id)->findOrFail($id);

        $request->validate([
            'diagnostic'    => 'nullable|string',
            'notes_medecin' => 'nullable|string',
        ]);

        $consultation->update([
            'diagnostic'    => $request->diagnostic,
            'notes_medecin' => $request->notes_medecin,
        ]);

        return redirect()->back()->with('success', 'Consultation mise à jour.');
    }

    // Clôturer une consultation
    public function cloturer($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $consultation = Consultation::where('medecin_id', $medecin->id)->findOrFail($id);
        $consultation->update(['statut' => 'terminee']);

        // Mettre à jour le statut du rendez-vous
        RendezVous::where('id', $consultation->rendez_vous_id)
            ->update(['statut' => 'termine']);

        return redirect()->route('medecin.rendez-vous.index')
                         ->with('success', 'Consultation clôturée avec succès.');
    }

    // Ajouter un document médical
    public function ajouterDocument(Request $request, $consultationId)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $consultation = Consultation::where('medecin_id', $medecin->id)
            ->findOrFail($consultationId);

        $request->validate([
            'type'        => 'required|in:ordonnance,analyse,radio,scanner,certificat,autre',
            'titre'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'fichier'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'type.required'    => 'Le type de document est obligatoire.',
            'titre.required'   => 'Le titre est obligatoire.',
            'fichier.required' => 'Le fichier est obligatoire.',
            'fichier.mimes'    => 'Le fichier doit être PDF, JPG ou PNG.',
            'fichier.max'      => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        // Sauvegarder le fichier
        $cheminFichier = $request->file('fichier')->store('documents', 'public');

        Document::create([
            'patient_id'      => $consultation->patient_id,
            'consultation_id' => $consultation->id,
            'type'            => $request->type,
            'titre'           => $request->titre,
            'description'     => $request->description,
            'fichier'         => $cheminFichier,
            'date_document'   => today(),
        ]);

        return redirect()->back()->with('success', 'Document ajouté avec succès.');
    }

    // Supprimer un document
    public function supprimerDocument($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $document = Document::whereHas('consultation', function ($q) use ($medecin) {
            $q->where('medecin_id', $medecin->id);
        })->findOrFail($id);

        // Supprimer le fichier du storage
        Storage::disk('public')->delete($document->fichier);
        $document->delete();

        return redirect()->back()->with('success', 'Document supprimé.');
    }
}