<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Afficher la page de connexion
    public function showLogin()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (session('user_id')) {
            return $this->redirectToDashboard(session('user_role'));
        }
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'L\'email est obligatoire.',
            'email.email'       => 'L\'email n\'est pas valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
        ]);

        // Chercher l'utilisateur par email
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et le mot de passe est correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Email ou mot de passe incorrect.'])->withInput();
        }

        // Stocker les infos dans la session
        session([
            'user_id'     => $user->id,
            'user_nom'    => $user->nom,
            'user_prenom' => $user->prenom,
            'user_email'  => $user->email,
            'user_role'   => $user->role,
            'user_photo'  => $user->photo,
        ]);

        return $this->redirectToDashboard($user->role);
    }

    // Afficher la page d'inscription (patient uniquement)
    public function showRegister()
    {
        if (session('user_id')) {
            return $this->redirectToDashboard(session('user_role'));
        }
        return view('auth.register');
    }

    // Traiter l'inscription
    public function register(Request $request)
    {
        $request->validate([
            'nom'                => 'required|string|max:100',
            'prenom'             => 'required|string|max:100',
            'email'              => 'required|email|unique:users,email',
            'password'           => 'required|min:6|confirmed',
            'telephone'          => 'nullable|string|max:20',
            'cin'                => 'required|string|unique:patients,cin',
            'date_naissance'     => 'required|date',
            'sexe'               => 'required|in:homme,femme',
            'adresse'            => 'nullable|string|max:255',
            'groupe_sanguin'     => 'nullable|string|max:5',
        ], [
            'nom.required'           => 'Le nom est obligatoire.',
            'prenom.required'        => 'Le prénom est obligatoire.',
            'email.required'         => 'L\'email est obligatoire.',
            'email.unique'           => 'Cet email est déjà utilisé.',
            'password.required'      => 'Le mot de passe est obligatoire.',
            'password.min'           => 'Le mot de passe doit avoir au moins 6 caractères.',
            'password.confirmed'     => 'Les mots de passe ne correspondent pas.',
            'cin.required'           => 'Le CIN est obligatoire.',
            'cin.unique'             => 'Ce CIN est déjà utilisé.',
            'date_naissance.required'=> 'La date de naissance est obligatoire.',
            'sexe.required'          => 'Le sexe est obligatoire.',
        ]);

        // Créer le compte user
        $user = User::create([
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'telephone'=> $request->telephone,
            'role'     => 'patient',
        ]);

        // Créer le profil patient
        $patient = Patient::create([
            'user_id'        => $user->id,
            'cin'            => $request->cin,
            'date_naissance' => $request->date_naissance,
            'sexe'           => $request->sexe,
            'adresse'        => $request->adresse,
            'groupe_sanguin' => $request->groupe_sanguin,
        ]);

        // Créer automatiquement le dossier médical
        DossierMedical::create([
            'patient_id'    => $patient->id,
            'numero_dossier'=> 'DOS-' . str_pad($patient->id, 5, '0', STR_PAD_LEFT),
            'date_ouverture'=> now()->toDateString(),
        ]);

        // Connecter automatiquement après inscription
        session([
            'user_id'     => $user->id,
            'user_nom'    => $user->nom,
            'user_prenom' => $user->prenom,
            'user_email'  => $user->email,
            'user_role'   => $user->role,
            'user_photo'  => $user->photo,
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Compte créé avec succès ! Bienvenue.');
    }

    // Déconnexion
    public function logout()
    {
        session()->flush();
        return redirect()->route('login')->with('success', 'Vous êtes déconnecté.');
    }

    // Rediriger selon le rôle
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'medecin':
                return redirect()->route('medecin.dashboard');
            case 'secretaire':
                return redirect()->route('secretaire.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            default:
                return redirect()->route('login');
        }
    }
}