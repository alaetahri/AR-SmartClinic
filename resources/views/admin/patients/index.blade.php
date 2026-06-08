@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-hospital-user me-2"></i> Patients</h2>
        <p>Liste des patients ayant eu des consultations</p>
    </div>

    {{-- RECHERCHE --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.patients.index') }}">
                <div class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control"
                            placeholder="Rechercher par nom, prénom ou CIN..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- TABLEAU --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Liste des Patients</span>
            <span class="badge bg-light text-dark">{{ $patients->total() }} patient(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3">Patient</th>
                            <th class="py-3">CIN</th>
                            <th class="py-3">Téléphone</th>
                            <th class="py-3">Sexe</th>
                            <th class="py-3">Groupe Sanguin</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($patient->user->photo)
                                        <img src="{{ asset($patient->user->photo) }}"
                                            style="width:38px;height:38px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div style="width:38px;height:38px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user" style="color:var(--primary);"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-600">{{ $patient->user->prenom }} {{ $patient->user->nom }}</div>
                                        <small class="text-muted">{{ $patient->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-light text-dark">{{ $patient->cin }}</span>
                            </td>
                            <td class="py-3 text-muted">{{ $patient->user->telephone ?? '—' }}</td>
                            <td class="py-3">
                                @if($patient->sexe === 'homme')
                                    <span class="badge bg-info text-white">
                                        <i class="fas fa-mars me-1"></i>Homme
                                    </span>
                                @else
                                    <span class="badge bg-danger text-white">
                                        <i class="fas fa-venus me-1"></i>Femme
                                    </span>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($patient->groupe_sanguin)
                                    <span class="badge bg-danger">{{ $patient->groupe_sanguin }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('admin.patients.show', $patient->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-hospital-user fa-2x mb-2 d-block"></i>
                                Aucun patient trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($patients->hasPages())
        <div class="card-footer">
            {{ $patients->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection