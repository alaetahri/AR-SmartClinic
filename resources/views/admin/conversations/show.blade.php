@extends('layouts.app')

@section('title', 'Détails Conversation')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-robot me-2"></i> Conversation IA</h2>
            <p>{{ $conversation->patient->user->prenom }} {{ $conversation->patient->user->nom }}
                — {{ $conversation->created_at->format('d/m/Y H:i') }}</p>
        </div>
        <a href="{{ route('admin.conversations.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">

        {{-- MESSAGES --}}
        <div class="col-lg-7 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-comments me-2"></i>Messages de la Conversation
                </div>
                <div class="card-body" style="max-height:500px;overflow-y:auto;">
                    @forelse($conversation->messages as $message)
                        @if($message->expediteur === 'patient')
                        <div class="d-flex justify-content-end mb-3">
                            <div style="max-width:75%;">
                                <div style="background:var(--primary);color:#fff;padding:12px 16px;border-radius:15px 15px 0 15px;font-size:0.9rem;">
                                    {{ $message->message }}
                                </div>
                                <small class="text-muted d-block text-end mt-1">
                                    <i class="fas fa-user me-1"></i>Patient
                                    — {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                        @else
                        <div class="d-flex justify-content-start mb-3">
                            <div style="max-width:75%;">
                                <div style="background:#f0f4f8;padding:12px 16px;border-radius:15px 15px 15px 0;font-size:0.9rem;">
                                    <i class="fas fa-robot me-2" style="color:var(--accent);"></i>
                                    {{ $message->message }}
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fas fa-robot me-1"></i>Assistant IA
                                    — {{ $message->created_at->format('H:i') }}
                                </small>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-comments fa-2x mb-2 d-block"></i>
                            Aucun message dans cette conversation.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- INFOS DROITE --}}
        <div class="col-lg-5">

            {{-- PATIENT --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Patient
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        @if($conversation->patient->user->photo)
                            <img src="{{ asset('storage/' . $conversation->patient->user->photo) }}"
                                style="width:50px;height:50px;border-radius:50%;object-fit:cover;">
                        @else
                            <div style="width:50px;height:50px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-user fa-lg" style="color:var(--primary);"></i>
                            </div>
                        @endif
                        <div>
                            <div class="fw-bold">
                                {{ $conversation->patient->user->prenom }}
                                {{ $conversation->patient->user->nom }}
                            </div>
                            <small class="text-muted">{{ $conversation->patient->user->email }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SYMPTÔMES --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-thermometer me-2"></i>Symptômes Détectés
                </div>
                <div class="card-body">
                    @forelse($conversation->symptomes as $symptome)
                        <span class="badge bg-light text-dark border me-1 mb-2" style="font-size:0.85rem;padding:6px 10px;">
                            <i class="fas fa-dot-circle me-1" style="color:var(--primary);"></i>
                            {{ $symptome->nom }}
                        </span>
                    @empty
                        <p class="text-muted mb-0">Aucun symptôme enregistré.</p>
                    @endforelse
                </div>
            </div>

            {{-- SPÉCIALITÉS PROPOSÉES --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-stethoscope me-2"></i>Spécialités Proposées
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th class="px-3 py-2">Spécialité</th>
                                    <th class="py-2 text-center">Score</th>
                                    <th class="py-2 text-center">Choisie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($conversation->specialitesProposees as $sp)
                                <tr>
                                    <td class="px-3 py-2">{{ $sp->specialite->nom }}</td>
                                    <td class="py-2 text-center">
                                        <span class="badge bg-primary">{{ $sp->score_confiance }}%</span>
                                    </td>
                                    <td class="py-2 text-center">
                                        @if($sp->choisie)
                                            <i class="fas fa-check-circle text-success fa-lg"></i>
                                        @else
                                            <i class="fas fa-times-circle text-muted"></i>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-muted">Aucune spécialité proposée.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SPÉCIALITÉ FINALE --}}
            @if($conversation->specialiteChoisie)
            <div class="card">
                <div class="card-header" style="background:var(--accent);">
                    <i class="fas fa-check-double me-2"></i>Spécialité Finale Choisie
                </div>
                <div class="card-body text-center">
                    <div style="width:60px;height:60px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="fas fa-stethoscope fa-lg" style="color:var(--primary);"></i>
                    </div>
                    <h5 class="fw-bold mb-0">{{ $conversation->specialiteChoisie->nom }}</h5>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection