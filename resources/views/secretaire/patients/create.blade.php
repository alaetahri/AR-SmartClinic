@extends('layouts.app')

@section('title', 'Nouveau Patient')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-user-plus me-2"></i> Nouveau Patient</h2>
        <p>Enregistrement d'un nouveau patient dans le système</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-hospital-user me-2"></i>Informations du Patient
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

                    <form action="{{ route('secretaire.patients.store') }}" method="POST">
                        @csrf

                        {{-- COMPTE --}}
                        <h6 class="fw-bold mb-3" style="color:var(--primary);">
                            <i class="fas fa-user me-2"></i>Informations Personnelles
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="nom"
                                    class="form-control @error('nom') is-invalid @enderror"
                                    value="{{ old('nom') }}" placeholder="Nom de famille">
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Prénom <span class="text-danger">*</span></label>
                                <input type="text" name="prenom"
                                    class="form-control @error('prenom') is-invalid @enderror"
                                    value="{{ old('prenom') }}" placeholder="Prénom">
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="email@exemple.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Téléphone</label>
                                <input type="text" name="telephone" class="form-control"
                                    value="{{ old('telephone') }}" placeholder="0661XXXXXX">
                            </div>
                        </div>

                        <hr>
                        {{-- INFOS MÉDICALES --}}
                        <h6 class="fw-bold mb-3" style="color:var(--primary);">
                            <i class="fas fa-notes-medical me-2"></i>Informations Médicales
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">CIN <span class="text-danger">*</span></label>
                                <input type="text" name="cin"
                                    class="form-control @error('cin') is-invalid @enderror"
                                    value="{{ old('cin') }}" placeholder="AB123456">
                                @error('cin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Date de Naissance <span class="text-danger">*</span></label>
                                <input type="date" name="date_naissance"
                                    class="form-control @error('date_naissance') is-invalid @enderror"
                                    value="{{ old('date_naissance') }}">
                                @error('date_naissance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-500">Sexe <span class="text-danger">*</span></label>
                                <select name="sexe" class="form-select @error('sexe') is-invalid @enderror">
                                    <option value="">-- Choisir --</option>
                                    <option value="homme" {{ old('sexe') == 'homme' ? 'selected' : '' }}>Homme</option>
                                    <option value="femme" {{ old('sexe') == 'femme' ? 'selected' : '' }}>Femme</option>
                                </select>
                                @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-500">Groupe Sanguin</label>
                                <select name="groupe_sanguin" class="form-select">
                                    <option value="">-- Choisir --</option>
                                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gs)
                                        <option value="{{ $gs }}" {{ old('groupe_sanguin') == $gs ? 'selected' : '' }}>
                                            {{ $gs }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-500">Adresse</label>
                                <input type="text" name="adresse" class="form-control"
                                    value="{{ old('adresse') }}" placeholder="Adresse complète">
                            </div>
                        </div>

                        <div class="alert alert-info mt-2" style="border-radius:10px;">
                            <i class="fas fa-info-circle me-2"></i>
                            Un mot de passe temporaire sera généré automatiquement et affiché après la création.
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Créer le Patient
                            </button>
                            <a href="{{ route('secretaire.patients.index') }}" class="btn btn-outline-secondary px-4">
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