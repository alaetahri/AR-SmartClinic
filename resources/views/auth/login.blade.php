@extends('layouts.app')
@section('title', 'Connexion')

@section('styles')
<style>
    .auth-section { min-height: calc(100vh - 80px); display: flex; align-items: center; padding: 60px 0; background: linear-gradient(135deg, #f4f8fc 0%, #e8f4fd 100%); }
    .auth-card { background: #fff; border-radius: 20px; box-shadow: 0 10px 40px rgba(26,111,196,0.12); overflow: hidden; }
    .auth-left { background: linear-gradient(135deg, #1a6fc4 0%, #0d4f9e 50%, #17a2b8 100%); padding: 50px 40px; color: #fff; display: flex; flex-direction: column; justify-content: center; }
    .auth-left h3 { font-size: 1.8rem; font-weight: 700; margin-bottom: 15px; }
    .auth-left p { opacity: 0.85; line-height: 1.7; font-size: 0.95rem; }
    .auth-feature { background: rgba(255,255,255,0.1); border-radius: 10px; padding: 12px 16px; margin-bottom: 12px; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.15); }
    .auth-right { padding: 50px 40px; }
    .auth-title { font-size: 1.8rem; font-weight: 700; color: #2c3e50; margin-bottom: 5px; }
    .auth-subtitle { color: #6c757d; font-size: 0.95rem; margin-bottom: 30px; }
    .form-label { font-weight: 500; color: #2c3e50; font-size: 0.9rem; margin-bottom: 6px; }
    .form-control { border-radius: 10px; border: 2px solid #e9ecef; padding: 12px 15px; font-size: 0.95rem; transition: border-color 0.3s; }
    .form-control:focus { border-color: #1a6fc4; box-shadow: 0 0 0 0.2rem rgba(26,111,196,0.1); }
    .btn-login { background: linear-gradient(135deg, #1a6fc4, #17a2b8); border: none; border-radius: 10px; padding: 13px; font-size: 1rem; font-weight: 600; color: #fff; width: 100%; transition: all 0.3s; }
    .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(26,111,196,0.3); color: #fff; }
    .divider { text-align: center; position: relative; margin: 20px 0; }
    .divider::before { content: ''; position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e9ecef; }
    .divider span { background: #fff; padding: 0 15px; color: #6c757d; font-size: 0.85rem; position: relative; }
</style>
@endsection

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="auth-card">
                    <div class="row g-0">

                        <!-- GAUCHE -->
                        <div class="col-lg-5 auth-left d-none d-lg-flex">
                            <div>
                                <div class="mb-4">
                                    <div style="width:60px;height:60px;background:rgba(255,255,255,0.2);border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-heartbeat" style="font-size:1.8rem;"></i>
                                    </div>
                                </div>
                                <h3>Bienvenue sur AR SmartClinic</h3>
                                <p>Connectez-vous pour accéder à votre espace médical personnalisé.</p>
                                <div class="mt-4">
                                    <div class="auth-feature"><i class="fas fa-calendar-check me-2" style="color:#7dd3fc;"></i> Gérez vos rendez-vous facilement</div>
                                    <div class="auth-feature"><i class="fas fa-robot me-2" style="color:#7dd3fc;"></i> Orientation médicale par IA</div>
                                    <div class="auth-feature"><i class="fas fa-folder-medical me-2" style="color:#7dd3fc;"></i> Accédez à votre dossier médical</div>
                                    <div class="auth-feature"><i class="fas fa-file-download me-2" style="color:#7dd3fc;"></i> Téléchargez vos documents</div>
                                </div>
                            </div>
                        </div>

                        <!-- DROITE : formulaire -->
                        <div class="col-lg-7 auth-right">
                            <h2 class="auth-title"><i class="fas fa-sign-in-alt me-2" style="color:#1a6fc4;"></i> Connexion</h2>
                            <p class="auth-subtitle">Entrez vos identifiants pour accéder à votre espace</p>

                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                                </div>
                            @endif

                            <form action="{{ route('login.post') }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label"><i class="fas fa-envelope me-1" style="color:#1a6fc4;"></i> Adresse email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="exemple@email.com" value="{{ old('email') }}" autofocus>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label"><i class="fas fa-lock me-1" style="color:#1a6fc4;"></i> Mot de passe</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Votre mot de passe">
                                    @error('password')<div class="text-danger mt-1" style="font-size:0.85rem;">{{ $message }}</div>@enderror
                                </div>

                                <button type="submit" class="btn btn-login mb-3">
                                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                                </button>

                                <div class="divider"><span>Pas encore de compte ?</span></div>

                                <div class="text-center">
                                    <a href="{{ route('register') }}" class="btn btn-outline-primary w-100" style="border-radius:10px;padding:12px;font-weight:500;">
                                        <i class="fas fa-user-plus me-2"></i> Créer un compte patient
                                    </a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection