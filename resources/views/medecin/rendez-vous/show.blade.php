@extends('layouts.app')

@section('title', 'Détails Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-calendar-check me-2"></i> Détails du Rendez-vous</h2>
            <p>{{ \Carbon\Carbon::parse($rendezVous->date_rendez_vous)->format('d/m/Y') }}
                à {{ $rendezVous->heure_debut }}</p>
        </div>
        <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">

        {{-- INFOS RDV --}}
        <div class="col-lg-4 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar me-2"></i>Informations RDV
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Date</span>
                        <span class="fw-500">{{ \Carbon\Carbon::parse($rendezVous->date_rendez_vous)->format('d/m/Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Heure</span>
                        <span class="fw-500">{{ $rendezVous->heure_debut }} — {{ $rendezVous->heure_fin }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Motif</span>
                        <span class="fw-500">{{ $rendezVous->motif ?? '—' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Statut</span>
                        @php
                            $badges = [
                                'en_attente' => ['bg-warning text-dark', 'En attente'],
                                'confirme'   => ['bg-success', 'Confirmé'],
                                'annule'     => ['bg-danger', 'Annulé'],
                                'termine'    => ['bg-secondary', 'Terminé'],
                            ];
                            [$badgeClass, $label] = $badges[$rendezVous->statut] ?? ['bg-secondary', $rendezVous->statut];
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cogs me-2"></i>Actions
                </div>
                <div class="card-body d-grid gap-2">
                    @if($rendezVous->statut === 'en_attente')
                        <form action="{{ route('medecin.rendez-vous.confirmer', $rendezVous->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i>Confirmer le RDV
                            </button>
                        </form>
                    @endif

                    @if($rendezVous->statut === 'confirme' && !$rendezVous->consultation)
                        <form action="{{ route('medecin.consultations.creer', $rendezVous->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-notes-medical me-2"></i>Démarrer Consultation
                            </button>
                        </form>
                    @endif

                    @if($rendezVous->consultation)
                        <a href="{{ route('medecin.consultations.show', $rendezVous->consultation->id) }}"
                            class="btn btn-info text-white w-100">
                            <i class="fas fa-file-medical me-2"></i>Voir Consultation
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- PATIENT --}}
        <div class="col-lg-8 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Patient
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if($rendezVous->patient->user->photo)
                            <img src="{{ asset($rendezVous->patient->user->photo) }}"
                                style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
                        @else
                            <div style="width:60px;height:60px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid var(--primary);">
                                <i class="fas fa-user fa-lg" style="color:var(--primary);"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold mb-0">
                                {{ $rendezVous->patient->user->prenom }}
                                {{ $rendezVous->patient->user->nom }}
                            </h5>
                            <small class="text-muted">{{ $rendezVous->patient->user->email }}</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">CIN</p>
                            <p class="fw-500">{{ $rendezVous->patient->cin }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Téléphone</p>
                            <p class="fw-500">{{ $rendezVous->patient->user->telephone ?? '—' }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Groupe sanguin</p>
                            @if($rendezVous->patient->groupe_sanguin)
                                <span class="badge bg-danger">{{ $rendezVous->patient->groupe_sanguin }}</span>
                            @else
                                <p class="fw-500">—</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- DOSSIER MÉDICAL --}}
            @if($rendezVous->patient->dossierMedical)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-folder-medical me-2"></i>Dossier Médical
                    <span class="badge bg-light text-dark ms-2">
                        {{ $rendezVous->patient->dossierMedical->numero_dossier }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Allergies</p>
                            <p>{{ $rendezVous->patient->dossierMedical->allergies ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Maladies chroniques</p>
                            <p>{{ $rendezVous->patient->dossierMedical->maladies_chroniques ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Antécédents médicaux</p>
                            <p>{{ $rendezVous->patient->dossierMedical->antecedents_medicaux ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Traitements en cours</p>
                            <p>{{ $rendezVous->patient->dossierMedical->traitements_en_cours ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>

</div>
@endsection