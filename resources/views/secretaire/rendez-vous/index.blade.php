@extends('layouts.app')

@section('title', 'Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-calendar-alt me-2"></i> Rendez-vous</h2>
            <p>Gestion de tous les rendez-vous de la clinique</p>
        </div>
        <a href="{{ route('secretaire.rendez-vous.create') }}" class="btn btn-light fw-600">
            <i class="fas fa-plus me-2"></i>Nouveau RDV
        </a>
    </div>

    {{-- FILTRES --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('secretaire.rendez-vous.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control"
                            value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">-- Tous les statuts --</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirme"   {{ request('statut') == 'confirme'   ? 'selected' : '' }}>Confirmé</option>
                            <option value="annule"     {{ request('statut') == 'annule'     ? 'selected' : '' }}>Annulé</option>
                            <option value="termine"    {{ request('statut') == 'termine'    ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="Rechercher par nom patient..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('secretaire.rendez-vous.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLEAU --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Liste des Rendez-vous</span>
            <span class="badge bg-light text-dark">{{ $rendezVous->total() }} rendez-vous</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3">Patient</th>
                            <th class="py-3">Médecin</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Heure</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rendezVous as $rdv)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-500">{{ $rdv->patient->user->prenom }} {{ $rdv->patient->user->nom }}</div>
                                <small class="text-muted">{{ $rdv->patient->cin }}</small>
                            </td>
                            <td class="py-3">
                                <div class="fw-500">Dr. {{ $rdv->medecin->user->prenom }} {{ $rdv->medecin->user->nom }}</div>
                                <small class="text-muted">{{ $rdv->medecin->specialite->nom }}</small>
                            </td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}
                                @if($rdv->date_rendez_vous == today()->toDateString())
                                    <span class="badge bg-primary ms-1">Aujourd'hui</span>
                                @endif
                            </td>
                            <td class="py-3">{{ $rdv->heure_debut }}</td>
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                                Aucun rendez-vous trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($rendezVous->hasPages())
        <div class="card-footer">
            {{ $rendezVous->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection