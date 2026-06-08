@extends('layouts.app')
@section('title', 'Gestion du Personnel')

@section('styles')
<style>
    .table th { font-weight: 600; color: #6c757d; font-size: 0.85rem; }
    .table td { vertical-align: middle; font-size: 0.9rem; }
    .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
    .avatar-placeholder { width: 40px; height: 40px; border-radius: 50%; background: #e8f4fd; display: flex; align-items: center; justify-content: center; color: #1a6fc4; font-weight: 600; }
</style>
@endsection

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-users me-2"></i> Gestion du Personnel</h2>
        <p>Médecins et secrétaires de la clinique</p>
    </div>

    <!-- FILTRES + BOUTON AJOUTER -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.personnel.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-500">Recherche</label>
                        <input type="text" name="search" class="form-control" placeholder="Nom, prénom ou email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-500">Rôle</label>
                        <select name="role" class="form-select">
                            <option value="">Tous</option>
                            <option value="medecin" {{ request('role') == 'medecin' ? 'selected' : '' }}>Médecins</option>
                            <option value="secretaire" {{ request('role') == 'secretaire' ? 'selected' : '' }}>Secrétaires</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i> Filtrer
                        </button>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <a href="{{ route('admin.personnel.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-user-plus me-1"></i> Ajouter personnel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLEAU -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Photo</th>
                            <th>Nom complet</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Rôle</th>
                            <th>Spécialité</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personnel as $user)
                        <tr>
                            <td class="ps-3">
                                @if($user->photo)
                                    <img src="{{ asset($user->photo) }}" class="avatar" alt="photo">
                                @else
                                    <div class="avatar-placeholder">{{ strtoupper(substr($user->prenom, 0, 1)) }}</div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:500;">{{ $user->prenom }} {{ $user->nom }}</div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->telephone ?? '—' }}</td>
                            <td>
                                @if($user->role == 'medecin')
                                    <span class="badge bg-primary">Médecin</span>
                                @else
                                    <span class="badge bg-warning text-dark">Secrétaire</span>
                                @endif
                            </td>
                            <td>
                                @if($user->role == 'medecin' && $user->medecin)
                                    {{ $user->medecin->specialite->nom ?? '—' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.personnel.show', $user->id) }}" class="btn btn-sm btn-outline-info me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.personnel.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.personnel.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-users" style="font-size:2rem;opacity:0.3;"></i>
                                <p class="mt-2">Aucun personnel trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($personnel->hasPages())
        <div class="card-footer">
            {{ $personnel->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection