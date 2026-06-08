@extends('layouts.app')

@section('title', 'Détails Spécialité')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-stethoscope me-2"></i> {{ $specialite->nom }}</h2>
            <p>Détails et médecins de cette spécialité</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.specialites.edit', $specialite->id) }}" class="btn btn-light">
                <i class="fas fa-edit me-2"></i>Modifier
            </a>
            <a href="{{ route('admin.specialites.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <div class="row">

        {{-- INFOS SPÉCIALITÉ --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Informations
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div style="width:80px;height:80px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                            <i class="fas fa-stethoscope fa-2x" style="color:var(--primary);"></i>
                        </div>
                        <h5 class="fw-bold">{{ $specialite->nom }}</h5>
                        <span class="badge bg-primary">{{ $specialite->medecins->count() }} médecin(s)</span>
                    </div>

                    <div class="border-top pt-3">
                        <p class="text-muted mb-1" style="font-size:0.85rem;">Description</p>
                        <p>{{ $specialite->description ?? 'Aucune description disponible.' }}</p>
                    </div>

                    <div class="border-top pt-3 mt-2">
                        <p class="text-muted mb-1" style="font-size:0.85rem;">Créée le</p>
                        <p class="mb-0">{{ $specialite->created_at ? $specialite->created_at->format('d/m/Y') : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- LISTE MÉDECINS --}}
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-md me-2"></i>Médecins de cette Spécialité
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th class="px-4 py-3">Médecin</th>
                                    <th class="py-3">Email</th>
                                    <th class="py-3">Téléphone</th>
                                    <th class="py-3">N° Ordre</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($specialite->medecins as $medecin)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @if($medecin->user->photo)
                                                <img src="{{ asset('storage/' . $medecin->user->photo) }}"
                                                    style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
                                            @else
                                                <div style="width:35px;height:35px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                                    <i class="fas fa-user-md" style="color:var(--primary);font-size:0.9rem;"></i>
                                                </div>
                                            @endif
                                            <span class="fw-500">Dr. {{ $medecin->user->prenom }} {{ $medecin->user->nom }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-muted">{{ $medecin->user->email }}</td>
                                    <td class="py-3 text-muted">{{ $medecin->user->telephone ?? '—' }}</td>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark">{{ $medecin->numero_ordre }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fas fa-user-md fa-2x mb-2 d-block"></i>
                                        Aucun médecin pour cette spécialité.
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