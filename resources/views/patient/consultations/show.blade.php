@extends('layouts.app')

@section('title', 'Détails Consultation')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-notes-medical me-2"></i> Détails de la Consultation</h2>
            <p>{{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('patient.consultations.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">

        {{-- INFOS CONSULTATION --}}
        <div class="col-lg-4 mb-4">

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user-md me-2"></i>Médecin
                </div>
                <div class="card-body text-center">
                    @if($consultation->medecin->user->photo)
                        <img src="{{ asset('storage/' . $consultation->medecin->user->photo) }}"
                            style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);"
                            class="mb-3">
                    @else
                        <div style="width:80px;height:80px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border:3px solid var(--primary);">
                            <i class="fas fa-user-md fa-2x" style="color:var(--primary);"></i>
                        </div>
                    @endif
                    <h6 class="fw-bold">
                        Dr. {{ $consultation->medecin->user->prenom }}
                        {{ $consultation->medecin->user->nom }}
                    </h6>
                    <span class="badge bg-success">{{ $consultation->medecin->specialite->nom }}</span>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Informations
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Date</span>
                        <span class="fw-500">
                            {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Motif</span>
                        <span class="fw-500">{{ $consultation->motif ?? '—' }}</span>
                    </div>
                    @if($consultation->rendezVous)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Heure RDV</span>
                        <span class="fw-500">{{ $consultation->rendezVous->heure_debut }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Statut</span>
                        @if($consultation->statut === 'terminee')
                            <span class="badge bg-success">Terminée</span>
                        @else
                            <span class="badge bg-warning text-dark">En cours</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- DIAGNOSTIC ET DOCUMENTS --}}
        <div class="col-lg-8">

            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clipboard-list me-2"></i>Diagnostic & Notes
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <p class="text-muted mb-2" style="font-size:0.85rem;">
                            <i class="fas fa-stethoscope me-1"></i>Diagnostic
                        </p>
                        <div style="background:#f8f9fa;border-radius:10px;padding:15px;">
                            {{ $consultation->diagnostic ?? 'Aucun diagnostic enregistré.' }}
                        </div>
                    </div>
                    <div>
                        <p class="text-muted mb-2" style="font-size:0.85rem;">
                            <i class="fas fa-pen me-1"></i>Notes du Médecin
                        </p>
                        <div style="background:#f8f9fa;border-radius:10px;padding:15px;">
                            {{ $consultation->notes_medecin ?? 'Aucune note enregistrée.' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- DOCUMENTS --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-medical me-2"></i>Documents Médicaux</span>
                    <span class="badge bg-light text-dark">{{ $consultation->documents->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($consultation->documents as $document)
                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:40px;height:40px;background:var(--secondary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-file-medical" style="color:var(--primary);"></i>
                            </div>
                            <div>
                                <div class="fw-500">{{ $document->titre }}</div>
                                <small class="text-muted">
                                    {{ ucfirst($document->type) }} —
                                    {{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        <a href="{{ route('patient.documents.telecharger', $document->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-download me-1"></i>Télécharger
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-file-medical fa-2x mb-2 d-block"></i>
                        Aucun document pour cette consultation.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>
@endsection