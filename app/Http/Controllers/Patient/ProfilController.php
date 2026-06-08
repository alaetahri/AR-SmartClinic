<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user    = User::findOrFail(session('user_id'));
        $patient = Patient::where('user_id', $user->id)->firstOrFail();
        return view('patient.profil', compact('user', 'patient'));
    }

    public function update(Request $request)
    {
        $user    = User::findOrFail(session('user_id'));
        $patient = Patient::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'nom'                       => 'required|string|max:100',
            'prenom'                    => 'required|string|max:100',
            'telephone'                 => 'nullable|string|max:20',
            'adresse'                   => 'nullable|string|max:255',
            'contact_urgence_nom'       => 'nullable|string|max:100',
            'contact_urgence_telephone' => 'nullable|string|max:20',
        ]);

        $user->update([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'telephone' => $request->telephone,
        ]);

        $patient->update([
            'adresse'                   => $request->adresse,
            'contact_urgence_nom'       => $request->contact_urgence_nom,
            'contact_urgence_telephone' => $request->contact_urgence_telephone,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ], [
                'password.min'       => 'Le mot de passe doit avoir au moins 6 caractères.',
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        session(['user_nom' => $user->nom, 'user_prenom' => $user->prenom]);

        return redirect()->route('patient.profil')
                         ->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'photo.required' => 'Veuillez choisir une photo.',
            'photo.image'    => 'Le fichier doit être une image.',
            'photo.max'      => 'La photo ne doit pas dépasser 2 Mo.',
        ]);

        $user = User::findOrFail(session('user_id'));

        // Supprimer ancienne photo
        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        // Sauvegarder dans public/uploads/photos/
        $nomFichier = time() . '_' . uniqid() . '.' . $request->file('photo')->getClientOriginalExtension();
        $request->file('photo')->move(public_path('uploads/photos'), $nomFichier);
        $chemin = 'uploads/photos/' . $nomFichier;

        $user->update(['photo' => $chemin]);
        session(['user_photo' => $chemin]);

        return redirect()->route('patient.profil')->with('success', 'Photo mise à jour.');
    }

    public function supprimerPhoto()
    {
        $user = User::findOrFail(session('user_id'));

        if ($user->photo && file_exists(public_path($user->photo))) {
            unlink(public_path($user->photo));
        }

        $user->update(['photo' => null]);
        session(['user_photo' => null]);

        return redirect()->route('patient.profil')->with('success', 'Photo supprimée.');
    }
}