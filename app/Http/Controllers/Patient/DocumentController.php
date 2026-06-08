<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $documents = Document::where('patient_id', $patient->id)
            ->with('consultation.medecin.user')
            ->orderBy('date_document', 'desc')
            ->paginate(10);

        return view('patient.documents', compact('documents'));
    }

    public function telecharger($id)
    {
        $patient = Patient::where('user_id', session('user_id'))->firstOrFail();

        $document = Document::where('patient_id', $patient->id)->findOrFail($id);

        if (!Storage::disk('public')->exists($document->fichier)) {
            return redirect()->back()->with('error', 'Fichier introuvable.');
        }

        // Récupérer l'extension réelle du fichier stocké
        $extension  = pathinfo($document->fichier, PATHINFO_EXTENSION);

        // Nettoyer le titre et ajouter l'extension
        $nomFichier = \Str::slug($document->titre) . '.' . $extension;

        // Récupérer le type MIME réel
        $cheminComplet = Storage::disk('public')->path($document->fichier);
        $mimeType      = mime_content_type($cheminComplet);

        return response()->download($cheminComplet, $nomFichier, [
            'Content-Type' => $mimeType,
        ]);
    }
}