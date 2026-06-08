@extends('layouts.app')

@section('title', 'Consultations')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-notes-medical me-2"></i> Consultations</h2>
        <p>Historique des consultations de la clinique (lecture seule)</p>
    </div>

    {{-- FILTRES --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('secretaire.consultations.index') }}">
                <div class="row g-2">
                    <div class="col-md-3">
                        <input type="date" name="date" class="form-control"
                            value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="statut" class="form-select">
                            <option value="">-- Tous les statuts --</option>
                            <option value="en_cours"  {{ request('statut') == 'en_cours'  ? 'selected' : '' }}>En cours</option>
                            <option value="terminee"  {{ request('statut') == 'terminee'  ? 'selected' : '' }}>Terminée</option>
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
                        <a href="{{ route('secretaire.consultations.index') }}" class="btn btn-outline-secondary w-100">
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
            <span><i class="fas fa-list me-2"></i>Liste des Consultations</span>
            <span class="badge bg-light text-dark">{{ $consultations->total() }} consultation(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3">Patient</th>
                            <th class="py-3">Médecin</th>
                            <th class="py-3">Spécialité</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Motif</th>
                            <th class="py-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultations as $consultation)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-500">
                                    {{ $consultation->patient->user->prenom }}
                                    {{ $consultation->patient->user->nom }}
                                </div>
                                <small class="text-muted">{{ $consultation->patient->cin }}</small>
                            </td>
                            <td class="py-3">
                                Dr. {{ $consultation->medecin->user->prenom }}
                                {{ $consultation->medecin->user->nom }}
                            </td>
                            <td class="py-3 text-muted">{{ $consultation->medecin->specialite->nom }}</td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 text-muted">{{ $consultation->motif ?? '—' }}</td>
                            <td class="py-3">
                                @if($consultation->statut === 'terminee')
                                    <span class="badge bg-success">Terminée</span>
                                @else
                                    <span class="badge bg-warning text-dark">En cours</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-notes-medical fa-2x mb-2 d-block"></i>
                                Aucune consultation trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($consultations->hasPages())
        <div class="card-footer">
            {{ $consultations->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection