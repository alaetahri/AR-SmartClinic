@extends('layouts.app')

@section('title', 'Assistant IA Médical')

@push('styles')
<style>
    * { box-sizing: border-box; }

    body { background: #f5f5f0; }

    .ai-wrapper {
        display: flex;
        height: calc(100vh - 70px);
        max-width: 1100px;
        margin: 0 auto;
        padding: 1rem;
        gap: 1rem;
    }

    /* ── SIDEBAR ── */
    .ai-sidebar {
        width: 260px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: .75rem;
    }

    .sidebar-brand {
        background: #1a6fc4;
        color: white;
        border-radius: 14px;
        padding: 1rem 1.2rem;
    }
    .sidebar-brand h6 {
        margin: 0 0 .2rem;
        font-size: .95rem;
        font-weight: 700;
        display: flex; align-items: center; gap: .5rem;
    }
    .sidebar-brand small { font-size: .72rem; opacity: .8; }

    .sidebar-box {
        background: white;
        border-radius: 14px;
        border: 1px solid #e8e8e4;
        padding: 1rem 1.1rem;
    }
    .sidebar-box h6 {
        font-size: .78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #888;
        margin: 0 0 .75rem;
    }

    .tip-row {
        display: flex; gap: .6rem; align-items: flex-start;
        font-size: .82rem; color: #555;
        margin-bottom: .55rem;
        line-height: 1.4;
    }
    .tip-row i { color: #1a6fc4; margin-top: 1px; flex-shrink: 0; font-size: 15px; }

    .spec-pill {
        display: flex; align-items: center; justify-content: space-between;
        background: #f0f7ff;
        border: 1px solid #c8dff6;
        border-radius: 8px;
        padding: .4rem .75rem;
        margin-bottom: .4rem;
        font-size: .81rem;
    }
    .spec-pill-name { font-weight: 600; color: #1a6fc4; }
    .spec-pill-score {
        background: #1a6fc4;
        color: white;
        font-size: .68rem;
        font-weight: 700;
        padding: .15rem .45rem;
        border-radius: 6px;
    }

    .btn-new-conv {
        width: 100%;
        background: white;
        border: 1.5px solid #1a6fc4;
        color: #1a6fc4;
        border-radius: 10px;
        padding: .55rem;
        font-size: .82rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        transition: all .18s;
        display: flex; align-items: center; justify-content: center; gap: .4rem;
    }
    .btn-new-conv:hover { background: #1a6fc4; color: white; }

    .urgence-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: .35rem 0;
        font-size: .82rem;
        border-bottom: 1px solid #f0f0ec;
    }
    .urgence-row:last-child { border-bottom: none; }
    .urgence-num { font-weight: 700; color: #dc2626; }

    /* ── CHAT MAIN ── */
    .ai-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: white;
        border-radius: 18px;
        border: 1px solid #e8e8e4;
        overflow: hidden;
        min-width: 0;
    }

    /* Header */
    .chat-header {
        padding: 1rem 1.4rem;
        border-bottom: 1px solid #f0f0ec;
        display: flex;
        align-items: center;
        gap: .9rem;
        flex-shrink: 0;
    }
    .chat-header-avatar {
        width: 40px; height: 40px;
        background: #1a6fc4;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-size: 18px;
        position: relative;
        flex-shrink: 0;
    }
    .online-dot {
        width: 10px; height: 10px;
        background: #22c55e;
        border-radius: 50%;
        border: 2px solid white;
        position: absolute;
        bottom: 0; right: 0;
    }
    .chat-header-info h6 { margin: 0; font-size: .95rem; font-weight: 700; color: #1a1a1a; }
    .chat-header-info small { font-size: .75rem; color: #888; }
    .chat-header-actions { margin-left: auto; }

    /* Messages zone */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem 1.4rem;
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        background: #fafaf8;
    }
    .chat-messages::-webkit-scrollbar { width: 4px; }
    .chat-messages::-webkit-scrollbar-thumb { background: #ddd; border-radius: 4px; }

    /* Welcome */
    .welcome-screen {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        text-align: center;
        padding: 2rem 1rem;
        gap: 1.2rem;
    }
    .welcome-icon {
        width: 64px; height: 64px;
        background: #1a6fc4;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        color: white;
        font-size: 28px;
    }
    .welcome-screen h5 { margin: 0; font-size: 1.15rem; font-weight: 700; color: #1a1a1a; }
    .welcome-screen p { margin: 0; font-size: .88rem; color: #666; max-width: 380px; line-height: 1.6; }

    .chips-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .5rem;
        width: 100%;
        max-width: 520px;
    }
    .chip {
        background: white;
        border: 1px solid #e8e8e4;
        border-radius: 10px;
        padding: .6rem .9rem;
        font-size: .81rem;
        color: #444;
        cursor: pointer;
        transition: all .15s;
        text-align: left;
        font-family: 'Poppins', sans-serif;
        display: flex; align-items: center; gap: .5rem;
        line-height: 1.3;
    }
    .chip:hover { border-color: #1a6fc4; color: #1a6fc4; background: #f0f7ff; }
    .chip-icon { font-size: 16px; flex-shrink: 0; }

    /* Messages */
    .msg-row { display: flex; gap: .75rem; align-items: flex-end; }
    .msg-row.user { flex-direction: row-reverse; }

    .msg-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px;
        flex-shrink: 0;
    }
    .msg-avatar.ai-av { background: #1a6fc4; color: white; }
    .msg-avatar.user-av { background: #e8e8e4; color: #555; }

    .msg-content { max-width: 68%; display: flex; flex-direction: column; gap: .25rem; }
    .msg-row.user .msg-content { align-items: flex-end; }

    .bubble {
        padding: .75rem 1rem;
        border-radius: 16px;
        font-size: .875rem;
        line-height: 1.6;
        word-break: break-word;
    }
    .bubble.ai-bubble {
        background: white;
        color: #222;
        border: 1px solid #e8e8e4;
        border-bottom-left-radius: 4px;
    }
    .bubble.user-bubble {
        background: #1a6fc4;
        color: white;
        border-bottom-right-radius: 4px;
    }
    .msg-time { font-size: .7rem; color: #aaa; padding: 0 .25rem; }

    /* Specialités card */
    .spec-card {
        background: white;
        border: 1px solid #e8e8e4;
        border-radius: 14px;
        padding: 1rem;
        margin-top: .5rem;
        max-width: 360px;
    }
    .spec-card-title {
        font-size: .78rem;
        font-weight: 700;
        color: #1a6fc4;
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: .75rem;
        display: flex; align-items: center; gap: .4rem;
    }
    .spec-row {
        display: flex; align-items: center; gap: .75rem;
        padding: .6rem .75rem;
        background: #fafaf8;
        border: 1px solid #eee;
        border-radius: 10px;
        margin-bottom: .5rem;
        transition: background .15s;
    }
    .spec-row:hover { background: #f0f7ff; }
    .spec-row-info { flex: 1; min-width: 0; }
    .spec-row-name { font-size: .84rem; font-weight: 600; color: #222; }
    .spec-bar-wrap { height: 4px; background: #eee; border-radius: 2px; margin-top: .3rem; overflow: hidden; }
    .spec-bar { height: 100%; background: #1a6fc4; border-radius: 2px; }
    .spec-row-pct { font-size: .72rem; color: #888; margin-top: .15rem; }
    .btn-choisir {
        background: #1a6fc4;
        color: white;
        border: none;
        border-radius: 8px;
        padding: .35rem .75rem;
        font-size: .77rem;
        font-weight: 600;
        font-family: 'Poppins', sans-serif;
        cursor: pointer;
        white-space: nowrap;
        transition: opacity .15s;
        flex-shrink: 0;
    }
    .btn-choisir:hover { opacity: .85; }

    /* Typing */
    .typing-wrap { display: none; }
    .typing-wrap.show { display: flex; }
    .typing-dots {
        background: white;
        border: 1px solid #e8e8e4;
        border-radius: 16px;
        border-bottom-left-radius: 4px;
        padding: .75rem 1rem;
        display: flex; gap: 4px; align-items: center;
    }
    .dot {
        width: 7px; height: 7px;
        background: #bbb;
        border-radius: 50%;
        animation: pulse 1.3s infinite;
    }
    .dot:nth-child(2) { animation-delay: .2s; }
    .dot:nth-child(3) { animation-delay: .4s; }
    @keyframes pulse {
        0%, 60%, 100% { transform: scale(1); opacity: .7; }
        30% { transform: scale(1.3); opacity: 1; }
    }

    /* Disclaimer */
    .disclaimer {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: .5rem .9rem;
        font-size: .77rem;
        color: #92400e;
        display: flex; gap: .5rem; align-items: center;
        flex-shrink: 0;
        margin: 0 1.2rem .6rem;
    }

    /* Input zone */
    .chat-input-zone {
        padding: .75rem 1.2rem 1rem;
        border-top: 1px solid #f0f0ec;
        flex-shrink: 0;
        background: white;
    }
    .input-box {
        display: flex;
        align-items: flex-end;
        gap: .6rem;
        background: #fafaf8;
        border: 1.5px solid #e0e0da;
        border-radius: 14px;
        padding: .6rem .6rem .6rem 1rem;
        transition: border-color .18s;
    }
    .input-box:focus-within { border-color: #1a6fc4; background: white; }
    .msg-textarea {
        flex: 1;
        border: none;
        background: transparent;
        resize: none;
        outline: none;
        font-family: 'Poppins', sans-serif;
        font-size: .875rem;
        color: #222;
        min-height: 38px;
        max-height: 130px;
        line-height: 1.55;
        overflow-y: auto;
    }
    .msg-textarea::placeholder { color: #aaa; }
    .send-btn {
        width: 38px; height: 38px;
        background: #1a6fc4;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        transition: opacity .15s, transform .1s;
        flex-shrink: 0;
    }
    .send-btn:hover { opacity: .88; }
    .send-btn:active { transform: scale(.95); }
    .send-btn:disabled { opacity: .4; cursor: not-allowed; }
    .input-hint { font-size: .7rem; color: #aaa; margin-top: .35rem; display: flex; justify-content: space-between; }

    /* Responsive */
    @media (max-width: 768px) {
        .ai-wrapper { flex-direction: column; height: auto; padding: .5rem; }
        .ai-sidebar { width: 100%; }
        .ai-main { height: 75vh; }
        .chips-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="ai-wrapper">

    {{-- ══════ SIDEBAR ══════ --}}
    <aside class="ai-sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand">
            <h6><i class="bi bi-robot"></i> Assistant IA</h6>
            <small>Clinique AR SmartClinic • 24h/24</small>
        </div>

        {{-- Conseils --}}
        <div class="sidebar-box">
            <h6>Conseils</h6>
            <div class="tip-row"><i class="bi bi-geo-alt-fill"></i><span><strong>Localisation</strong> — où avez-vous mal ?</span></div>
            <div class="tip-row"><i class="bi bi-clock-fill"></i><span><strong>Durée</strong> — depuis quand ?</span></div>
            <div class="tip-row"><i class="bi bi-thermometer-half"></i><span><strong>Intensité</strong> — de 1 à 10 ?</span></div>
            <div class="tip-row"><i class="bi bi-plus-circle-fill"></i><span><strong>Signes associés</strong> — fièvre, nausées ?</span></div>
        </div>

        {{-- Spécialités recommandées --}}
        @if(isset($specialitesProposees) && $specialitesProposees->isNotEmpty())
        <div class="sidebar-box">
            <h6>Recommandations</h6>
            @foreach($specialitesProposees as $sp)
            <div class="spec-pill">
                <span class="spec-pill-name">{{ $sp->specialite->nom }}</span>
                <span class="spec-pill-score">{{ $sp->score_confiance }}%</span>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Nouvelle conversation --}}
        @if(isset($conversation) && $conversation)
        <div class="sidebar-box">
            <h6>Actions</h6>
            <p style="font-size:.78rem; color:#888; margin:0 0 .6rem;">Depuis le {{ $conversation->created_at->format('d/m/Y H:i') }}</p>
            <form action="{{ route('patient.assistant') }}" method="GET">
                <input type="hidden" name="new" value="1">
                <button type="submit" class="btn-new-conv"
                        onclick="return confirm('Démarrer une nouvelle conversation ?')">
                    <i class="bi bi-plus-circle"></i> Nouvelle conversation
                </button>
            </form>
        </div>
        @endif

        {{-- Urgences --}}
        <div class="sidebar-box">
            <h6 style="color:#dc2626;">Urgences</h6>
            <div class="urgence-row"><span style="font-size:.82rem;color:#555;">SAMU Maroc</span><span class="urgence-num">141</span></div>
            <div class="urgence-row"><span style="font-size:.82rem;color:#555;">Protection Civile</span><span class="urgence-num">150</span></div>
            <div class="urgence-row"><span style="font-size:.82rem;color:#555;">Pompiers</span><span class="urgence-num">15</span></div>
        </div>

    </aside>

    {{-- ══════ CHAT MAIN ══════ --}}
    <main class="ai-main">

        {{-- Header --}}
        <div class="chat-header">
            <div class="chat-header-avatar">
                <i class="bi bi-robot"></i>
                <span class="online-dot"></span>
            </div>
            <div class="chat-header-info">
                <h6>Dr. IA — Assistant Médical</h6>
                <small>En ligne • Répond instantanément</small>
            </div>
            @if(isset($conversation) && $conversation)
            <div class="chat-header-actions">
                <a href="{{ route('patient.assistant') }}?new=1"
                   class="btn btn-sm btn-outline-primary"
                   style="border-radius:8px; font-size:.78rem;"
                   onclick="return confirm('Nouvelle conversation ?')">
                    <i class="bi bi-plus-circle me-1"></i>Nouveau
                </a>
            </div>
            @endif
        </div>

        {{-- Messages --}}
        <div class="chat-messages" id="chatMessages">

            @if(!isset($conversation) || !$conversation || (isset($messages) && $messages->isEmpty()))

            {{-- Écran de bienvenue --}}
            <div class="welcome-screen">
                <div class="welcome-icon"><i class="bi bi-robot"></i></div>
                <h5>Bonjour, {{ session('user_prenom') }} 👋</h5>
                <p>Décrivez vos symptômes en français ou en darija. Je vous orienterai vers le bon spécialiste.</p>
                <div class="chips-grid">
                    <button class="chip" onclick="fillMsg('J\'ai des maux de tête fréquents et des vertiges depuis 3 jours')">
                        <span class="chip-icon"><i class="bi bi-emoji-dizzy" style="color:#1a6fc4;"></i></span>
                        Maux de tête & vertiges
                    </button>
                    <button class="chip" onclick="fillMsg('J\'ai des douleurs thoraciques et du mal à respirer')">
                        <span class="chip-icon"><i class="bi bi-heart-pulse" style="color:#dc2626;"></i></span>
                        Douleurs thoraciques
                    </button>
                    <button class="chip" onclick="fillMsg('J\'ai des douleurs articulaires et des gonflements au niveau des genoux')">
                        <span class="chip-icon"><i class="bi bi-person-arms-up" style="color:#f59e0b;"></i></span>
                        Douleurs articulaires
                    </button>
                    <button class="chip" onclick="fillMsg('J\'ai des problèmes de vision, mes yeux sont fatigués et rouges')">
                        <span class="chip-icon"><i class="bi bi-eye" style="color:#17a2b8;"></i></span>
                        Problèmes de vision
                    </button>
                    <button class="chip" onclick="fillMsg('J\'ai des douleurs abdominales et des troubles digestifs')">
                        <span class="chip-icon"><i class="bi bi-activity" style="color:#22c55e;"></i></span>
                        Douleurs abdominales
                    </button>
                    <button class="chip" onclick="fillMsg('J\'ai de la fièvre à 39°C depuis 2 jours avec des frissons')">
                        <span class="chip-icon"><i class="bi bi-thermometer-sun" style="color:#ef4444;"></i></span>
                        Fièvre persistante
                    </button>
                </div>
            </div>

            @else

            {{-- Historique --}}
            @foreach($messages as $msg)
            <div class="msg-row {{ $msg->expediteur === 'patient' ? 'user' : '' }}">

                <div class="msg-avatar {{ $msg->expediteur === 'patient' ? 'user-av' : 'ai-av' }}">
                    @if($msg->expediteur === 'patient')
                        <i class="bi bi-person-fill" style="font-size:13px;"></i>
                    @else
                        <i class="bi bi-robot" style="font-size:13px;"></i>
                    @endif
                </div>

                <div class="msg-content">
                    <div class="bubble {{ $msg->expediteur === 'patient' ? 'user-bubble' : 'ai-bubble' }}">
                        {!! nl2br(e($msg->message)) !!}
                    </div>
                    <span class="msg-time">{{ $msg->created_at ? $msg->created_at->format('H:i') : '' }}</span>

                    {{-- Spécialités recommandées après dernier message IA --}}
                    @if($msg->expediteur === 'ai' && $loop->last && isset($specialitesProposees) && $specialitesProposees->isNotEmpty() && isset($conversation) && $conversation)
                    <div class="spec-card">
                        <div class="spec-card-title">
                            <i class="bi bi-stars"></i> Spécialités recommandées
                        </div>
                        @foreach($specialitesProposees as $sp)
                        <div class="spec-row">
                            <div class="spec-row-info">
                                <div class="spec-row-name">{{ $sp->specialite->nom }}</div>
                                <div class="spec-bar-wrap">
                                    <div class="spec-bar" style="width:{{ $sp->score_confiance }}%;"></div>
                                </div>
                                <div class="spec-row-pct">Pertinence : {{ $sp->score_confiance }}%</div>
                            </div>
                            <form action="{{ route('patient.assistant.choisir') }}" method="POST">
                                @csrf
                                <input type="hidden" name="specialite_id" value="{{ $sp->specialite_id }}">
                                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                                <button type="submit" class="btn-choisir"
                                    onclick="return confirm('Choisir {{ $sp->specialite->nom }} et prendre un RDV ?')">
                                    <i class="bi bi-calendar-check me-1"></i>Choisir
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endforeach

            @endif

            {{-- Typing indicator --}}
            <div class="msg-row typing-wrap" id="typingWrap">
                <div class="msg-avatar ai-av"><i class="bi bi-robot" style="font-size:13px;"></i></div>
                <div class="typing-dots">
                    <div class="dot"></div><div class="dot"></div><div class="dot"></div>
                </div>
            </div>

        </div>

        {{-- Disclaimer --}}
        <div class="disclaimer">
            <i class="bi bi-shield-exclamation" style="flex-shrink:0;"></i>
            <span>Cet assistant oriente uniquement — il ne remplace pas un médecin. Urgence : <strong>141</strong></span>
        </div>

        {{-- Flash success --}}
        @if(session('success'))
        <div class="alert alert-success mx-3 mb-2 py-2" style="font-size:.82rem; border-radius:10px;">
            <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
        </div>
        @endif

        {{-- Zone saisie --}}
        <div class="chat-input-zone">
            <form action="{{ route('patient.assistant.envoyer') }}" method="POST" id="chatForm">
                @csrf
                <div class="input-box">
                    <textarea
                        name="message"
                        id="msgInput"
                        class="msg-textarea"
                        placeholder="Décrivez vos symptômes ici... (ex: douleur à l'épaule droite depuis 1 semaine)"
                        rows="1"
                        maxlength="1000"
                        required
                    ></textarea>
                    <button type="submit" class="send-btn" id="sendBtn" title="Envoyer">
                        <i class="bi bi-send-fill"></i>
                    </button>
                </div>
                <div class="input-hint">
                    <span>Entrée pour envoyer • Shift+Entrée pour nouvelle ligne</span>
                    <span id="charCnt">0/1000</span>
                </div>
            </form>
            @error('message')
            <div style="font-size:.78rem; color:#dc2626; margin-top:.3rem;">
                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
            </div>
            @enderror
        </div>

    </main>
</div>
@endsection

@push('scripts')
<script>
const msgInput = document.getElementById('msgInput');
const charCnt  = document.getElementById('charCnt');
const chatForm = document.getElementById('chatForm');
const sendBtn  = document.getElementById('sendBtn');
const messages = document.getElementById('chatMessages');

// Scroll bas
if (messages) messages.scrollTop = messages.scrollHeight;

// Auto-resize + compteur
if (msgInput) {
    msgInput.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 130) + 'px';
        charCnt.textContent = this.value.length + '/1000';
    });

    // Entrée = envoyer
    msgInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) chatForm.requestSubmit();
        }
    });
}

// Chips
function fillMsg(text) {
    if (!msgInput) return;
    msgInput.value = text;
    msgInput.dispatchEvent(new Event('input'));
    msgInput.focus();
}

// Typing au submit
if (chatForm) {
    chatForm.addEventListener('submit', function () {
        sendBtn.disabled = true;
        const tw = document.getElementById('typingWrap');
        if (tw) { tw.classList.add('show'); messages.scrollTop = messages.scrollHeight; }
    });
}
</script>
@endpush