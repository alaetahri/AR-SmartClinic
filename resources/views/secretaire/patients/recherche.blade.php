@extends('layouts.app')

@section('title', 'Recherche Patient')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-search me-2"></i> Recherche Rapide Patient</h2>
        <p>Rechercher un patient par son numéro CIN</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            {{-- FORMULAIRE RECHERCHE --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-id-card me-2"></i>Recherche par CIN
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('secretaire.patients.recherche') }}">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <input type="text" name="cin" class="form-control"
                                    placeholder="Entrez le numéro CIN..."
                                    value="{{ request('cin') }}" autofocus>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- RÉSULTAT --}}
            @if(isset($patient) && $patient)
            <div class="card">
                <div class="card-header" style="background:var(--accent);">
                    <i class="fas fa-user-check me-2"></i>Patient Trouvé
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if($patient->user->photo)
                            <img src="{{ asset($patient->user->photo) }}"
                                style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);">
                        @else
                            <div style="width:70px;height:70px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;border:3px solid var(--primary);">
                                <i class="fas fa-user fa-xl" style="color:var(--primary);"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="fw-bold mb-1">{{ $patient->user->prenom }} {{ $patient->user->nom }}</h5>
                            <small class="text-muted">{{ $patient->user->email }}</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <p class="text-muted mb-0" style="font-size:0.8rem;">CIN</p>
                            <p class="fw-500">{{ $patient->cin }}</p>
                        </div>
                        <div class="col-6 mb-2">
                            <p class="text-muted mb-0" style="font-size:0.8rem;">Téléphone</p>
                            <p class="fw-500">{{ $patient->user->telephone ?? '—' }}</p>
                        </div>
                        <div class="col-6 mb-2">
                            <p class="text-muted mb-0" style="font-size:0.8rem;">Sexe</p>
                            <p class="fw-500">{{ ucfirst($patient->sexe) }}</p>
                        </div>
                        <div class="col-6 mb-2">
                            <p class="text-muted mb-0" style="font-size:0.8rem;">Groupe sanguin</p>
                            @if($patient->groupe_sanguin)
                                <span class="badge bg-danger">{{ $patient->groupe_sanguin }}</span>
                            @else
                                <p class="fw-500">—</p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('secretaire.rendez-vous.create', ['patient_id' => $patient->id]) }}"
                            class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Créer un RDV
                        </a>
                        <a href="{{ route('secretaire.patients.show', $patient->id) }}"
                            class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i>Voir la fiche
                        </a>
                    </div>
                </div>
            </div>
            @elseif(request('cin'))
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Aucun patient trouvé avec le CIN <strong>{{ request('cin') }}</strong>.
                <a href="{{ route('secretaire.patients.create') }}" class="alert-link ms-2">
                    Créer ce patient ?
                </a>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection