@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-calendar-plus me-2"></i> Nouveau Rendez-vous</h2>
        <p>Créer un rendez-vous pour un patient</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ÉTAPE 1 : RECHERCHE PATIENT PAR CIN --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-search me-2"></i>Étape 1 — Rechercher le Patient par CIN
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('secretaire.rendez-vous.create') }}">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <input type="text" name="cin" class="form-control"
                                    placeholder="Entrez le numéro CIN du patient..."
                                    value="{{ request('cin') }}" autofocus>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i>Rechercher
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(request('cin') && !$patientSelectionne)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucun patient trouvé avec le CIN <strong>{{ request('cin') }}</strong>.
                            <a href="{{ route('secretaire.patients.create') }}" class="alert-link ms-2">
                                Créer ce patient ?
                            </a>
                        </div>
                    @endif

                    @if($patientSelectionne)
                        <div class="alert alert-success mt-3 mb-0 d-flex align-items-center gap-3">
                            @if($patientSelectionne->user->photo)
                                <img src="{{ asset('storage/' . $patientSelectionne->user->photo) }}"
                                    style="width:45px;height:45px;border-radius:50%;object-fit:cover;">
                            @else
                                <div style="width:45px;height:45px;background:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-user" style="color:var(--primary);"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">
                                    {{ $patientSelectionne->user->prenom }} {{ $patientSelectionne->user->nom }}
                                </div>
                                <small>
                                    CIN : {{ $patientSelectionne->cin }}
                                    @if($patientSelectionne->user->telephone)
                                        — Tél : {{ $patientSelectionne->user->telephone }}
                                    @endif
                                    @if($patientSelectionne->groupe_sanguin)
                                        — Groupe : <strong>{{ $patientSelectionne->groupe_sanguin }}</strong>
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ÉTAPE 2 : FORMULAIRE RDV --}}
            @if($patientSelectionne)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-2"></i>Étape 2 — Informations du Rendez-vous
                </div>
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('secretaire.rendez-vous.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="patient_id" value="{{ $patientSelectionne->id }}">

                        <div class="mb-3">
                            <label class="form-label fw-500">Médecin <span class="text-danger">*</span></label>
                            <select name="medecin_id" class="form-select @error('medecin_id') is-invalid @enderror">
                                <option value="">-- Choisir un médecin --</option>
                                @foreach($medecins as $medecin)
                                    <option value="{{ $medecin->id }}"
                                        {{ old('medecin_id') == $medecin->id ? 'selected' : '' }}>
                                        Dr. {{ $medecin->user->prenom }} {{ $medecin->user->nom }}
                                        — {{ $medecin->specialite->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('medecin_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date_rendez_vous"
                                    class="form-control @error('date_rendez_vous') is-invalid @enderror"
                                    value="{{ old('date_rendez_vous') }}"
                                    min="{{ today()->toDateString() }}">
                                @error('date_rendez_vous')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Heure <span class="text-danger">*</span></label>
                                <select name="heure_debut" class="form-select @error('heure_debut') is-invalid @enderror">
                                    <option value="">-- Choisir l'heure --</option>
                                    @php
                                        $d = strtotime('00:00');
                                        $f = strtotime('23:30');
                                        while ($d <= $f) {
                                            $h = date('H:i', $d);
                                            $selected = old('heure_debut') == $h ? 'selected' : '';
                                            echo "<option value=\"$h\" $selected>$h</option>";
                                            $d = strtotime('+30 minutes', $d);
                                        }
                                    @endphp
                                </select>
                                @error('heure_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-500">Motif</label>
                            <input type="text" name="motif" class="form-control"
                                value="{{ old('motif') }}"
                                placeholder="Motif de la consultation...">
                        </div>

                        <div class="alert alert-info" style="border-radius:10px;">
                            <i class="fas fa-info-circle me-2"></i>
                            Durée : <strong>30 minutes</strong> par rendez-vous.
                            Le créneau sera vérifié automatiquement.
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Créer le RDV
                            </button>
                            <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection