<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Indisponibilite;
use Illuminate\Http\Request;

class IndisponibiliteController extends Controller
{
    // Liste des indisponibilités
    public function index()
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $indisponibilites = Indisponibilite::where('medecin_id', $medecin->id)
            ->orderBy('date_debut', 'desc')
            ->paginate(10);

        return view('medecin.indisponibilites', compact('medecin', 'indisponibilites'));
    }

    // Ajouter une indisponibilité
    public function store(Request $request)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin'   => 'required|date|after:date_debut',
            'motif'      => 'nullable|string|max:200',
        ], [
            'date_debut.required'        => 'La date de début est obligatoire.',
            'date_debut.after_or_equal'  => 'La date de début doit être aujourd\'hui ou dans le futur.',
            'date_fin.required'          => 'La date de fin est obligatoire.',
            'date_fin.after'             => 'La date de fin doit être après la date de début.',
        ]);

        Indisponibilite::create([
            'medecin_id' => $medecin->id,
            'date_debut' => $request->date_debut,
            'date_fin'   => $request->date_fin,
            'motif'      => $request->motif,
        ]);

        return redirect()->route('medecin.indisponibilites.index')
                         ->with('success', 'Indisponibilité ajoutée.');
    }

    // Supprimer une indisponibilité
    public function destroy($id)
    {
        $medecin = Medecin::where('user_id', session('user_id'))->firstOrFail();

        $indisponibilite = Indisponibilite::where('medecin_id', $medecin->id)
            ->findOrFail($id);

        $indisponibilite->delete();

        return redirect()->route('medecin.indisponibilites.index')
                         ->with('success', 'Indisponibilité supprimée.');
    }
}