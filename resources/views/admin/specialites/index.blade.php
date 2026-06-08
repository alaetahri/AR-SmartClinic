@extends('layouts.app')

@section('title', 'Spécialités')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-stethoscope me-2"></i> Spécialités Médicales</h2>
            <p>Gestion des spécialités de la clinique</p>
        </div>
        <a href="{{ route('admin.specialites.create') }}" class="btn btn-light fw-600">
            <i class="fas fa-plus me-2"></i>Nouvelle Spécialité
        </a>
    </div>

    {{-- RECHERCHE --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.specialites.index') }}">
                <div class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control"
                            placeholder="Rechercher une spécialité..."
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
            <span><i class="fas fa-list me-2"></i>Liste des Spécialités</span>
            <span class="badge bg-light text-dark">{{ $specialites->total() }} spécialité(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="py-3">Spécialité</th>
                            <th class="py-3">Description</th>
                            <th class="py-3 text-center">Médecins</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($specialites as $specialite)
                        <tr>
                            <td class="px-4 py-3 text-muted">{{ $loop->iteration }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:35px;height:35px;background:var(--secondary);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-stethoscope" style="color:var(--primary);font-size:0.9rem;"></i>
                                    </div>
                                    <span class="fw-600">{{ $specialite->nom }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-muted" style="max-width:300px;">
                                {{ $specialite->description ? \Str::limit($specialite->description, 80) : '—' }}
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-primary rounded-pill">
                                    {{ $specialite->medecins_count }} médecin(s)
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('admin.specialites.show', $specialite->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.specialites.edit', $specialite->id) }}"
                                        class="btn btn-sm btn-outline-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.specialites.destroy', $specialite->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cette spécialité ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-stethoscope fa-2x mb-2 d-block"></i>
                                Aucune spécialité trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($specialites->hasPages())
        <div class="card-footer">
            {{ $specialites->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection