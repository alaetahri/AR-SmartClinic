@extends('layouts.app')

@section('title', 'Consultation')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-notes-medical me-2"></i> Consultation</h2>
            <p>{{ $consultation->patient->user->prenom }} {{ $consultation->patient->user->nom }}
                — {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('medecin.rendez-vous.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">

        {{-- COLONNE GAUCHE --}}
        <div class="col-lg-4 mb-4">

            {{-- INFOS PATIENT --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-user me-2"></i>Patient
                </div>
                <div class="card-body text-center">
                    @if($consultation->patient->user->photo)
                        <img src="{{ asset($consultation->patient->user->photo) }}"
                            style="width:70px;height:70px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);"
                            class="mb-2">
                    @else
                        <div style="width:70px;height:70px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;border:3px solid var(--primary);">
                            <i class="fas fa-user fa-xl" style="color:var(--primary);"></i>
                        </div>
                    @endif
                    <h6 class="fw-bold">{{ $consultation->patient->user->prenom }} {{ $consultation->patient->user->nom }}</h6>
                    <small class="text-muted">{{ $consultation->patient->cin }}</small>

                    @if($consultation->patient->groupe_sanguin)
                        <div class="mt-2">
                            <span class="badge bg-danger">{{ $consultation->patient->groupe_sanguin }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- STATUT CONSULTATION --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>Statut
                </div>
                <div class="card-body text-center">
                    @if($consultation->statut === 'en_cours')
                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size:0.95rem;">
                            <i class="fas fa-spinner me-2"></i>En cours
                        </span>
                    @else
                        <span class="badge bg-success px-3 py-2" style="font-size:0.95rem;">
                            <i class="fas fa-check me-2"></i>Terminée
                        </span>
                    @endif
                    <div class="mt-3 text-muted" style="font-size:0.85rem;">
                        <i class="fas fa-calendar me-1"></i>
                        {{ \Carbon\Carbon::parse($consultation->date_consultation)->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            {{-- ACTIONS --}}
            @if($consultation->statut === 'en_cours')
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-cogs me-2"></i>Actions
                </div>
                <div class="card-body d-grid gap-2">
                    <form action="{{ route('medecin.consultations.cloturer', $consultation->id) }}" method="POST"
                        onsubmit="return confirm('Clôturer cette consultation ?')">
                        @csrf @method('PUT')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check-double me-2"></i>Clôturer la Consultation
                        </button>
                    </form>
                </div>
            </div>
            @endif

        </div>

        {{-- COLONNE DROITE --}}
        <div class="col-lg-8">

            {{-- DIAGNOSTIC ET NOTES --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-clipboard-list me-2"></i>Diagnostic & Notes Médicales
                </div>
                <div class="card-body">
                    @if($consultation->statut === 'en_cours')
                    <form action="{{ route('medecin.consultations.update', $consultation->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-500">Motif</label>
                            <input type="text" class="form-control bg-light"
                                value="{{ $consultation->motif ?? '—' }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-500">Diagnostic</label>
                            <textarea name="diagnostic" class="form-control" rows="4"
                                placeholder="Entrez le diagnostic...">{{ old('diagnostic', $consultation->diagnostic) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-500">Notes du Médecin</label>
                            <textarea name="notes_medecin" class="form-control" rows="4"
                                placeholder="Notes et observations...">{{ old('notes_medecin', $consultation->notes_medecin) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </form>
                    @else
                    <div class="mb-3">
                        <label class="form-label fw-500 text-muted">Motif</label>
                        <p>{{ $consultation->motif ?? '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-500 text-muted">Diagnostic</label>
                        <p>{{ $consultation->diagnostic ?? '—' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-500 text-muted">Notes du Médecin</label>
                        <p class="mb-0">{{ $consultation->notes_medecin ?? '—' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- DOCUMENTS --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-medical me-2"></i>Documents Médicaux</span>
                    <span class="badge bg-light text-dark">{{ $consultation->documents->count() }}</span>
                </div>

                {{-- AJOUTER DOCUMENT --}}
                @if($consultation->statut === 'en_cours')
                <div class="card-body border-bottom">
                    <h6 class="fw-600 mb-3">Ajouter un Document</h6>
                    <form action="{{ route('medecin.documents.store', $consultation->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="type" class="form-select @error('type') is-invalid @enderror">
                                    <option value="">-- Type --</option>
                                    <option value="ordonnance">Ordonnance</option>
                                    <option value="analyse">Analyse</option>
                                    <option value="radio">Radio</option>
                                    <option value="scanner">Scanner</option>
                                    <option value="certificat">Certificat</option>
                                    <option value="autre">Autre</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="titre"
                                    class="form-control @error('titre') is-invalid @enderror"
                                    placeholder="Titre du document"
                                    value="{{ old('titre') }}">
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-8">
                                <input type="file" name="fichier"
                                    class="form-control @error('fichier') is-invalid @enderror"
                                    accept=".pdf,.jpg,.jpeg,.png">
                                @error('fichier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-upload me-1"></i> Ajouter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                @endif

                {{-- LISTE DOCUMENTS --}}
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th class="px-4 py-2">Titre</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Date</th>
                                    @if($consultation->statut === 'en_cours')
                                    <th class="py-2 text-center">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($consultation->documents as $document)
                                <tr>
                                    <td class="px-4 py-2">{{ $document->titre }}</td>
                                    <td class="py-2">
                                        <span class="badge bg-light text-dark">{{ ucfirst($document->type) }}</span>
                                    </td>
                                    <td class="py-2 text-muted">
                                        {{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}
                                    </td>
                                    @if($consultation->statut === 'en_cours')
                                    <td class="py-2 text-center">
                                        <form action="{{ route('medecin.documents.destroy', $document->id) }}" method="POST"
                                            onsubmit="return confirm('Supprimer ce document ?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Aucun document ajouté.
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