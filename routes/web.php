<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Controllers Admin
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\SpecialiteController;
use App\Http\Controllers\Admin\ConversationController as AdminConversationController;

// Controllers Médecin
use App\Http\Controllers\Medecin\MedecinController;
use App\Http\Controllers\Medecin\RendezVousController as MedecinRendezVousController;
use App\Http\Controllers\Medecin\ConsultationController as MedecinConsultationController;
use App\Http\Controllers\Medecin\PatientController as MedecinPatientController;
use App\Http\Controllers\Medecin\IndisponibiliteController;
use App\Http\Controllers\Medecin\ProfilController as MedecinProfilController;

// Controllers Secrétaire
use App\Http\Controllers\Secretaire\SecretaireController;
use App\Http\Controllers\Secretaire\PatientController as SecretairePatientController;
use App\Http\Controllers\Secretaire\RendezVousController as SecretaireRendezVousController;
use App\Http\Controllers\Secretaire\ConsultationController as SecretaireConsultationController;

// Controllers Patient
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Patient\RendezVousController as PatientRendezVousController;
use App\Http\Controllers\Patient\DossierController;
use App\Http\Controllers\Patient\DocumentController;
use App\Http\Controllers\Patient\NotificationController;
use App\Http\Controllers\Patient\ProfilController as PatientProfilController;
use App\Http\Controllers\Patient\ConsultationController as PatientConsultationController;

// ─────────────────────────────────────────────
// PAGE D'ACCUEIL
// ─────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Accès direct aux fichiers storage — sans middleware
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response()->file($fullPath);
})->where('path', '.*');
// ─────────────────────────────────────────────
// AUTHENTIFICATION
// ─────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─────────────────────────────────────────────
// ROUTES ADMIN
// ─────────────────────────────────────────────
Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('personnel', PersonnelController::class);
    Route::resource('patients', AdminPatientController::class)->only(['index', 'show']);
    Route::resource('specialites', SpecialiteController::class);
    Route::resource('conversations', AdminConversationController::class)->only(['index', 'show']);
});

// ─────────────────────────────────────────────
// ROUTES MÉDECIN
// ─────────────────────────────────────────────
Route::middleware('role:medecin')->prefix('medecin')->name('medecin.')->group(function () {
    Route::get('/dashboard', [MedecinController::class, 'dashboard'])->name('dashboard');

    Route::resource('rendez-vous', MedecinRendezVousController::class)->only(['index', 'show']);
    Route::put('/rendez-vous/{id}/confirmer', [MedecinRendezVousController::class, 'confirmer'])->name('rendez-vous.confirmer');
    Route::put('/rendez-vous/{id}/terminer', [MedecinRendezVousController::class, 'terminer'])->name('rendez-vous.terminer');

    Route::get('/consultations/{id}', [MedecinConsultationController::class, 'show'])->name('consultations.show');
    Route::post('/consultations/{rendezVousId}/creer', [MedecinConsultationController::class, 'creer'])->name('consultations.creer');
    Route::put('/consultations/{id}', [MedecinConsultationController::class, 'update'])->name('consultations.update');
    Route::put('/consultations/{id}/cloturer', [MedecinConsultationController::class, 'cloturer'])->name('consultations.cloturer');

    Route::post('/documents/{consultationId}', [MedecinConsultationController::class, 'ajouterDocument'])->name('documents.store');
    Route::delete('/documents/{id}', [MedecinConsultationController::class, 'supprimerDocument'])->name('documents.destroy');

    Route::resource('patients', MedecinPatientController::class)->only(['index', 'show']);
    Route::get('/patients/{id}/dossier', [MedecinPatientController::class, 'dossier'])->name('patients.dossier');

    Route::resource('indisponibilites', IndisponibiliteController::class)->only(['index', 'store', 'destroy']);

    Route::get('/profil', [MedecinProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [MedecinProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/photo', [MedecinProfilController::class, 'updatePhoto'])->name('profil.photo');
});

// ─────────────────────────────────────────────
// ROUTES SECRÉTAIRE
// ─────────────────────────────────────────────
Route::middleware('role:secretaire')->prefix('secretaire')->name('secretaire.')->group(function () {
    Route::get('/dashboard', [SecretaireController::class, 'dashboard'])->name('dashboard');

    Route::get('/patients/recherche', [SecretairePatientController::class, 'recherche'])->name('patients.recherche');
    Route::resource('patients', SecretairePatientController::class)->only(['index', 'create', 'store', 'show']);

    Route::resource('rendez-vous', SecretaireRendezVousController::class)->only(['index', 'create', 'store']);
    Route::put('/rendez-vous/{id}/confirmer', [SecretaireRendezVousController::class, 'confirmer'])->name('rendez-vous.confirmer');
    Route::put('/rendez-vous/{id}/annuler', [SecretaireRendezVousController::class, 'annuler'])->name('rendez-vous.annuler');

    Route::resource('consultations', SecretaireConsultationController::class)->only(['index']);
});

// ─────────────────────────────────────────────
// ROUTES PATIENT
// ─────────────────────────────────────────────
Route::middleware('role:patient')->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    Route::resource('rendez-vous', PatientRendezVousController::class)->only(['index', 'create', 'store', 'show']);
    Route::put('/rendez-vous/{id}/annuler', [PatientRendezVousController::class, 'annuler'])->name('rendez-vous.annuler');

    Route::get('/dossier', [DossierController::class, 'index'])->name('dossier');

    Route::resource('documents', DocumentController::class)->only(['index']);
    Route::get('/documents/{id}/telecharger', [DocumentController::class, 'telecharger'])->name('documents.telecharger');

    Route::resource('consultations', PatientConsultationController::class)->only(['index', 'show']);

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::put('/notifications/{id}/lire', [NotificationController::class, 'marquerLu'])->name('notifications.lire');
    Route::put('/notifications/lire-tout', [NotificationController::class, 'marquerToutLu'])->name('notifications.lire-tout');

    Route::get('/profil', [PatientProfilController::class, 'index'])->name('profil');
    Route::put('/profil', [PatientProfilController::class, 'update'])->name('profil.update');
    Route::post('/profil/photo', [PatientProfilController::class, 'updatePhoto'])->name('profil.photo');
    Route::delete('/profil/photo', [PatientProfilController::class, 'supprimerPhoto'])->name('profil.photo.supprimer');

    Route::get('/assistant', [PatientController::class, 'assistant'])->name('assistant');
    Route::post('/assistant', [PatientController::class, 'envoyerMessage'])->name('assistant.envoyer');
    Route::post('/assistant/choisir-specialite', [PatientController::class, 'choisirSpecialite'])->name('assistant.choisir');
});