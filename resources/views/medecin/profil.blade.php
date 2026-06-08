@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-user-md me-2"></i> Mon Profil</h2>
        <p>Dr. {{ $user->prenom }} {{ $user->nom }} — {{ $medecin->specialite->nom }}</p>
    </div>

    <div class="row">

        {{-- PHOTO --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-camera me-2"></i>Photo de Profil
                </div>
                <div class="card-body text-center">
                    @if($user->photo)
                        <img src="{{ asset($user->photo) }}"
                            style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid var(--primary);"
                            class="mb-3">
                    @else
                        <div style="width:120px;height:120px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border:4px solid var(--primary);">
                            <i class="fas fa-user-md fa-3x" style="color:var(--primary);"></i>
                        </div>
                    @endif

                    <h5 class="fw-bold">Dr. {{ $user->prenom }} {{ $user->nom }}</h5>
                    <span class="badge bg-success mb-3">{{ $medecin->specialite->nom }}</span>

                    <form action="{{ route('medecin.profil.photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror"
                                accept=".jpg,.jpeg,.png">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-upload me-2"></i>Changer la photo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- INFORMATIONS --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i>Modifier mes Informations
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

                    <form action="{{ route('medecin.profil.update') }}" method="POST">
                        @csrf @method('PUT')

                        <h6 class="fw-bold mb-3" style="color:var(--primary);">
                            <i class="fas fa-user me-2"></i>Informations Personnelles
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Nom</label>
                                <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror"
                                    value="{{ old('nom', $user->nom) }}">
                                @error('nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Prénom</label>
                                <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror"
                                    value="{{ old('prenom', $user->prenom) }}">
                                @error('prenom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Email</label>
                                <input type="email" class="form-control bg-light"
                                    value="{{ $user->email }}" disabled>
                                <small class="text-muted">L'email ne peut pas être modifié.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Téléphone</label>
                                <input type="text" name="telephone" class="form-control"
                                    value="{{ old('telephone', $user->telephone) }}"
                                    placeholder="0661XXXXXX">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-500">Spécialité</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $medecin->specialite->nom }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-500">Biographie</label>
                            <textarea name="biographie" class="form-control" rows="4"
                                placeholder="Présentez-vous...">{{ old('biographie', $medecin->biographie) }}</textarea>
                        </div>

                        <hr>
                        <h6 class="fw-bold mb-3" style="color:var(--primary);">
                            <i class="fas fa-lock me-2"></i>Changer le Mot de Passe
                            <small class="text-muted fw-normal">(laisser vide pour ne pas changer)</small>
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Nouveau mot de passe</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Minimum 6 caractères">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-500">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control" placeholder="Répéter le mot de passe">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>

                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection