@extends('layouts.app')
@section('title', 'Mon Espace Patient')

@section('styles')
<style>
    .welcome-banner { background: linear-gradient(135deg, #1a6fc4, #17a2b8); border-radius: 15px; padding: 35px; color: #fff; margin-bottom: 30px; }
    .welcome-banner h2 { font-size: 1.8rem; font-weight: 700; margin-bottom: 8px; }
    .action-card { border-radius: 15px; padding: 30px 20px; text-align: center; transition: all 0.3s; cursor: pointer; border: 2px solid transparent; text-decoration: none; display: block; color: inherit; }
    .action-card:hover { transform: translateY(-5px); border-color: #1a6fc4; box-shadow: 0 10px 30px rgba(26,111,196,0.15); color: inherit; }
    .action-icon { width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 1.8rem; }
    .action-card h5 { font-weight: 600; margin-bottom: 8px; color: #2c3e50; }
    .action-card p { font-size: 0.85rem; color: #6c757d; margin: 0; }
    .rdv-card { background: #fff; border-radius: 15px; padding: 20px; box-shadow: 0 2px 15px rgba(0,0,0,0.07); border-left: 5px solid #1a6fc4; }
    .notif-item { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; font-size: 0.9rem; }
    .notif-item:last-child { border-bottom: none; }
</style>
@endsection

@section('content')
<div class="container py-4">

    <!-- BANNIÈRE BIENVENUE -->
    <div class="welcome-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2><i class="fas fa-hand-wave me-2"></i> Bonjour, {{ session('user_prenom') }} {{ session('user_nom') }} !</h2>
                <p style="opacity:0.9;margin:0;">Comment pouvons-nous vous aider aujourd'hui ? Votre santé est notre priorité.</p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:15px 20px;display:inline-block;">
                    <div style="font-size:0.85rem;opacity:0.8;">{{ now()->format('l d F Y') }}</div>
                    <div style="font-size:1.3rem;font-weight:600;">{{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- STATS RAPIDES -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div style="background:#fff;border-radius:15px;padding:20px;text-align:center;box-shadow:0 2px 15px rgba(0,0,0,0.07);">
                <div style="font-size:2rem;font-weight:700;color:#1a6fc4;">{{ $totalRendezVous }}</div>
                <div style="font-size:0.85rem;color:#6c757d;">Rendez-vous</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div style="background:#fff;border-radius:15px;padding:20px;text-align:center;box-shadow:0 2px 15px rgba(0,0,0,0.07);">
                <div style="font-size:2rem;font-weight:700;color:#28a745;">{{ $totalConsultations }}</div>
                <div style="font-size:0.85rem;color:#6c757d;">Consultations</div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div style="background:#fff;border-radius:15px;padding:20px;text-align:center;box-shadow:0 2px 15px rgba(0,0,0,0.07);">
                <div style="font-size:2rem;font-weight:700;color:#dc3545;">{{ $notifications->count() }}</div>
                <div style="font-size:0.85rem;color:#6c757d;">Notifications</div>
            </div>
        </div>
    </div>

    <!-- PROCHAIN RDV -->
    @if($prochainRendezVous)
    <div class="rdv-card mb-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div style="font-size:0.8rem;color:#6c757d;margin-bottom:5px;"><i class="fas fa-calendar-check me-1"></i> PROCHAIN RENDEZ-VOUS</div>
                <h5 style="font-weight:700;margin-bottom:5px;">Dr. {{ $prochainRendezVous->medecin->user->prenom }} {{ $prochainRendezVous->medecin->user->nom }}</h5>
                <div style="color:#6c757d;font-size:0.9rem;">
                    <i class="fas fa-stethoscope me-1"></i> {{ $prochainRendezVous->medecin->specialite->nom }}
                    &nbsp;|&nbsp;
                    <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($prochainRendezVous->date_rendez_vous)->format('d/m/Y') }}
                    &nbsp;|&nbsp;
                    <i class="fas fa-clock me-1"></i> {{ $prochainRendezVous->heure_debut }}
                </div>
            </div>
            <div>
                @if($prochainRendezVous->statut == 'confirme')
                    <span class="badge bg-success">Confirmé</span>
                @else
                    <span class="badge bg-warning text-dark">En attente</span>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- ACTIONS PRINCIPALES -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3">
            <a href="{{ route('patient.rendez-vous.create') }}" class="action-card card">
                <div class="action-icon" style="background:#e8f4fd;"><i class="fas fa-calendar-plus" style="color:#1a6fc4;"></i></div>
                <h5>Rendez-vous direct</h5>
                <p>Choisissez votre médecin directement</p>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('patient.assistant') }}" class="action-card card">
                <div class="action-icon" style="background:#e8fdf5;"><i class="fas fa-robot" style="color:#17a2b8;"></i></div>
                <h5>Assistant IA</h5>
                <p>Décrivez vos symptômes et soyez orienté</p>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('patient.rendez-vous.index') }}" class="action-card card">
                <div class="action-icon" style="background:#fff3e0;"><i class="fas fa-list-alt" style="color:#ff9800;"></i></div>
                <h5>Mes rendez-vous</h5>
                <p>Consultez tous vos rendez-vous</p>
            </a>
        </div>
        <div class="col-6 col-md-3">
            <a href="{{ route('patient.dossier') }}" class="action-card card">
                <div class="action-icon" style="background:#f3e5f5;"><i class="fas fa-folder-medical" style="color:#9c27b0;"></i></div>
                <h5>Mon dossier</h5>
                <p>Accédez à votre dossier médical</p>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- LIENS RAPIDES -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-bolt me-2"></i> Accès rapide</div>
                <div class="card-body">
                    <a href="{{ route('patient.consultations.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-history me-2"></i> Historique des consultations
                    </a>
                    <a href="{{ route('patient.documents.index') }}" class="btn btn-outline-success w-100 mb-2">
                        <i class="fas fa-file-medical me-2"></i> Mes documents médicaux
                    </a>
                    <a href="{{ route('patient.notifications') }}" class="btn btn-outline-warning w-100 mb-2">
                        <i class="fas fa-bell me-2"></i> Mes notifications
                    </a>
                    <a href="{{ route('patient.profil') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-user me-2"></i> Mon profil
                    </a>
                </div>
            </div>
        </div>

        <!-- NOTIFICATIONS -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-bell me-2"></i> Notifications récentes</span>
                    <a href="{{ route('patient.notifications') }}" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notif)
                    <div class="notif-item">
                        <div style="font-weight:500;">{{ $notif->titre }}</div>
                        <div style="font-size:0.8rem;color:#6c757d;margin-top:3px;">{{ $notif->message }}</div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">
                        <i class="fas fa-bell-slash" style="font-size:1.5rem;opacity:0.3;"></i>
                        <p class="mt-2 mb-0">Aucune notification</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection