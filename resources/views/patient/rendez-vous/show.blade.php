@extends('layouts.app')

@section('title', 'Détails Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-calendar-check me-2"></i> Détails du Rendez-vous</h2>
            <p>{{ \Carbon\Carbon::parse($rendezVous->date_rendez_vous)->format('d/m/Y') }} à {{ $rendezVous->heure_debut }}</p>
        </div>
        <a href="{{ route('patient.rendez-vous.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- INFOS RDV --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-calendar me-2"></i>Informations du Rendez-vous
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Date</p>
                            <p class="fw-500">{{ \Carbon\Carbon::parse($rendezVous->date_rendez_vous)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Heure</p>
                            <p class="fw-500">{{ $rendezVous->heure_debut }} — {{ $rendezVous->heure_fin }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Motif</p>
                            <p class="fw-500">{{ $rendezVous->motif ?? '—' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Statut</p>
                            @php
                                $badges = [
                                    'en_attente' => ['bg-warning text-dark', 'En attente'],
                                    'confirme'   => ['bg-success', 'Confirmé'],
                                    'annule'     => ['bg-danger', 'Annulé'],
                                    'termine'    => ['bg-secondary', 'Terminé'],
                                ];
                                [$bc, $lb] = $badges[$rendezVous->statut] ?? ['bg-secondary', $rendezVous->statut];
                            @endphp
                            <span class="badge {{ $bc }} px-3 py-2">{{ $lb }}</span>
                        </div>
                    </div>

                    @if(in_array($rendezVous->statut, ['en_attente', 'confirme']))
                        <form action="{{ route('patient.rendez-vous.annuler', $rendezVous->id) }}" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                            @csrf @method('PUT')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i>Annuler ce Rendez-vous
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- MÉDECIN --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-md me-2"></i>Médecin
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        @if($rendezVous->medecin->user->photo)
                            <img src="{{ asset($rendezVous->medecin->user->photo) }}"
                                style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
                        @else
                            <div style="width:60px;height:60px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid var(--primary);">
                                <i class="fas fa-user-md fa-lg" style="color:var(--primary);"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold mb-1">
                                Dr. {{ $rendezVous->medecin->user->prenom }} {{ $rendezVous->medecin->user->nom }}
                            </h5>
                            <span class="badge bg-success">{{ $rendezVous->medecin->specialite->nom }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONSULTATION --}}
            @if($rendezVous->consultation)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-notes-medical me-2"></i>Consultation associée
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Diagnostic</p>
                            <p>{{ $rendezVous->consultation->diagnostic ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1" style="font-size:0.8rem;">Statut</p>
                            @if($rendezVous->consultation->statut === 'terminee')
                                <span class="badge bg-success">Terminée</span>
                            @else
                                <span class="badge bg-warning text-dark">En cours</span>
                            @endif
                        </div>
                    </div>

                    @if($rendezVous->consultation->documents->count() > 0)
                    <div>
                        <p class="text-muted mb-2" style="font-size:0.8rem;">Documents</p>
                        @foreach($rendezVous->consultation->documents as $doc)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span><i class="fas fa-file-medical me-2 text-primary"></i>{{ $doc->titre }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark">{{ ucfirst($doc->type) }}</span>
                                    <a href="{{ route('patient.documents.telecharger', $doc->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('patient.consultations.show', $rendezVous->consultation->id) }}"
                            class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-2"></i>Voir la consultation complète
                        </a>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection