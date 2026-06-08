@extends('layouts.app')
@section('title', 'Dashboard Médecin')

@section('styles')
<style>
    .stat-card { border-radius: 15px; padding: 25px; color: #fff; border: none; }
    .stat-card .stat-icon { width: 55px; height: 55px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .stat-card .stat-number { font-size: 2rem; font-weight: 700; margin: 10px 0 5px; }
    .stat-card .stat-label { font-size: 0.85rem; opacity: 0.85; }
    .rdv-item { border-left: 4px solid #1a6fc4; padding: 12px 15px; margin-bottom: 10px; background: #f4f8fc; border-radius: 0 10px 10px 0; }
    .table th { font-weight: 600; color: #6c757d; font-size: 0.85rem; }
    .table td { vertical-align: middle; font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="container py-4">

    <!-- TITRE -->
    <div class="page-title mb-4">
        <h2><i class="fas fa-user-md me-2"></i> Tableau de bord</h2>
        <p>Bienvenue Dr. {{ session('user_prenom') }} {{ session('user_nom') }} — {{ $medecin->specialite->nom }} — {{ now()->format('d/m/Y') }}</p>
    </div>

    <!-- STATS -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#1a6fc4,#135aa0);">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-number">{{ $rendezVousAujourdhui->count() }}</div>
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
                <div class="stat-number">{{ $totalConsultations }}</div>
                <div class="stat-label">Consultations terminées</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card" style="background:linear-gradient(135deg,#17a2b8,#138496);">
                <div class="stat-icon"><i class="fas fa-stethoscope"></i></div>
                <div class="stat-number">{{ $consultationEnCours ? '1' : '0' }}</div>
                <div class="stat-label">En cours</div>
            </div>
        </div>
    </div>

    @if($consultationEnCours)
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Consultation en cours avec <strong>{{ $consultationEnCours->patient->user->prenom }} {{ $consultationEnCours->patient->user->nom }}</strong>
        <a href="{{ route('medecin.consultations.show', $consultationEnCours->id) }}" class="btn btn-sm btn-primary ms-3">
            <i class="fas fa-arrow-right me-1"></i> Continuer
        </a>
    </div>
    @endif

    <div class="row g-4">
        <!-- RDV AUJOURD'HUI -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-day me-2"></i> Rendez-vous d'aujourd'hui</span>
                    <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-sm btn-light">Voir tout</a>
                </div>
                <div class="card-body">
                    @forelse($rendezVousAujourdhui as $rdv)
                    <div class="rdv-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div style="font-weight:600;">{{ $rdv->patient->user->prenom }} {{ $rdv->patient->user->nom }}</div>
                                <div style="font-size:0.85rem;color:#6c757d;margin-top:3px;">
                                    <i class="fas fa-clock me-1"></i> {{ $rdv->heure_debut }} - {{ $rdv->heure_fin }}
                                    @if($rdv->motif)
                                        | <i class="fas fa-notes-medical me-1"></i> {{ $rdv->motif }}
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                @if($rdv->statut == 'en_attente')
                                    <form action="{{ route('medecin.rendez-vous.confirmer', $rdv->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success">Confirmer</button>
                                    </form>
                                @elseif($rdv->statut == 'confirme')
                                    @if(!$rdv->consultation)
                                        <form action="{{ route('medecin.consultations.creer', $rdv->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Démarrer</button>
                                        </form>
                                    @else
                                        <a href="{{ route('medecin.consultations.show', $rdv->consultation->id) }}" class="btn btn-sm btn-info">Continuer</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-check" style="font-size:2.5rem;opacity:0.3;"></i>
                        <p class="mt-2">Aucun rendez-vous aujourd'hui</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- PROCHAINS RDV + LIENS -->
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-calendar me-2"></i> Prochains rendez-vous</div>
                <div class="card-body p-0">
                    @forelse($prochainsRendezVous as $rdv)
                    <div class="d-flex align-items-center p-3 border-bottom">
                        <div style="background:#e8f4fd;border-radius:10px;padding:8px 12px;margin-right:12px;text-align:center;min-width:55px;">
                            <div style="font-size:1.1rem;font-weight:700;color:#1a6fc4;">{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d') }}</div>
                            <div style="font-size:0.7rem;color:#6c757d;">{{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('M') }}</div>
                        </div>
                        <div>
                            <div style="font-weight:500;font-size:0.9rem;">{{ $rdv->patient->user->prenom }} {{ $rdv->patient->user->nom }}</div>
                            <div style="font-size:0.8rem;color:#6c757d;"><i class="fas fa-clock me-1"></i> {{ $rdv->heure_debut }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted">Aucun prochain rendez-vous</div>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header"><i class="fas fa-bolt me-2"></i> Actions rapides</div>
                <div class="card-body">
                    <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-calendar me-2"></i> Tous mes rendez-vous
                    </a>
                    <a href="{{ route('medecin.patients.index') }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="fas fa-users me-2"></i> Mes patients
                    </a>
                    <a href="{{ route('medecin.indisponibilites.index') }}" class="btn btn-outline-warning w-100">
                        <i class="fas fa-calendar-times me-2"></i> Mes indisponibilités
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection