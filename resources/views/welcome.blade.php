@extends('layouts.app')

@section('title', 'Accueil')

@section('styles')
<style>
    /* ── HERO ── */
    .hero-section {
        background: linear-gradient(135deg, #1a6fc4 0%, #0d4f9e 50%, #17a2b8 100%);
        color: #fff;
        padding: 100px 0 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 35px;
        line-height: 1.7;
    }

    .hero-btn {
        padding: 14px 35px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .hero-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .hero-icon-box {
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 40px;
        text-align: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .hero-icon-box i {
        font-size: 5rem;
        opacity: 0.8;
    }

    /* ── STATS ── */
    .stats-section {
        background: #fff;
        padding: 40px 0;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }

    .stat-item {
        text-align: center;
        padding: 20px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a6fc4;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-top: 5px;
    }

    /* ── SERVICES ── */
    .services-section {
        padding: 80px 0;
    }

    .section-title {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-title h2 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
    }

    .section-title p {
        color: #6c757d;
        font-size: 1rem;
        max-width: 600px;
        margin: 10px auto 0;
    }

    .section-title .title-line {
        width: 60px;
        height: 4px;
        background: #1a6fc4;
        margin: 15px auto 0;
        border-radius: 2px;
    }

    .service-card {
        background: #fff;
        border-radius: 15px;
        padding: 35px 25px;
        text-align: center;
        transition: all 0.3s;
        box-shadow: 0 3px 15px rgba(0,0,0,0.07);
        height: 100%;
        border: 2px solid transparent;
    }

    .service-card:hover {
        transform: translateY(-8px);
        border-color: #1a6fc4;
        box-shadow: 0 15px 35px rgba(26,111,196,0.15);
    }

    .service-icon {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.8rem;
    }

    .service-card h5 {
        font-weight: 600;
        margin-bottom: 12px;
        color: #2c3e50;
    }

    .service-card p {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    /* ── AI SECTION ── */
    .ai-section {
        background: linear-gradient(135deg, #1a2a3a 0%, #1a6fc4 100%);
        color: #fff;
        padding: 80px 0;
    }

    .ai-feature {
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.15);
    }

    /* ── HOW IT WORKS ── */
    .how-section {
        padding: 80px 0;
        background: #f4f8fc;
    }

    .step-card {
        text-align: center;
        padding: 30px 20px;
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: #1a6fc4;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 auto 20px;
    }

    /* ── DOCTORS SECTION ── */
    .doctors-section {
        padding: 80px 0;
        background: #fff;
    }

    .doctor-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.07);
        transition: all 0.3s;
    }

    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(26,111,196,0.15);
    }

    .doctor-avatar {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #1a6fc4, #17a2b8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 2rem;
        color: #fff;
    }

    /* ── CTA ── */
    .cta-section {
        background: #fff;
        padding: 80px 0;
    }

    .cta-box {
        background: linear-gradient(135deg, #1a6fc4, #17a2b8);
        border-radius: 20px;
        padding: 60px 40px;
        text-align: center;
        color: #fff;
    }
</style>
@endsection

@section('content')

<!-- ══════════════════════════════════════
     HERO SECTION
══════════════════════════════════════ -->
<section class="hero-section">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="mb-3">
                    <span class="badge" style="background:rgba(255,255,255,0.2);padding:8px 18px;border-radius:20px;font-size:0.85rem;">
                        <i class="fas fa-robot me-2"></i> Clinique intelligente avec IA
                    </span>
                </div>
                <h1 class="hero-title">
                    Votre santé,<br>
                    <span style="color:#7dd3fc;">notre priorité</span><br>
                    24h/24 — 7j/7
                </h1>
                <p class="hero-subtitle">
                    AR SmartClinic vous offre une prise en charge médicale complète avec un assistant
                    intelligent qui analyse vos symptômes et vous oriente vers le bon spécialiste.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('register') }}" class="btn btn-white hero-btn" style="background:#fff;color:#1a6fc4;">
                        <i class="fas fa-user-plus me-2"></i> S'inscrire gratuitement
                    </a>
                    <a href="{{ route('login') }}" class="btn hero-btn" style="background:rgba(255,255,255,0.15);color:#fff;border:2px solid rgba(255,255,255,0.4);">
                        <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="hero-icon-box">
                    <i class="fas fa-hospital-alt text-white"></i>
                    <h4 class="mt-3 mb-2" style="color:#fff;font-weight:600;">AR SmartClinic</h4>
                    <p style="color:rgba(255,255,255,0.8);font-size:0.9rem;">Soins médicaux de qualité supérieure</p>
                    <hr style="border-color:rgba(255,255,255,0.2);">
                    <div class="row text-center">
                        <div class="col-4">
                            <div style="font-size:1.5rem;font-weight:700;">24/7</div>
                            <div style="font-size:0.75rem;opacity:0.8;">Disponible</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:1.5rem;font-weight:700;">IA</div>
                            <div style="font-size:0.75rem;opacity:0.8;">Orientation</div>
                        </div>
                        <div class="col-4">
                            <div style="font-size:1.5rem;font-weight:700;">100%</div>
                            <div style="font-size:0.75rem;opacity:0.8;">Sécurisé</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════
     STATS
══════════════════════════════════════ -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">500+</div>
                    <div class="stat-label"><i class="fas fa-users me-1"></i> Patients satisfaits</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">20+</div>
                    <div class="stat-label"><i class="fas fa-user-md me-1"></i> Médecins spécialistes</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">15+</div>
                    <div class="stat-label"><i class="fas fa-stethoscope me-1"></i> Spécialités médicales</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label"><i class="fas fa-clock me-1"></i> Service continu</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════
     SERVICES
══════════════════════════════════════ -->
<section class="services-section" id="services">
    <div class="container">
        <div class="section-title">
            <h2>Nos Services</h2>
            <p>Une gamme complète de services médicaux pour prendre soin de votre santé</p>
            <div class="title-line"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon" style="background:#e8f4fd;">
                        <i class="fas fa-calendar-check" style="color:#1a6fc4;"></i>
                    </div>
                    <h5>Rendez-vous en ligne</h5>
                    <p>Prenez rendez-vous facilement avec le médecin de votre choix selon les créneaux disponibles.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon" style="background:#e8fdf5;">
                        <i class="fas fa-robot" style="color:#17a2b8;"></i>
                    </div>
                    <h5>Orientation par IA</h5>
                    <p>Notre assistant intelligent analyse vos symptômes et vous oriente vers la bonne spécialité médicale.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon" style="background:#fff3e0;">
                        <i class="fas fa-folder-medical" style="color:#ff9800;"></i>
                    </div>
                    <h5>Dossier médical numérique</h5>
                    <p>Accédez à votre dossier médical complet, historique des consultations et documents à tout moment.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon" style="background:#fce4ec;">
                        <i class="fas fa-ambulance" style="color:#e91e63;"></i>
                    </div>
                    <h5>Urgences 24h/24</h5>
                    <p>Notre équipe médicale est disponible à toute heure pour prendre en charge les urgences.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon" style="background:#f3e5f5;">
                        <i class="fas fa-file-medical" style="color:#9c27b0;"></i>
                    </div>
                    <h5>Documents médicaux</h5>
                    <p>Téléchargez vos ordonnances, analyses, radios et certificats directement depuis votre espace.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="service-card">
                    <div class="service-icon" style="background:#e8f5e9;">
                        <i class="fas fa-bell" style="color:#4caf50;"></i>
                    </div>
                    <h5>Notifications intelligentes</h5>
                    <p>Recevez des rappels pour vos rendez-vous et les mises à jour importantes de votre suivi médical.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════
     SECTION IA
══════════════════════════════════════ -->
<section class="ai-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge mb-3" style="background:rgba(255,255,255,0.2);padding:8px 18px;border-radius:20px;">
                    <i class="fas fa-robot me-2"></i> Intelligence Artificielle
                </span>
                <h2 style="font-size:2.2rem;font-weight:700;margin-bottom:20px;">
                    Assistant IA pour une meilleure orientation médicale
                </h2>
                <p style="opacity:0.85;line-height:1.8;margin-bottom:30px;">
                    Notre assistant intelligent analyse vos symptômes en temps réel et vous propose
                    les spécialités médicales les plus adaptées à votre situation.
                    <strong>Il ne remplace pas le médecin</strong>, il vous guide vers le bon spécialiste.
                </p>
                <div class="ai-feature">
                    <i class="fas fa-comments me-3" style="color:#7dd3fc;font-size:1.3rem;"></i>
                    <strong>Décrivez vos symptômes</strong> en langage naturel
                </div>
                <div class="ai-feature">
                    <i class="fas fa-brain me-3" style="color:#7dd3fc;font-size:1.3rem;"></i>
                    <strong>L'IA analyse</strong> et pose des questions complémentaires
                </div>
                <div class="ai-feature">
                    <i class="fas fa-stethoscope me-3" style="color:#7dd3fc;font-size:1.3rem;"></i>
                    <strong>Recevez des suggestions</strong> de spécialités médicales adaptées
                </div>
                <div class="ai-feature">
                    <i class="fas fa-calendar-plus me-3" style="color:#7dd3fc;font-size:1.3rem;"></i>
                    <strong>Prenez rendez-vous</strong> directement avec le bon médecin
                </div>
                <div class="mt-4">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg" style="border-radius:25px;font-weight:600;color:#1a6fc4;">
                        <i class="fas fa-robot me-2"></i> Essayer l'assistant IA
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Simulation chat IA -->
                <div style="background:rgba(255,255,255,0.1);border-radius:20px;padding:25px;border:1px solid rgba(255,255,255,0.2);">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width:40px;height:40px;background:#17a2b8;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-robot text-white"></i>
                        </div>
                        <div class="ms-3">
                            <strong>Assistant AR SmartClinic</strong>
                            <div style="font-size:0.8rem;opacity:0.7;">
                                <i class="fas fa-circle me-1" style="color:#4caf50;font-size:0.6rem;"></i> En ligne
                            </div>
                        </div>
                    </div>
                    <hr style="border-color:rgba(255,255,255,0.2);">
                    <!-- Messages simulés -->
                    <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:12px 16px;margin-bottom:12px;max-width:85%;">
                        <p style="margin:0;font-size:0.9rem;">Bonjour ! Je suis votre assistant médical. Décrivez-moi vos symptômes.</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.25);border-radius:12px;padding:12px 16px;margin-bottom:12px;max-width:85%;margin-left:auto;text-align:right;">
                        <p style="margin:0;font-size:0.9rem;">J'ai des maux de tête fréquents et des vertiges...</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:12px 16px;max-width:85%;">
                        <p style="margin:0;font-size:0.9rem;">Je comprends. Depuis combien de temps ressentez-vous ces symptômes ?</p>
                    </div>
                    <div style="margin-top:15px;display:flex;gap:10px;">
                        <input type="text" class="form-control" placeholder="Écrivez votre réponse..." style="border-radius:20px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.3);color:#fff;" disabled>
                        <button class="btn" style="background:#17a2b8;color:#fff;border-radius:50%;width:42px;height:42px;padding:0;" disabled>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <p class="text-center mt-3" style="font-size:0.8rem;opacity:0.7;">
                        <i class="fas fa-lock me-1"></i> Connectez-vous pour utiliser l'assistant
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════
     COMMENT ÇA MARCHE
══════════════════════════════════════ -->
<section class="how-section" id="about">
    <div class="container">
        <div class="section-title">
            <h2>Comment ça marche ?</h2>
            <p>En 4 étapes simples, prenez soin de votre santé depuis chez vous</p>
            <div class="title-line"></div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5 style="font-weight:600;">Créez votre compte</h5>
                    <p style="color:#6c757d;font-size:0.9rem;">Inscrivez-vous gratuitement en quelques minutes avec vos informations personnelles.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5 style="font-weight:600;">Décrivez vos symptômes</h5>
                    <p style="color:#6c757d;font-size:0.9rem;">Utilisez notre assistant IA ou choisissez directement votre spécialité.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5 style="font-weight:600;">Prenez rendez-vous</h5>
                    <p style="color:#6c757d;font-size:0.9rem;">Choisissez le médecin et le créneau qui vous conviennent parmi les disponibilités.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5 style="font-weight:600;">Consultez & suivez</h5>
                    <p style="color:#6c757d;font-size:0.9rem;">Accédez à votre dossier médical, vos documents et votre historique en ligne.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════
     CTA FINAL
══════════════════════════════════════ -->
<section class="cta-section">
    <div class="container">
        <div class="cta-box">
            <h2 style="font-size:2.2rem;font-weight:700;margin-bottom:15px;">
                Prêt à prendre soin de votre santé ?
            </h2>
            <p style="font-size:1.05rem;opacity:0.9;margin-bottom:35px;max-width:600px;margin-left:auto;margin-right:auto;">
                Rejoignez AR SmartClinic et bénéficiez d'un suivi médical intelligent et personnalisé.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-white btn-lg hero-btn" style="background:#fff;color:#1a6fc4;font-weight:600;">
                    <i class="fas fa-user-plus me-2"></i> Créer un compte gratuit
                </a>
                <a href="{{ route('login') }}" class="btn btn-lg hero-btn" style="background:transparent;color:#fff;border:2px solid rgba(255,255,255,0.5);font-weight:600;">
                    <i class="fas fa-sign-in-alt me-2"></i> Se connecter
                </a>
            </div>
        </div>
    </div>
</section>

@endsection