<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AR SmartClinic - @yield('title', 'Bienvenue')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }
        :root {
            --primary: #1a6fc4;
            --primary-dark: #135aa0;
            --secondary: #e8f4fd;
            --accent: #17a2b8;
            --text-dark: #2c3e50;
        }
        body { background-color: #f4f8fc; color: var(--text-dark); }
        .navbar { background: #fff; box-shadow: 0 2px 15px rgba(26,111,196,0.1); padding: 10px 0; }
        .navbar-brand span { font-size: 1.3rem; font-weight: 700; color: var(--primary); margin-left: 8px; }
        .navbar-toggler { border: none; font-size: 1.4rem; color: var(--primary); }
        .navbar-toggler:focus { box-shadow: none; }
        .nav-link { color: var(--text-dark) !important; font-weight: 500; padding: 8px 15px !important; transition: color 0.3s; }
        .nav-link:hover { color: var(--primary) !important; }
        .profile-btn { background: var(--primary); color: #fff !important; border-radius: 25px; padding: 8px 20px !important; font-weight: 500; transition: background 0.3s; }
        .profile-btn:hover { background: var(--primary-dark); color: #fff !important; }
        .profile-photo { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 8px; border: 2px solid #fff; }
        .role-badge { font-size: 0.7rem; padding: 2px 8px; border-radius: 10px; margin-left: 5px; }
        .dropdown-menu { border: none; box-shadow: 0 5px 25px rgba(0,0,0,0.1); border-radius: 12px; padding: 8px; }
        .dropdown-item { border-radius: 8px; padding: 8px 15px; font-size: 0.9rem; transition: background 0.2s; }
        .dropdown-item:hover { background: var(--secondary); color: var(--primary); }
        .notif-badge { position: relative; }
        .notif-badge .badge { position: absolute; top: -5px; right: -5px; font-size: 0.65rem; }
        .footer { background: #1a2a3a; color: #adb5bd; padding: 50px 0 20px; margin-top: 60px; }
        .footer h5 { color: #fff; font-weight: 600; margin-bottom: 20px; }
        .footer a { color: #adb5bd; text-decoration: none; transition: color 0.3s; display: block; margin-bottom: 8px; font-size: 0.9rem; }
        .footer a:hover { color: var(--primary); }
        .footer-bottom { border-top: 1px solid #2d3f50; padding-top: 20px; margin-top: 30px; text-align: center; font-size: 0.85rem; }
        .alert { border: none; border-radius: 10px; }
        .card { border: none; border-radius: 15px; box-shadow: 0 2px 15px rgba(0,0,0,0.07); }
        .card-header { background: var(--primary); color: #fff; border-radius: 15px 15px 0 0 !important; font-weight: 600; }
        .btn-primary { background: var(--primary); border-color: var(--primary); border-radius: 8px; font-weight: 500; }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .page-title { background: linear-gradient(135deg, var(--primary), var(--accent)); color: #fff; padding: 25px 30px; border-radius: 15px; margin-bottom: 25px; }
        .page-title h2 { margin: 0; font-weight: 700; }
        .page-title p { margin: 5px 0 0; opacity: 0.85; font-size: 0.9rem; }
        /* PAGINATION CORRIGEE */
        .pagination { margin-bottom: 0; }
        .page-link { color: var(--primary); border-radius: 8px !important; margin: 0 2px; border: 1px solid #dee2e6; font-size: 0.9rem; padding: 6px 12px; }
        .page-link:hover { background: var(--secondary); color: var(--primary); border-color: var(--primary); }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); color: #fff; }
        .page-item.disabled .page-link { color: #adb5bd; }
        .page-link svg { width: 14px; height: 14px; }
    </style>
    @yield('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
            <div style="width:45px;height:45px;background:var(--primary);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-heartbeat text-white" style="font-size:1.3rem;"></i>
            </div>
            <span>AR SmartClinic</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <i class="fas fa-bars"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('welcome') }}"><i class="fas fa-home me-1"></i> Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="#services"><i class="fas fa-stethoscope me-1"></i> Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#about"><i class="fas fa-info-circle me-1"></i> À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact"><i class="fas fa-phone me-1"></i> Contact</a></li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                @if(session('user_id'))
                    @php
                        $notifCount = \App\Models\Notification::where('user_id', session('user_id'))->where('lu', false)->count();
                        $roleLabels = [
                            'admin'      => ['Directeur',  'bg-danger'],
                            'medecin'    => ['Medecin',    'bg-success'],
                            'secretaire' => ['Secretaire', 'bg-warning text-dark'],
                            'patient'    => ['Patient',    'bg-info text-dark'],
                        ];
                        $roleInfo = $roleLabels[session('user_role')] ?? ['Utilisateur', 'bg-secondary'];
                    @endphp

                    <li class="nav-item me-2">
                        @if(session('user_role') === 'patient')
                            <a class="nav-link notif-badge" href="{{ route('patient.notifications') }}">
                        @else
                            <a class="nav-link notif-badge" href="#">
                        @endif
                            <i class="fas fa-bell" style="font-size:1.2rem;color:var(--primary);"></i>
                            @if($notifCount > 0)
                                <span class="badge bg-danger rounded-pill">{{ $notifCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link profile-btn dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                            @if(session('user_photo'))
                                <img src="{{ asset(session('user_photo')) }}" class="profile-photo" alt="photo">
                            @else
                                <i class="fas fa-user-circle me-2" style="font-size:1.2rem;"></i>
                            @endif
                            {{ session('user_prenom') }} {{ session('user_nom') }}
                            <span class="badge {{ $roleInfo[1] }} role-badge">{{ $roleInfo[0] }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">

                            @if(session('user_role') === 'patient')
                                <li><a class="dropdown-item fw-600" href="{{ route('patient.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('patient.profil') }}">
                                    <i class="fas fa-user me-2 text-primary"></i>Mon Profil
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.rendez-vous.index') }}">
                                    <i class="fas fa-calendar-check me-2 text-primary"></i>Mes Rendez-vous
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.consultations.index') }}">
                                    <i class="fas fa-notes-medical me-2 text-primary"></i>Mes Consultations
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.dossier') }}">
                                    <i class="fas fa-folder-medical me-2 text-primary"></i>Mon Dossier
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.documents.index') }}">
                                    <i class="fas fa-file-medical me-2 text-primary"></i>Mes Documents
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.notifications') }}">
                                    <i class="fas fa-bell me-2 text-primary"></i>Notifications
                                    @if($notifCount > 0)<span class="badge bg-danger ms-1">{{ $notifCount }}</span>@endif
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('patient.assistant') }}">
                                    <i class="fas fa-robot me-2 text-primary"></i>Assistant IA
                                </a></li>

                            @elseif(session('user_role') === 'medecin')
                                <li><a class="dropdown-item fw-600" href="{{ route('medecin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('medecin.profil') }}">
                                    <i class="fas fa-user-md me-2 text-primary"></i>Mon Profil
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('medecin.rendez-vous.index') }}">
                                    <i class="fas fa-calendar me-2 text-primary"></i>Mes Rendez-vous
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('medecin.patients.index') }}">
                                    <i class="fas fa-users me-2 text-primary"></i>Mes Patients
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('medecin.indisponibilites.index') }}">
                                    <i class="fas fa-calendar-times me-2 text-primary"></i>Indisponibilites
                                </a></li>

                            @elseif(session('user_role') === 'admin')
                                <li><a class="dropdown-item fw-600" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.personnel.index') }}">
                                    <i class="fas fa-users me-2 text-primary"></i>Personnel
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.patients.index') }}">
                                    <i class="fas fa-hospital-user me-2 text-primary"></i>Patients
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.specialites.index') }}">
                                    <i class="fas fa-stethoscope me-2 text-primary"></i>Specialites
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.conversations.index') }}">
                                    <i class="fas fa-robot me-2 text-primary"></i>Conversations IA
                                </a></li>

                            @elseif(session('user_role') === 'secretaire')
                                <li><a class="dropdown-item fw-600" href="{{ route('secretaire.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2 text-primary"></i>Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('secretaire.rendez-vous.index') }}">
                                    <i class="fas fa-calendar me-2 text-primary"></i>Rendez-vous
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('secretaire.patients.index') }}">
                                    <i class="fas fa-users me-2 text-primary"></i>Patients
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('secretaire.consultations.index') }}">
                                    <i class="fas fa-notes-medical me-2 text-primary"></i>Consultations
                                </a></li>
                            @endif

                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Se deconnecter
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                @else
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link profile-btn" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i> S'inscrire</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
<div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('error'))
<div class="container mt-3">
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

@if(session('info'))
<div class="container mt-3">
    <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<main>@yield('content')</main>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div style="width:40px;height:40px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-heartbeat text-white"></i>
                    </div>
                    <h5 class="mb-0 ms-2">AR SmartClinic</h5>
                </div>
                <p style="font-size:0.9rem;line-height:1.7;">Clinique moderne offrant des soins de qualite 24h/24 et 7j/7, avec une technologie intelligente pour mieux vous orienter.</p>
            </div>
            <div class="col-lg-2 col-md-4 mb-4">
                <h5>Liens rapides</h5>
                <a href="{{ route('welcome') }}"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Accueil</a>
                <a href="#services"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Services</a>
                <a href="#about"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> A propos</a>
                <a href="{{ route('login') }}"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Connexion</a>
            </div>
            <div class="col-lg-3 col-md-4 mb-4">
                <h5>Nos Services</h5>
                <a href="#"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Consultations medicales</a>
                <a href="#"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Urgences 24/7</a>
                <a href="#"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Orientation par IA</a>
                <a href="#"><i class="fas fa-chevron-right me-1" style="font-size:0.7rem;"></i> Dossier medical en ligne</a>
            </div>
            <div class="col-lg-3 col-md-4 mb-4" id="contact">
                <h5>Contact</h5>
                <p style="font-size:0.9rem;"><i class="fas fa-map-marker-alt me-2" style="color:var(--primary);"></i> 123 Avenue de la Sante, Maroc</p>
                <p style="font-size:0.9rem;"><i class="fas fa-phone me-2" style="color:var(--primary);"></i> +212 5XX-XXXXXX</p>
                <p style="font-size:0.9rem;"><i class="fas fa-envelope me-2" style="color:var(--primary);"></i> contact@arsmartclinic.ma</p>
                <p style="font-size:0.9rem;"><i class="fas fa-clock me-2" style="color:var(--primary);"></i> Ouvert 24h/24 - 7j/7</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">© {{ date('Y') }} AR SmartClinic. Tous droits reserves. | Developpe avec <i class="fas fa-heart text-danger"></i> pour votre sante.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>