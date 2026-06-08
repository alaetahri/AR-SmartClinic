@extends('layouts.app')
@section('title', 'Dashboard Secrétaire')

@section('styles')
<style>
    .stat-card { border-radius: 15px; padding: 25px; color: #fff; border: none; }
    .stat-card .stat-icon { width: 55px; height: 55px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .stat-card .stat-number { font-size: 2rem; font-weight: 700; margin: 10px 0 5px; }
    .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; }
    .table th { font-weight: 600; color: #6c757d; font-size: 0.85rem; }
    .table td { vertical-align: middle; font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="container py-4">

    <!-- TITRE -->
    <div class="page-title mb-4">
        <h2><i class="fas fa-user-nurse me-2"></i> Tableau de bord</h2>
        <p>Bienvenue, {{ session('user_prenom') }} {{ session('user_nom') }} — {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- STATS -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#1a6fc4,#135aa0);">
                <div class="stat-icon"><i class="fas fa-hospital-user"></i></div>
                <div class="stat-number">{{ $totalPatients }}</div>
                <div class="stat-label">Total patients</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#17a2b8,#138496);">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-number">{{ $rendezVousAujourdhui }}</div>
                <div class="stat-label">RDV aujourd'hui</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#ffc107,#e0a800);">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-number">{{ $rendezVousEnAttente }}</div>
                <div class="stat-label">En attente</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#28a745,#1e7e34);">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-number">{{ $rendezVousConfirmes }}</div>
                <div class="stat-label">Confirmés</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- DERNIERS RDV -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-check me-2"></i> Rendez-vous en attente / confirmés</span>
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Patient</th>
                                    <th>Médecin</th>
                                    <th>Date</th>
                                    <th>Heure</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($derniersRendezVous as $rdv)
                                <tr>
                                    <td class="ps-3">{{ $rdv->patient->user->prenom }} {{ $rdv->patient->user->nom }}</td>
                                    <td>Dr. {{ $rdv->medecin->user->nom }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}</td>
                                    <td>{{ $rdv->heure_debut }}</td>
                                    <td>
                                        @if($rdv->statut == 'en_attente')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @else
                                            <span class="badge bg-success">Confirmé</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rdv->statut == 'en_attente')
                                        <form action="{{ route('secretaire.rendez-vous.confirmer', $rdv->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">Confirmer</button>
                                        </form>
                                        @endif
                                        <form action="{{ route('secretaire.rendez-vous.annuler', $rdv->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-danger">Annuler</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-3">Aucun rendez-vous</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ACTIONS RAPIDES -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header"><i class="fas fa-bolt me-2"></i> Actions rapides</div>
                <div class="card-body">
                    <a href="{{ route('secretaire.patients.create') }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-user-plus me-2"></i> Nouveau patient
                    </a>
                    <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-success w-100 mb-2">
                        <i class="fas fa-calendar-plus me-2"></i> Nouveau rendez-vous
                    </a>
                    <a href="{{ route('secretaire.patients.recherche') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-search me-2"></i> Rechercher un patient
                    </a>
                    <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-calendar me-2"></i> Tous les rendez-vous
                    </a>
                    <a href="{{ route('secretaire.consultations.index') }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-stethoscope me-2"></i> Toutes les consultations
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection