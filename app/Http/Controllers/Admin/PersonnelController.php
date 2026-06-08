<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Medecin;
use App\Models\Specialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
    // Liste du personnel (médecins + secrétaires)
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['medecin', 'secretaire']);

        $query->when($request->role, function ($q) use ($request) {
            return $q->where('role', $request->role);
        });

        $query->when($request->search, function ($q) use ($request) {
            return $q->where(function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%')
                   ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        });

        $personnel = $query->orderBy('created_at', 'desc')->paginate(10);
        $specialites = Specialite::all();

        return view('admin.personnel.index', compact('personnel', 'specialites'));
    }

    // Formulaire ajout personnel
    public function create()
    {
        $specialites = Specialite::all();
        return view('admin.personnel.create', compact('specialites'));
    }

    // Sauvegarder nouveau personnel
    public function store(Request $request)
    {
        $rules = [
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'telephone' => 'nullable|string|max:20',
            'role'      => 'required|in:medecin,secretaire',
        ];

        if ($request->role === 'medecin') {
            $rules['specialite_id'] = 'required|exists:specialites,id';
            $rules['numero_ordre']  = 'required|string|max:50';
            $rules['biographie']    = 'nullable|string';
        }

        $request->validate($rules, [
            'nom.required'           => 'Le nom est obligatoire.',
            'prenom.required'        => 'Le prénom est obligatoire.',
            'email.required'         => 'L\'email est obligatoire.',
            'email.unique'           => 'Cet email est déjà utilisé.',
            'password.required'      => 'Le mot de passe est obligatoire.',
            'password.min'           => 'Le mot de passe doit avoir au moins 6 caractères.',
            'role.required'          => 'Le rôle est obligatoire.',
            'specialite_id.required' => 'La spécialité est obligatoire pour un médecin.',
            'numero_ordre.required'  => 'Le numéro d\'ordre est obligatoire pour un médecin.',
        ]);

        $user = User::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'telephone' => $request->telephone,
            'role'      => $request->role,
        ]);

        if ($request->role === 'medecin') {
            Medecin::create([
                'user_id'       => $user->id,
                'specialite_id' => $request->specialite_id,
                'numero_ordre'  => $request->numero_ordre,
                'biographie'    => $request->biographie,
            ]);
        }

        return redirect()->route('admin.personnel.index')
                         ->with('success', 'Personnel ajouté avec succès.');
    }

    // Afficher détails d'un membre du personnel
    public function show($id)
    {
        $user    = User::findOrFail($id);
        $medecin = null;

        if ($user->role === 'medecin') {
            $medecin = Medecin::with('specialite')->where('user_id', $id)->first();
        }

        return view('admin.personnel.show', compact('user', 'medecin'));
    }

    // Formulaire modification
    public function edit($id)
    {
        $user        = User::findOrFail($id);
        $specialites = Specialite::all();
        $medecin     = null;

        if ($user->role === 'medecin') {
            $medecin = Medecin::where('user_id', $id)->first();
        }

        return view('admin.personnel.edit', compact('user', 'specialites', 'medecin'));
    }

    // Mettre à jour
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'nom'       => 'required|string|max:100',
            'prenom'    => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $id,
            'telephone' => 'nullable|string|max:20',
        ];

        if ($user->role === 'medecin') {
            $rules['specialite_id'] = 'required|exists:specialites,id';
            $rules['numero_ordre']  = 'required|string|max:50';
            $rules['biographie']    = 'nullable|string';
        }

        $request->validate($rules);

        $user->update([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'telephone' => $request->telephone,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        if ($user->role === 'medecin') {
            Medecin::where('user_id', $id)->update([
                'specialite_id' => $request->specialite_id,
                'numero_ordre'  => $request->numero_ordre,
                'biographie'    => $request->biographie,
            ]);
        }

        return redirect()->route('admin.personnel.index')
                         ->with('success', 'Personnel modifié avec succès.');
    }

    // Supprimer
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.personnel.index')
                         ->with('success', 'Personnel supprimé avec succès.');
    }
}