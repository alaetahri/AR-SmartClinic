@extends('layouts.app')

@section('title', 'Détails Patient')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-hospital-user me-2"></i> {{ $patient->user->prenom }} {{ $patient->user->nom }}</h2>
            <p>Fiche patient</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('secretaire.rendez-vous.create', ['patient_id' => $patient->id]) }}"
                class="btn btn-light">
                <i class="fas fa-calendar-plus me-2"></i>Nouveau RDV
            </a>
            <a href="{{ route('secretaire.patients.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">

        {{-- INFOS PATIENT --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Informations Personnelles
                </div>
                <div class="card-body text-center">
                    @if($patient->user->photo)
                        <img src="{{ asset($patient->user->photo) }}"
                            style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);"
                            class="mb-3">
                    @else
                        <div style="width:90px;height:90px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;border:3px solid var(--primary);">
                            <i class="fas fa-user fa-2x" style="color:var(--primary);"></i>
                        </div>
                    @endif
                    <h5 class="fw-bold">{{ $patient->user->prenom }} {{ $patient->user->nom }}</h5>
                    <span class="badge bg-info text-white mb-3">Patient</span>

                    <div class="text-start mt-2">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">CIN</span>
                            <span class="fw-500">{{ $patient->cin }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Email</span>
                            <span style="font-size:0.82rem;">{{ $patient->user->email }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Téléphone</span>
                            <span class="fw-500">{{ $patient->user->telephone ?? '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Sexe</span>
                            <span class="fw-500">{{ ucfirst($patient->sexe) }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Naissance</span>
                            <span class="fw-500">
                                {{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') : '—' }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Groupe sanguin</span>
                            @if($patient->groupe_sanguin)
                                <span class="badge bg-danger">{{ $patient->groupe_sanguin }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Adresse</span>
                            <span style="font-size:0.85rem;">{{ $patient->adresse ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RENDEZ-VOUS --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-check me-2"></i>Rendez-vous</span>
                    <span class="badge bg-light text-dark">{{ $patient->rendezVous->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="py-3">Heure</th>
                                    <th class="py-3">Médecin</th>
                                    <th class="py-3">Statut</th>
                                    <th class="py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patient->rendezVous as $rdv)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}
                                    </td>
                                    <td class="py-3">{{ $rdv->heure_debut }}</td>
                                    <td class="py-3">Dr. {{ $rdv->medecin->user->prenom }} {{ $rdv->medecin->user->nom }}</td>
                                    <td class="py-3">
                                        @php
                                            $badges = [
                                                'en_attente' => ['bg-warning text-dark', 'En attente'],
                                                'confirme'   => ['bg-success', 'Confirmé'],
                                                'annule'     => ['bg-danger', 'Annulé'],
                                                'termine'    => ['bg-secondary', 'Terminé'],
                                            ];
                                            [$bc, $lb] = $badges[$rdv->statut] ?? ['bg-secondary', $rdv->statut];
                                        @endphp
                                        <span class="badge {{ $bc }}">{{ $lb }}</span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            @if($rdv->statut === 'en_attente')
                                                <form action="{{ route('secretaire.rendez-vous.confirmer', $rdv->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <button class="btn btn-sm btn-outline-success" title="Confirmer">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if(in_array($rdv->statut, ['en_attente', 'confirme']))
                                                <form action="{{ route('secretaire.rendez-vous.annuler', $rdv->id) }}" method="POST"
                                                    onsubmit="return confirm('Annuler ce rendez-vous ?')">
                                                    @csrf @method('PUT')
                                                    <button class="btn btn-sm btn-outline-danger" title="Annuler">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        Aucun rendez-vous.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection