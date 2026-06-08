<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\DossierMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    // Liste des patients
    public function index(Request $request)
    {
        $query = Patient::with('user');

        $query->when($request->search, function ($q) use ($request) {
            return $q->whereHas('user', function ($q2) use ($request) {
                $q2->where('nom', 'like', '%' . $request->search . '%')
                   ->orWhere('prenom', 'like', '%' . $request->search . '%');
            })->orWhere('cin', 'like', '%' . $request->search . '%');
        });

        $patients = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('secretaire.patients.index', compact('patients'));
    }

    // Formulaire création patient
    public function create()
    {
        return view('secretaire.patients.create');
    }

    // Sauvegarder nouveau patient
    public function store(Request $request)
    {
        $request->validate([
            'nom'            => 'required|string|max:100',
            'prenom'         => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'telephone'      => 'nullable|string|max:20',
            'cin'            => 'required|string|unique:patients,cin',
            'date_naissance' => 'required|date',
            'sexe'           => 'required|in:homme,femme',
            'adresse'        => 'nullable|string|max:255',
            'groupe_sanguin' => 'nullable|string|max:5',
        ], [
            'nom.required'     => 'Le nom est obligatoire.',
            'prenom.required'  => 'Le prénom est obligatoire.',
            'email.required'   => 'L\'email est obligatoire.',
            'email.unique'     => 'Cet email est déjà utilisé.',
            'cin.required'     => 'Le CIN est obligatoire.',
            'cin.unique'       => 'Ce CIN est déjà utilisé.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'sexe.required'    => 'Le sexe est obligatoire.',
        ]);

        // Générer un mot de passe temporaire
        $motDePasseTemp = 'AR' . rand(1000, 9999);

        // Créer le compte user
        $user = User::create([
            'nom'       => $request->nom,
            'prenom'    => $request->prenom,
            'email'     => $request->email,
            'password'  => Hash::make($motDePasseTemp),
            'telephone' => $request->telephone,
            'role'      => 'patient',
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

        // Créer le dossier médical automatiquement
        DossierMedical::create([
            'patient_id'     => $patient->id,
            'numero_dossier' => 'DOS-' . str_pad($patient->id, 5, '0', STR_PAD_LEFT),
            'date_ouverture' => today(),
        ]);

        // Rediriger avec les identifiants à donner au patient
        return redirect()->route('secretaire.patients.show', $patient->id)
                         ->with('success', 'Patient créé. Email : ' . $user->email . ' | Mot de passe : ' . $motDePasseTemp);
    }

    // Afficher détails patient
    public function show($id)
    {
        $patient = Patient::with(['user', 'rendezVous.medecin.user'])->findOrFail($id);
        return view('secretaire.patients.show', compact('patient'));
    }

    // Recherche patient par CIN (pour RDV sur place)
    public function recherche(Request $request)
    {
        $patient = null;

        if ($request->cin) {
            $patient = Patient::with('user')
                ->where('cin', $request->cin)
                ->first();

            if (!$patient) {
                return redirect()->back()
                                 ->with('error', 'Aucun patient trouvé avec ce CIN.');
            }
        }

        return view('secretaire.patients.recherche', compact('patient'));
    }
}