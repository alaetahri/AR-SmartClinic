@extends('layouts.app')

@section('title', 'Mes Rendez-vous')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-calendar-alt me-2"></i> Mes Rendez-vous</h2>
        <p>Dr. {{ session('user_prenom') }} {{ session('user_nom') }}</p>
    </div>

    {{-- FILTRES --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('medecin.rendez-vous.index') }}">
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
                            <i class="fas fa-filter me-1"></i> Filtrer
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-times me-1"></i> Réinitialiser
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
                            <th class="py-3">Date</th>
                            <th class="py-3">Heure</th>
                            <th class="py-3">Motif</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rendezVous as $rdv)
                        <tr class="{{ $rdv->date_rendez_vous == today()->toDateString() ? 'table-light' : '' }}">
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($rdv->patient->user->photo)
                                        <img src="{{ asset($rdv->patient->user->photo) }}"
                                            style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div style="width:35px;height:35px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user" style="color:var(--primary);font-size:0.9rem;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-500">{{ $rdv->patient->user->prenom }} {{ $rdv->patient->user->nom }}</div>
                                        <small class="text-muted">{{ $rdv->patient->cin }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($rdv->date_rendez_vous)->format('d/m/Y') }}
                                @if($rdv->date_rendez_vous == today()->toDateString())
                                    <span class="badge bg-primary ms-1">Aujourd'hui</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <i class="fas fa-clock me-1 text-muted"></i>
                                {{ $rdv->heure_debut }} — {{ $rdv->heure_fin }}
                            </td>
                            <td class="py-3 text-muted">{{ $rdv->motif ?? '—' }}</td>
                            <td class="py-3">
                                @php
                                    $badges = [
                                        'en_attente' => ['bg-warning text-dark', 'En attente'],
                                        'confirme'   => ['bg-success', 'Confirmé'],
                                        'annule'     => ['bg-danger', 'Annulé'],
                                        'termine'    => ['bg-secondary', 'Terminé'],
                                    ];
                                    [$badgeClass, $label] = $badges[$rdv->statut] ?? ['bg-secondary', $rdv->statut];
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $label }}</span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('medecin.rendez-vous.show', $rdv->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($rdv->statut === 'en_attente')
                                        <form action="{{ route('medecin.rendez-vous.confirmer', $rdv->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Confirmer">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($rdv->statut === 'confirme' && !$rdv->consultation)
                                        <form action="{{ route('medecin.consultations.creer', $rdv->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-info" title="Démarrer consultation">
                                                <i class="fas fa-notes-medical"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($rdv->statut === 'confirme' && $rdv->consultation)
                                        <a href="{{ route('medecin.consultations.show', $rdv->consultation->id) }}"
                                            class="btn btn-sm btn-outline-info" title="Voir consultation">
                                            <i class="fas fa-file-medical"></i>
                                        </a>
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