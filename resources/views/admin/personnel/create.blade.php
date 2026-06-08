@extends('layouts.app')
@section('title', 'Ajouter Personnel')

@section('styles')
<style>
    .form-label { font-weight: 500; color: #2c3e50; font-size: 0.9rem; }
    .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 11px 15px; }
    .form-control:focus, .form-select:focus { border-color: #1a6fc4; box-shadow: none; }
    .section-divider { background: #f4f8fc; border-radius: 10px; padding: 10px 15px; margin: 20px 0 15px; font-weight: 600; color: #1a6fc4; }
    #medecin-fields { display: none; }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-user-plus me-2"></i> Ajouter un membre du personnel</h2>
        <p>Médecin ou secrétaire</p>
    </div>

    <div class="card">
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form action="{{ route('admin.personnel.store') }}" method="POST">
                @csrf

                <div class="section-divider"><i class="fas fa-user me-2"></i> Informations générales</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}" placeholder="Nom de famille">
                        @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" value="{{ old('prenom') }}" placeholder="Prénom">
                        @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@exemple.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}" placeholder="+212 6XX-XXXXXX">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 caractères">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" onchange="toggleMedecinFields(this.value)">
                            <option value="">-- Choisir --</option>
                            <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                            <option value="secretaire" {{ old('role') == 'secretaire' ? 'selected' : '' }}>Secrétaire</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- CHAMPS MÉDECIN -->
                <div id="medecin-fields" class="{{ old('role') == 'medecin' ? 'd-block' : '' }}">
                    <div class="section-divider"><i class="fas fa-user-md me-2"></i> Informations médecin</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Spécialité <span class="text-danger">*</span></label>
                            <select name="specialite_id" class="form-select @error('specialite_id') is-invalid @enderror">
                                <option value="">-- Choisir une spécialité --</option>
                                @foreach($specialites as $specialite)
                                    <option value="{{ $specialite->id }}" {{ old('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                        {{ $specialite->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialite_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Numéro d'ordre <span class="text-danger">*</span></label>
                            <input type="text" name="numero_ordre" class="form-control @error('numero_ordre') is-invalid @enderror" value="{{ old('numero_ordre') }}" placeholder="Ex: MED-12345">
                            @error('numero_ordre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Biographie</label>
                            <textarea name="biographie" class="form-control" rows="3" placeholder="Description du médecin...">{{ old('biographie') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Enregistrer
                    </button>
                    <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleMedecinFields(role) {
    var fields = document.getElementById('medecin-fields');
    fields.style.display = role === 'medecin' ? 'block' : 'none';
}
// Afficher si old('role') = medecin
document.addEventListener('DOMContentLoaded', function() {
    var role = document.querySelector('[name="role"]').value;
    toggleMedecinFields(role);
});
</script>
@endsection