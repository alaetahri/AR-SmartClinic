@extends('layouts.app')

@section('title', 'Modifier Personnel')

@section('content')
<div class="container py-4">

    {{-- TITRE --}}
    <div class="page-title">
        <h2><i class="fas fa-user-edit me-2"></i> Modifier le Personnel</h2>
        <p>Modification des informations de {{ $user->prenom }} {{ $user->nom }}</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i>
                    Informations du {{ $user->role === 'medecin' ? 'Médecin' : 'Secrétaire' }}
                </div>
                <div class="card-body p-4">

                    {{-- ERREURS --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.personnel.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- INFORMATIONS PERSONNELLES --}}
                        <h6 class="fw-bold mb-3" style="color:var(--primary);">
                            <i class="fas fa-user me-2"></i>Informations Personnelles
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                    value="{{ old('nom', $user->nom) }}" placeholder="Nom de famille">
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                    value="{{ old('prenom', $user->prenom) }}" placeholder="Prénom">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" placeholder="email@exemple.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Téléphone</label>
                                <input type="text" name="telephone" class="form-control"
                                    value="{{ old('telephone', $user->telephone) }}" placeholder="0661XXXXXX">
                            </div>
                        </div>

                        {{-- RÔLE (affiché mais non modifiable) --}}
                        <div class="mb-3">
                            <label class="form-label fw-500">Rôle</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $user->role === 'medecin' ? 'Médecin' : 'Secrétaire' }}" disabled>
                        </div>

                        {{-- MOT DE PASSE --}}
                        <hr>
                        <h6 class="fw-bold mb-3" style="color:var(--primary);">
                            <i class="fas fa-lock me-2"></i>Changer le Mot de Passe
                            <small class="text-muted fw-normal">(laisser vide pour ne pas changer)</small>
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Nouveau mot de passe</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimum 6 caractères">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- INFORMATIONS MÉDECIN --}}
                        @if($user->role === 'medecin' && $medecin)
                            <hr>
                            <h6 class="fw-bold mb-3" style="color:var(--primary);">
                                <i class="fas fa-user-md me-2"></i>Informations Médicales
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-500">Spécialité <span class="text-danger">*</span></label>
                                    <select name="specialite_id" class="form-select @error('specialite_id') is-invalid @enderror">
                                        <option value="">-- Choisir une spécialité --</option>
                                        @foreach($specialites as $specialite)
                                            <option value="{{ $specialite->id }}"
                                                {{ old('specialite_id', $medecin->specialite_id) == $specialite->id ? 'selected' : '' }}>
                                                {{ $specialite->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialite_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-500">Numéro d'Ordre <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_ordre" class="form-control @error('numero_ordre') is-invalid @enderror"
                                        value="{{ old('numero_ordre', $medecin->numero_ordre) }}" placeholder="Ex: MG-001">
                                    @error('numero_ordre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-500">Biographie</label>
                                <textarea name="biographie" class="form-control" rows="4"
                                    placeholder="Présentation du médecin...">{{ old('biographie', $medecin->biographie) }}</textarea>
                            </div>
                        @endif

                        {{-- BOUTONS --}}
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                            <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-secondary px-4">
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