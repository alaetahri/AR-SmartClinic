@extends('layouts.app')
@section('title', 'Dashboard Directeur')

@section('styles')
<style>
    .stat-card { border-radius: 15px; padding: 25px; color: #fff; border: none; }
    .stat-card .stat-icon { width: 55px; height: 55px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .stat-card .stat-number { font-size: 2rem; font-weight: 700; margin: 10px 0 5px; }
    .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; }
    .table th { font-weight: 600; color: #6c757d; font-size: 0.85rem; border-bottom: 2px solid #e9ecef; }
    .table td { vertical-align: middle; font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="page-title mb-4">
        <h2><i class="fas fa-tachometer-alt me-2"></i> Dashboard Directeur</h2>
        <p>Bienvenue, {{ session('user_prenom') }} {{ session('user_nom') }} — {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- STATS PRINCIPALES -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#1a6fc4,#135aa0);">
                <div class="stat-icon"><i class="fas fa-hospital-user"></i></div>
                <div class="stat-number">{{ $totalPatients }}</div>
                <div class="stat-label">Patients enregistrés</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#17a2b8,#138496);">
                <div class="stat-icon"><i class="fas fa-user-md"></i></div>
                <div class="stat-number">{{ $totalMedecins }}</div>
                <div class="stat-label">Médecins</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#28a745,#1e7e34);">
                <div class="stat-icon"><i class="fas fa-stethoscope"></i></div>
                <div class="stat-number">{{ $totalSpecialites }}</div>
                <div class="stat-label">Spécialités</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#fd7e14,#e96b02);">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number">{{ $totalSecretaires }}</div>
                <div class="stat-label">Secrétaires</div>
            </div>
        </div>
    </div>

    <!-- STATS RENDEZ-VOUS -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="fas fa-calendar-alt me-2"></i> Statistiques des Rendez-vous</div>
                <div class="card-body">
                    <div class="row g-3 text-center">
                        <div class="col-6 col-md-2">
                            <div style="background:#f4f8fc;border-radius:12px;padding:20px 10px;">
                                <div style="font-size:1.8rem;font-weight:700;color:#1a6fc4;">{{ $totalRendezVous }}</div>
                                <div style="font-size:0.8rem;color:#6c757d;margin-top:5px;">Total</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div style="background:#fff3cd;border-radius:12px;padding:20px 10px;">
                                <div style="font-size:1.8rem;font-weight:700;color:#856404;">{{ $rendezVousEnAttente }}</div>
                                <div style="font-size:0.8rem;color:#856404;margin-top:5px;">En attente</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div style="background:#d1ecf1;border-radius:12px;padding:20px 10px;">
                                <div style="font-size:1.8rem;font-weight:700;color:#0c5460;">{{ $rendezVousConfirmes }}</div>
                                <div style="font-size:0.8rem;color:#0c5460;margin-top:5px;">Confirmés</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div style="background:#d4edda;border-radius:12px;padding:20px 10px;">
                                <div style="font-size:1.8rem;font-weight:700;color:#155724;">{{ $rendezVousTermines }}</div>
                                <div style="font-size:0.8rem;color:#155724;margin-top:5px;">Terminés</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div style="background:#f8d7da;border-radius:12px;padding:20px 10px;">
                                <div style="font-size:1.8rem;font-weight:700;color:#721c24;">{{ $rendezVousAnnules }}</div>
                                <div style="font-size:0.8rem;color:#721c24;margin-top:5px;">Annulés</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div style="background:#e8f4fd;border-radius:12px;padding:20px 10px;">
                                <div style="font-size:1.8rem;font-weight:700;color:#1a6fc4;">{{ $rendezVousAujourdhui }}</div>
                                <div style="font-size:0.8rem;color:#1a6fc4;margin-top:5px;">Aujourd'hui</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- DERNIERS RDV -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-check me-2"></i> Derniers Rendez-vous</span>
                    {{-- Lien vers liste RDV --}}
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-light">Voir patients</a>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($derniersRendezVous as $rdv)
                                <tr>
                                    <td class="ps-3">
                                        <div style="font-weight:500;">
                                            {{ $rdv->patient->user->prenom ?? '' }}
                                            {{ $rdv->patient->user->nom ?? '' }}
                                        </div>
                                    </td>
                                    <td>Dr. {{ $rdv->medecin->user->nom ?? '' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}</td>
                                    <td>{{ $rdv->heure_debut }}</td>
                                    <td>
                                        @if($rdv->statut == 'en_attente')
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        @elseif($rdv->statut == 'confirme')
                                            <span class="badge bg-info">Confirmé</span>
                                        @elseif($rdv->statut == 'termine')
                                            <span class="badge bg-success">Terminé</span>
                                        @else
                                            <span class="badge bg-danger">Annulé</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Aucun rendez-vous</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MÉDECINS + LIENS RAPIDES -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-user-md me-2"></i> Médecins actifs aujourd'hui</div>
                <div class="card-body p-0">
                    @forelse($medecins as $medecin)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div style="width:40px;height:40px;background:#e8f4fd;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:12px;">
                            @if($medecin->user->photo)
                                <img src="{{ asset($medecin->user->photo) }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                            @else
                                <i class="fas fa-user-md" style="color:#1a6fc4;"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div style="font-weight:500;font-size:0.9rem;">Dr. {{ $medecin->user->prenom }} {{ $medecin->user->nom }}</div>
                            <div style="font-size:0.8rem;color:#6c757d;">{{ $medecin->specialite->nom ?? '' }}</div>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $medecin->rdv_aujourdhui }} RDV</span>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">Aucun médecin</div>
                    @endforelse
                </div>
            </div>

            <!-- LIENS RAPIDES -->
            <div class="card">
                <div class="card-header"><i class="fas fa-bolt me-2"></i> Actions rapides</div>
                <div class="card-body">
                    <a href="{{ route('admin.personnel.create') }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-user-plus me-2"></i> Ajouter un médecin
                    </a>
                    <a href="{{ route('admin.specialites.create') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-plus me-2"></i> Ajouter une spécialité
                    </a>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="fas fa-hospital-user me-2"></i> Liste des patients
                    </a>
                    <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-info w-100 mb-2">
                        <i class="fas fa-users me-2"></i> Liste du personnel
                    </a>
                    <a href="{{ route('admin.conversations.index') }}" class="btn btn-outline-dark w-100">
                        <i class="fas fa-robot me-2"></i> Conversations IA
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection