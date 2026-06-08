@extends('layouts.app')

@section('title', 'Conversations IA')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-robot me-2"></i> Conversations IA</h2>
        <p>Historique des consultations avec l'assistant intelligent</p>
    </div>

    {{-- RECHERCHE --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.conversations.index') }}">
                <div class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control"
                            placeholder="Rechercher par nom ou prénom du patient..."
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
            <span><i class="fas fa-list me-2"></i>Liste des Conversations</span>
            <span class="badge bg-light text-dark">{{ $conversations->total() }} conversation(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th class="px-4 py-3">Patient</th>
                            <th class="py-3">Titre</th>
                            <th class="py-3">Spécialité Choisie</th>
                            <th class="py-3">Date</th>
                            <th class="py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conversations as $conversation)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-2">
                                    @if($conversation->patient->user->photo)
                                        <img src="{{ asset('storage/' . $conversation->patient->user->photo) }}"
                                            style="width:35px;height:35px;border-radius:50%;object-fit:cover;">
                                    @else
                                        <div style="width:35px;height:35px;background:var(--secondary);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                            <i class="fas fa-user" style="color:var(--primary);font-size:0.9rem;"></i>
                                        </div>
                                    @endif
                                    <span class="fw-500">
                                        {{ $conversation->patient->user->prenom }}
                                        {{ $conversation->patient->user->nom }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3 text-muted">
                                {{ $conversation->titre ?? 'Sans titre' }}
                            </td>
                            <td class="py-3">
                                @if($conversation->specialiteChoisie)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>
                                        {{ $conversation->specialiteChoisie->nom }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>En cours
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-muted">
                                {{ $conversation->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 text-center">
                                <a href="{{ route('admin.conversations.show', $conversation->id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-robot fa-2x mb-2 d-block"></i>
                                Aucune conversation trouvée.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($conversations->hasPages())
        <div class="card-footer">
            {{ $conversations->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection