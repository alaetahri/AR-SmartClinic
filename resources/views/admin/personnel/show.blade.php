@extends('layouts.app')
@section('title', 'Détails Personnel')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-user me-2"></i> Fiche Personnel</h2>
        <p>Détails du membre du personnel</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body py-4">
                    @if($user->photo)
                        <img src="{{ asset($user->photo) }}" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid #e8f4fd;" alt="photo">
                    @else
                        <div style="width:100px;height:100px;border-radius:50%;background:#e8f4fd;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:700;color:#1a6fc4;margin:0 auto;">
                            {{ strtoupper(substr($user->prenom, 0, 1)) }}
                        </div>
                    @endif
                    <h4 class="mt-3 mb-1">{{ $user->prenom }} {{ $user->nom }}</h4>
                    @if($user->role == 'medecin')
                        <span class="badge bg-primary">Médecin</span>
                        @if($medecin)
                            <p class="text-muted mt-2 mb-0">{{ $medecin->specialite->nom ?? '' }}</p>
                        @endif
                    @else
                        <span class="badge bg-warning text-dark">Secrétaire</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-info-circle me-2"></i> Informations générales</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div style="font-size:0.8rem;color:#6c757d;">Nom complet</div>
                            <div style="font-weight:500;">{{ $user->prenom }} {{ $user->nom }}</div>
                        </div>
                        <div class="col-md-6">
                            <div style="font-size:0.8rem;color:#6c757d;">Email</div>
                            <div style="font-weight:500;">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div style="font-size:0.8rem;color:#6c757d;">Téléphone</div>
                            <div style="font-weight:500;">{{ $user->telephone ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div style="font-size:0.8rem;color:#6c757d;">Membre depuis</div>
                            <div style="font-weight:500;">{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            @if($user->role == 'medecin' && $medecin)
            <div class="card">
                <div class="card-header"><i class="fas fa-user-md me-2"></i> Informations médecin</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div style="font-size:0.8rem;color:#6c757d;">Spécialité</div>
                            <div style="font-weight:500;">{{ $medecin->specialite->nom ?? '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div style="font-size:0.8rem;color:#6c757d;">Numéro d'ordre</div>
                            <div style="font-weight:500;">{{ $medecin->numero_ordre }}</div>
                        </div>
                        @if($medecin->biographie)
                        <div class="col-12">
                            <div style="font-size:0.8rem;color:#6c757d;">Biographie</div>
                            <div>{{ $medecin->biographie }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('admin.personnel.edit', $user->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Modifier
                </a>
                <a href="{{ route('admin.personnel.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection