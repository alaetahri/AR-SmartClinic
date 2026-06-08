@extends('layouts.app')

@section('title', 'Prendre un Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-calendar-plus me-2"></i> Prendre un Rendez-vous</h2>
        <p>Choisissez un médecin et un créneau disponible</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ÉTAPE 1 : FILTRE SPÉCIALITÉ --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-stethoscope me-2"></i>Étape 1 — Filtrer par Spécialité
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('patient.rendez-vous.create') }}">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <select name="specialite_id" class="form-select">
                                    <option value="">-- Toutes les spécialités --</option>
                                    @foreach($specialites as $specialite)
                                        <option value="{{ $specialite->id }}"
                                            {{ $specialiteSelectionnee?->id == $specialite->id ? 'selected' : '' }}>
                                            {{ $specialite->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>Filtrer
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('patient.rendez-vous.create') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-1"></i>Tout
                                </a>
                            </div>
                        </div>
                    </form>

                    @if($specialiteSelectionnee)
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            Spécialité sélectionnée : <strong>{{ $specialiteSelectionnee->nom }}</strong>
                            — {{ $medecins->count() }} médecin(s) disponible(s)
                        </div>
                    @endif
                </div>
            </div>

            {{-- ÉTAPE 2 : FORMULAIRE RDV --}}
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

                    <form action="{{ route('patient.rendez-vous.store') }}" method="POST">
                        @csrf

                        {{-- Garder la spécialité sélectionnée lors du store --}}
                        @if($specialiteSelectionnee)
                            <input type="hidden" name="specialite_id" value="{{ $specialiteSelectionnee->id }}">
                        @endif

                        {{-- MÉDECIN --}}
                        <div class="mb-3">
                            <label class="form-label fw-500">Médecin <span class="text-danger">*</span></label>
                            @if($medecins->count() === 0)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Aucun médecin disponible pour cette spécialité.
                                </div>
                            @else
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
                                @error('medecin_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @endif
                        </div>

                        <div class="row">
                            {{-- DATE --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date_rendez_vous"
                                    class="form-control @error('date_rendez_vous') is-invalid @enderror"
                                    value="{{ old('date_rendez_vous') }}"
                                    min="{{ today()->toDateString() }}">
                                @error('date_rendez_vous') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- HEURE --}}
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
                                @error('heure_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- MOTIF --}}
                        <div class="mb-4">
                            <label class="form-label fw-500">Motif de consultation</label>
                            <input type="text" name="motif" class="form-control"
                                value="{{ old('motif') }}"
                                placeholder="Décrivez brièvement votre motif...">
                        </div>

                        <div class="alert alert-info" style="border-radius:10px;">
                            <i class="fas fa-info-circle me-2"></i>
                            Durée : <strong>30 minutes</strong> par consultation.
                            Votre RDV sera en attente de confirmation.
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary px-4"
                                {{ $medecins->count() === 0 ? 'disabled' : '' }}>
                                <i class="fas fa-calendar-check me-2"></i>Confirmer le RDV
                            </button>
                            <a href="{{ route('patient.rendez-vous.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection