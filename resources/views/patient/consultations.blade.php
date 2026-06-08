@extends('layouts.app')

@section('title', 'Mes Consultations')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-notes-medical me-2"></i> Mes Consultations</h2>
        <p>Historique de toutes vos consultations médicales</p>
    </div>

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
                            <th class="px-4 py-3">Médecin</th>
                            <th class="py-3">Spécialité</th>
                            <th class="py-3">Date</th>
                            <th class="py-3">Motif</th>
                            <th class="py-3">Documents</th>
                            <th class="py-3">Statut</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($consultations as $consultation)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($consultation->medecin->user->photo)
                                        <img src="{{ asset($consultation->medecin->user->photo) }}"
                                            style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div style="width:35px;height:35px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user-md" style="color:var(--primary);font-size:0.9rem;"></i>
                                        </div>
                                    @endif
                                    <span class="fw-500">
                                        Dr. {{ $consultation->medecin->user->prenom }}
                                        {{ $consultation->medecin->user->nom }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 text-muted">{{ $consultation->medecin->specialite->nom }}</td>
                            <td class="py-3">
                                {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                            </td>
                            <td class="py-3 text-muted">{{ $consultation->motif ?? '—' }}</td>
                            <td class="py-3">
                                @if($consultation->documents->count() > 0)
                                    <span class="badge bg-primary">
                                        <i class="fas fa-file me-1"></i>{{ $consultation->documents->count() }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($consultation->statut === 'terminee')
                                    <span class="badge bg-success">Terminée</span>
                                @else
                                    <span class="badge bg-warning text-dark">En cours</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('patient.consultations.show', $consultation->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
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
            {{ $consultations->links() }}
        </div>
        @endif
    </div>

</div>
@endsection