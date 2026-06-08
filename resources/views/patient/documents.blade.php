@extends('layouts.app')

@section('title', 'Mes Documents')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-file-medical me-2"></i> Mes Documents Médicaux</h2>
        <p>Tous vos documents médicaux en un seul endroit</p>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Liste des Documents</span>
            <span class="badge bg-light text-dark">{{ $documents->total() }} document(s)</span>
        </div>
        <div class="card-body p-0">
            @forelse($documents as $document)
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:45px;height:45px;border-radius:10px;display:flex;align-items:center;justify-content:center;
                        background:{{ match($document->type) {
                            'ordonnance' => '#e8f4fd',
                            'analyse'    => '#fff3cd',
                            'radio'      => '#d1ecf1',
                            'scanner'    => '#d4edda',
                            'certificat' => '#f8d7da',
                            default      => '#f8f9fa'
                        } }};">
                        <i class="fas fa-file-medical" style="color:var(--primary);font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <div class="fw-600">{{ $document->titre }}</div>
                        <small class="text-muted">
                            <span class="badge bg-light text-dark me-2">{{ ucfirst($document->type) }}</span>
                            Dr. {{ $document->consultation->medecin->user->prenom }}
                            {{ $document->consultation->medecin->user->nom }}
                            —
                            {{ \Carbon\Carbon::parse($document->date_document)->format('d/m/Y') }}
                        </small>
                    </div>
                </div>
                <a href="{{ route('patient.documents.telecharger', $document->id) }}"
                    class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download me-1"></i>Télécharger
                </a>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-file-medical fa-3x mb-3 d-block"></i>
                <h5>Aucun document médical disponible.</h5>
                <p>Vos documents apparaîtront ici après vos consultations.</p>
            </div>
            @endforelse
        </div>
        @if($documents->hasPages())
        <div class="card-footer">
            {{ $documents->links() }}
        </div>
        @endif
    </div>

</div>
@endsection