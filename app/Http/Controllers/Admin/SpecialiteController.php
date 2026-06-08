<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialite;
use Illuminate\Http\Request;

class SpecialiteController extends Controller
{
    // Liste des spécialités
    public function index(Request $request)
    {
        $query = Specialite::withCount('medecins');

        $query->when($request->search, function ($q) use ($request) {
            return $q->where('nom', 'like', '%' . $request->search . '%');
        });

        $specialites = $query->orderBy('nom')->paginate(10);

        return view('admin.specialites.index', compact('specialites'));
    }

    // Formulaire ajout
    public function create()
    {
        return view('admin.specialites.create');
    }

    // Sauvegarder
    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:100|unique:specialites,nom',
            'description' => 'nullable|string',
        ], [
            'nom.required' => 'Le nom de la spécialité est obligatoire.',
            'nom.unique'   => 'Cette spécialité existe déjà.',
        ]);

        Specialite::create([
            'nom'         => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.specialites.index')
                         ->with('success', 'Spécialité ajoutée avec succès.');
    }

    // Formulaire modification
    public function edit($id)
    {
        $specialite = Specialite::findOrFail($id);
        return view('admin.specialites.edit', compact('specialite'));
    }

    // Mettre à jour
    public function update(Request $request, $id)
    {
        $specialite = Specialite::findOrFail($id);

        $request->validate([
            'nom'         => 'required|string|max:100|unique:specialites,nom,' . $id,
            'description' => 'nullable|string',
        ]);

        $specialite->update([
            'nom'         => $request->nom,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.specialites.index')
                         ->with('success', 'Spécialité modifiée avec succès.');
    }

    // Supprimer
    public function destroy($id)
    {
        $specialite = Specialite::findOrFail($id);

        // Vérifier si des médecins utilisent cette spécialité
        if ($specialite->medecins()->count() > 0) {
            return redirect()->route('admin.specialites.index')
                             ->with('error', 'Impossible de supprimer : des médecins utilisent cette spécialité.');
        }

        $specialite->delete();

        return redirect()->route('admin.specialites.index')
                         ->with('success', 'Spécialité supprimée avec succès.');
    }

    // Afficher détails (non utilisé mais requis par resource)
    public function show($id)
    {
        $specialite = Specialite::with('medecins.user')->findOrFail($id);
        return view('admin.specialites.show', compact('specialite'));
    }
}