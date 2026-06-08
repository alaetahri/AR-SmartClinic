@extends('layouts.app')
@section('title', 'Inscription')

@section('styles')
<style>
    .auth-section { padding: 60px 0; background: linear-gradient(135deg, #f4f8fc 0%, #e8f4fd 100%); }
    .auth-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 40px rgba(26,111,196,0.12); overflow: hidden; }
    .auth-header { background: linear-gradient(135deg, #1a6fc4, #17a2b8); padding: 30px 40px; color: #fff; }
    .auth-header h2 { font-size: 1.8rem; font-weight: 700; margin: 0; }
    .auth-header p { opacity: 0.85; margin: 8px 0 0; font-size: 0.95rem; }
    .auth-body { padding: 40px; }
    .form-label { font-weight: 500; color: #2c3e50; font-size: 0.9rem; margin-bottom: 6px; }
    .form-control, .form-select { border-radius: 10px; border: 2px solid #e9ecef; padding: 11px 15px; font-size: 0.95rem; transition: border-color 0.3s; }
    .form-control:focus, .form-select:focus { border-color: #1a6fc4; box-shadow: 0 0 0 0.2rem rgba(26,111,196,0.1); }
    .section-divider { background: #f4f8fc; border-radius: 10px; padding: 10px 15px; margin: 25px 0 20px; font-weight: 600; color: #1a6fc4; font-size: 0.95rem; }
    .btn-register { background: linear-gradient(135deg, #1a6fc4, #17a2b8); border: none; border-radius: 10px; padding: 13px; font-size: 1rem; font-weight: 600; color: #fff; width: 100%; transition: all 0.3s; }
    .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(26,111,196,0.3); color: #fff; }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="auth-card">

                    <div class="auth-header">
                        <div class="d-flex align-items-center">
                            <div style="width:50px;height:50px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                                <i class="fas fa-user-plus" style="font-size:1.4rem;"></i>
                            </div>
                            <div>
                                <h2>Créer un compte patient</h2>
                                <p>Remplissez le formulaire pour rejoindre AR SmartClinic</p>
                            </div>
                        </div>
                    </div>

                    <div class="auth-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Veuillez corriger les erreurs suivantes :</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register.post') }}" method="POST">
                            @csrf

                            <!-- Infos personnelles -->
                            <div class="section-divider"><i class="fas fa-user me-2"></i> Informations personnelles</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" placeholder="Votre nom" value="{{ old('nom') }}">
                                    @error('nom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="prenom" class="form-control @error('prenom') is-invalid @enderror" placeholder="Votre prénom" value="{{ old('prenom') }}">
                                    @error('prenom')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="exemple@email.com" value="{{ old('email') }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="telephone" class="form-control" placeholder="+212 6XX-XXXXXX" value="{{ old('telephone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 caractères">
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Répétez le mot de passe">
                                </div>
                            </div>

                            <!-- Infos médicales -->
                            <div class="section-divider"><i class="fas fa-notes-medical me-2"></i> Informations médicales</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">CIN <span class="text-danger">*</span></label>
                                    <input type="text" name="cin" class="form-control @error('cin') is-invalid @enderror" placeholder="Ex: AB123456" value="{{ old('cin') }}">
                                    @error('cin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de naissance <span class="text-danger">*</span></label>
                                    <input type="date" name="date_naissance" class="form-control @error('date_naissance') is-invalid @enderror" value="{{ old('date_naissance') }}">
                                    @error('date_naissance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sexe <span class="text-danger">*</span></label>
                                    <select name="sexe" class="form-select @error('sexe') is-invalid @enderror">
                                        <option value="">-- Choisir --</option>
                                        <option value="homme" {{ old('sexe') == 'homme' ? 'selected' : '' }}>Homme</option>
                                        <option value="femme" {{ old('sexe') == 'femme' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                    @error('sexe')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Groupe sanguin</label>
                                    <select name="groupe_sanguin" class="form-select">
                                        <option value="">-- Choisir --</option>
                                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $groupe)
                                            <option value="{{ $groupe }}" {{ old('groupe_sanguin') == $groupe ? 'selected' : '' }}>{{ $groupe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <input type="text" name="adresse" class="form-control" placeholder="Votre adresse complète" value="{{ old('adresse') }}">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-register">
                                    <i class="fas fa-user-plus me-2"></i> Créer mon compte
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <span style="color:#6c757d;font-size:0.9rem;">Vous avez déjà un compte ?</span>
                                <a href="{{ route('login') }}" style="color:#1a6fc4;font-weight:600;font-size:0.9rem;"> Se connecter</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection