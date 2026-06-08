@extends('layouts.app')

@section('title', 'Mon Dossier Médical')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-folder-medical me-2"></i> Mon Dossier Médical</h2>
        @if($patient->dossierMedical)
            <p>N° {{ $patient->dossierMedical->numero_dossier }}
                — Ouvert le {{ \Carbon\Carbon::parse($patient->dossierMedical->date_ouverture)->format('d/m/Y') }}</p>
        @endif
    </div>

    <div class="row">

        {{-- INFOS PERSONNELLES --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Mes Informations
                </div>
                <div class="card-body text-center">
                    @if($patient->user->photo)
                        <img src="{{ asset($patient->user->photo) }}"
                            style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);"
                            class="mb-3">
                    @else
                        <div style="width:90px;height:90px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border:3px solid var(--primary);">
                            <i class="fas fa-user fa-2x" style="color:var(--primary);"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold">{{ $patient->user->prenom }} {{ $patient->user->nom }}</h5>

                    @if($patient->groupe_sanguin)
                        <span class="badge bg-danger mb-3">{{ $patient->groupe_sanguin }}</span>
                    @endif

                    <div class="text-start mt-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">CIN</span>
                            <span class="fw-500">{{ $patient->cin }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Sexe</span>
                            <span class="fw-500">{{ ucfirst($patient->sexe) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Naissance</span>
                            <span class="fw-500">
                                {{ $patient->date_naissance
                                    ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y')
                                    : '—' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Téléphone</span>
                            <span class="fw-500">{{ $patient->user->telephone ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DOSSIER MÉDICAL --}}
        <div class="col-lg-8 mb-4">
            @if($patient->dossierMedical)

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header" style="background:#dc3545;">
                            <i class="fas fa-allergies me-2"></i>Allergies
                        </div>
                        <div class="card-body">
                            @if($patient->dossierMedical->allergies)
                                <p class="mb-0">{{ $patient->dossierMedical->allergies }}</p>
                            @else
                                <p class="text-muted mb-0"><i>Aucune allergie connue.</i></p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header" style="background:#fd7e14;">
                            <i class="fas fa-heartbeat me-2"></i>Maladies Chroniques
                        </div>
                        <div class="card-body">
                            @if($patient->dossierMedical->maladies_chroniques)
                                <p class="mb-0">{{ $patient->dossierMedical->maladies_chroniques }}</p>
                            @else
                                <p class="text-muted mb-0"><i>Aucune maladie chronique connue.</i></p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header" style="background:#6f42c1;">
                            <i class="fas fa-history me-2"></i>Antécédents Médicaux
                        </div>
                        <div class="card-body">
                            @if($patient->dossierMedical->antecedents_medicaux)
                                <p class="mb-0">{{ $patient->dossierMedical->antecedents_medicaux }}</p>
                            @else
                                <p class="text-muted mb-0"><i>Aucun antécédent médical.</i></p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header" style="background:#20c997;">
                            <i class="fas fa-pills me-2"></i>Traitements en Cours
                        </div>
                        <div class="card-body">
                            @if($patient->dossierMedical->traitements_en_cours)
                                <p class="mb-0">{{ $patient->dossierMedical->traitements_en_cours }}</p>
                            @else
                                <p class="text-muted mb-0"><i>Aucun traitement en cours.</i></p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-comment-medical me-2"></i>Observations Générales
                        </div>
                        <div class="card-body">
                            @if($patient->dossierMedical->observations_generales)
                                <p class="mb-0">{{ $patient->dossierMedical->observations_generales }}</p>
                            @else
                                <p class="text-muted mb-0"><i>Aucune observation générale.</i></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @else
            <div class="card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                    <h5>Dossier médical non trouvé.</h5>
                    <p>Contactez la secrétaire pour créer votre dossier.</p>
                </div>
            </div>
            @endif
        </div>

    </div>

</div>
@endsection