@extends('layouts.app')

@section('title', 'Mes Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-calendar-alt me-2"></i> Mes Rendez-vous</h2>
            <p>Historique et suivi de vos rendez-vous</p>
        </div>
        <a href="{{ route('patient.rendez-vous.create') }}" class="btn btn-light fw-600">
            <i class="fas fa-plus me-2"></i>Nouveau RDV
        </a>
    </div>

    {{-- FILTRES --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('patient.rendez-vous.index') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="date" name="date" class="form-control"
                            value="{{ request('date') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="statut" class="form-select">
                            <option value="">-- Tous les statuts --</option>
                            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="confirme"   {{ request('statut') == 'confirme'   ? 'selected' : '' }}>Confirmé</option>
                            <option value="annule"     {{ request('statut') == 'annule'     ? 'selected' : '' }}>Annulé</option>
                            <option value="termine"    {{ request('statut') == 'termine'    ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('patient.rendez-vous.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i>Réinitialiser
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
                            <th class="px-4 py-3">Médecin</th>
                            <th class="py-3">Spécialité</th>
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
                                <div class="d-flex align-items-center gap-2">
                                    @if($rdv->medecin->user->photo)
                                        <img src="{{ asset($rdv->medecin->user->photo) }}"
                                            style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div style="width:35px;height:35px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user-md" style="color:var(--primary);font-size:0.9rem;"></i>
                                        </div>
                                    @endif
                                    <span class="fw-500">Dr. {{ $rdv->medecin->user->prenom }} {{ $rdv->medecin->user->nom }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-muted">{{ $rdv->medecin->specialite->nom }}</td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}
                                @if($rdv->date_rendez_vous == today()->toDateString())
                                    <span class="badge bg-primary ms-1">Aujourd'hui</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <i class="fas fa-clock me-1 text-muted"></i>
                                {{ $rdv->heure_debut }}
                            </td>
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
                                    <a href="{{ route('patient.rendez-vous.show', $rdv->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(in_array($rdv->statut, ['en_attente', 'confirme']))
                                        <form action="{{ route('patient.rendez-vous.annuler', $rdv->id) }}" method="POST"
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
                                <div class="mt-3">
                                    <a href="{{ route('patient.rendez-vous.create') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus me-1"></i>Prendre un RDV
                                    </a>
                                </div>
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