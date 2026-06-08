@extends('layouts.app')

@section('title', 'Dossier Patient')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-folder-medical me-2"></i> Dossier Médical</h2>
            <p>{{ $patient->user->prenom }} {{ $patient->user->nom }}</p>
        </div>
        <a href="{{ route('medecin.patients.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">

        {{-- INFOS PATIENT --}}
        <div class="col-lg-4 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Informations Patient
                </div>
                <div class="card-body text-center">
                    @if($patient->user->photo)
                        <img src="{{ asset('storage/' . $patient->user->photo) }}"
                            style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);"
                            class="mb-3">
                    @else
                        <div style="width:80px;height:80px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border:3px solid var(--primary);">
                            <i class="fas fa-user fa-2x" style="color:var(--primary);"></i>
                        </div>
                    @endif
                    <h6 class="fw-bold">{{ $patient->user->prenom }} {{ $patient->user->nom }}</h6>
                    <small class="text-muted d-block mb-2">{{ $patient->user->email }}</small>
                    @if($patient->groupe_sanguin)
                        <span class="badge bg-danger">{{ $patient->groupe_sanguin }}</span>
                    @endif

                    <div class="text-start mt-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">CIN</span>
                            <span class="fw-500">{{ $patient->cin }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Téléphone</span>
                            <span class="fw-500">{{ $patient->user->telephone ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Sexe</span>
                            <span class="fw-500">{{ ucfirst($patient->sexe) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Naissance</span>
                            <span class="fw-500">
                                {{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') : '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DOSSIER --}}
            @if($patient->dossierMedical)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-folder me-2"></i>Dossier
                    <span class="badge bg-light text-dark ms-2">{{ $patient->dossierMedical->numero_dossier }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Allergies</p>
                        <p class="mb-0">{{ $patient->dossierMedical->allergies ?? '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Maladies chroniques</p>
                        <p class="mb-0">{{ $patient->dossierMedical->maladies_chroniques ?? '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Antécédents médicaux</p>
                        <p class="mb-0">{{ $patient->dossierMedical->antecedents_medicaux ?? '—' }}</p>
                    </div>
                    <div class="mb-0">
                        <p class="text-muted mb-1" style="font-size:0.8rem;">Traitements en cours</p>
                        <p class="mb-0">{{ $patient->dossierMedical->traitements_en_cours ?? '—' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- CONSULTATIONS --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-notes-medical me-2"></i>Consultations</span>
                    <span class="badge bg-light text-dark">{{ $patient->consultations->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($patient->consultations as $consultation)
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="fw-600">
                                    {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                                </span>
                                @if($consultation->statut === 'terminee')
                                    <span class="badge bg-success ms-2">Terminée</span>
                                @else
                                    <span class="badge bg-warning text-dark ms-2">En cours</span>
                                @endif
                            </div>
                            <a href="{{ route('medecin.consultations.show', $consultation->id) }}"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Voir
                            </a>
                        </div>

                        @if($consultation->motif)
                            <p class="text-muted mb-1" style="font-size:0.85rem;">
                                <strong>Motif :</strong> {{ $consultation->motif }}
                            </p>
                        @endif

                        @if($consultation->diagnostic)
                            <p class="text-muted mb-1" style="font-size:0.85rem;">
                                <strong>Diagnostic :</strong> {{ \Str::limit($consultation->diagnostic, 100) }}
                            </p>
                        @endif

                        @if($consultation->documents->count() > 0)
                            <div class="mt-2">
                                @foreach($consultation->documents as $doc)
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        @php
                                            $ext = strtolower(pathinfo($doc->fichier, PATHINFO_EXTENSION));
                                        @endphp
                                        @if(in_array($ext, ['jpg','jpeg','png']))
                                            <img src="{{ asset('storage/' . $doc->fichier) }}"
                                                style="width:40px;height:40px;object-fit:cover;border-radius:5px;border:1px solid #dee2e6;">
                                        @elseif($ext === 'pdf')
                                            <div style="width:40px;height:40px;background:#fff5f5;border-radius:5px;display:flex;align-items:center;justify-content:center;border:1px solid #dee2e6;">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                            </div>
                                        @else
                                            <div style="width:40px;height:40px;background:#f8f9fa;border-radius:5px;display:flex;align-items:center;justify-content:center;border:1px solid #dee2e6;">
                                                <i class="fas fa-file text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="fw-500" style="font-size:0.85rem;">{{ $doc->titre }}</span>
                                            <small class="text-muted d-block">{{ ucfirst($doc->type) }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-notes-medical fa-2x mb-2 d-block"></i>
                        Aucune consultation enregistrée.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

</div>
@endsection