@extends('layouts.app')

@section('title', 'Mes Indisponibilités')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-calendar-times me-2"></i> Mes Indisponibilités</h2>
        <p>Gérez vos périodes d'indisponibilité</p>
    </div>

    <div class="row">

        {{-- FORMULAIRE AJOUT --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-plus me-2"></i>Ajouter une Indisponibilité
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('medecin.indisponibilites.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-500">Date et heure de début <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date_debut"
                                class="form-control @error('date_debut') is-invalid @enderror"
                                value="{{ old('date_debut') }}">
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-500">Date et heure de fin <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="date_fin"
                                class="form-control @error('date_fin') is-invalid @enderror"
                                value="{{ old('date_fin') }}">
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-500">Motif</label>
                            <input type="text" name="motif" class="form-control"
                                placeholder="Ex: Congé, Formation..."
                                value="{{ old('motif') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i>Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- LISTE --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list me-2"></i>Mes Indisponibilités</span>
                    <span class="badge bg-light text-dark">{{ $indisponibilites->total() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th class="px-4 py-3">Début</th>
                                    <th class="py-3">Fin</th>
                                    <th class="py-3">Motif</th>
                                    <th class="py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($indisponibilites as $indispo)
                                <tr>
                                    <td class="px-4 py-3">
                                        <i class="fas fa-calendar me-1 text-danger"></i>
                                        {{ \Carbon\Carbon::parse($indispo->date_debut)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3">
                                        <i class="fas fa-calendar me-1 text-success"></i>
                                        {{ \Carbon\Carbon::parse($indispo->date_fin)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-3 text-muted">{{ $indispo->motif ?? '—' }}</td>
                                    <td class="py-3 text-center">
                                        <form action="{{ route('medecin.indisponibilites.destroy', $indispo->id) }}"
                                            method="POST" onsubmit="return confirm('Supprimer cette indisponibilité ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-calendar-check fa-2x mb-2 d-block"></i>
                                        Aucune indisponibilité enregistrée.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($indisponibilites->hasPages())
                <div class="card-footer">
                    {{ $indisponibilites->links() }}
                </div>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection