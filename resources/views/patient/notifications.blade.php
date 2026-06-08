@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="container py-4">

    <div class="page-title d-flex justify-content-between align-items-center">
        <div>
            <h2><i class="fas fa-bell me-2"></i> Mes Notifications</h2>
            <p>Toutes vos notifications et alertes</p>
        </div>
        <form action="{{ route('patient.notifications.lire-tout') }}" method="POST">
            @csrf @method('PUT')
            <button type="submit" class="btn btn-light">
                <i class="fas fa-check-double me-2"></i>Tout marquer comme lu
            </button>
        </form>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @forelse($notifications as $notification)
            <div class="d-flex align-items-start gap-3 px-4 py-3 border-bottom
                {{ !$notification->lu ? 'bg-light' : '' }}">

                {{-- ICÔNE --}}
                <div style="width:45px;height:45px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                    background:{{ !$notification->lu ? 'var(--primary)' : '#e9ecef' }};">
                    <i class="fas fa-bell" style="color:{{ !$notification->lu ? '#fff' : '#6c757d' }};"></i>
                </div>

                {{-- CONTENU --}}
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="fw-600">{{ $notification->titre }}</span>
                            @if(!$notification->lu)
                                <span class="badge bg-primary ms-2" style="font-size:0.7rem;">Nouveau</span>
                            @endif
                        </div>
                        <small class="text-muted ms-3" style="white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($notification->date_envoi)->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    <p class="text-muted mb-0 mt-1" style="font-size:0.9rem;">
                        {{ $notification->message }}
                    </p>
                </div>

                {{-- MARQUER LU --}}
                @if(!$notification->lu)
                <form action="{{ route('patient.notifications.lire', $notification->id) }}" method="POST"
                    style="flex-shrink:0;">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Marquer comme lu">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
                @endif

            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-bell-slash fa-3x mb-3 d-block"></i>
                <h5>Aucune notification.</h5>
                <p>Vous serez notifié ici pour vos rendez-vous et consultations.</p>
            </div>
            @endforelse
        </div>
        @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>

</div>
@endsection