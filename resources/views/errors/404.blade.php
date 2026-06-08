@extends('layouts.app')
@section('title', 'Page introuvable')
@section('content')
<div class="container py-5 text-center">
    <div style="max-width:500px;margin:0 auto;">
        <div style="font-size:8rem;font-weight:700;color:#1a6fc4;line-height:1;">404</div>
        <h2 style="font-weight:700;color:#2c3e50;margin-bottom:15px;">Page introuvable</h2>
        <p style="color:#6c757d;margin-bottom:30px;">La page que vous cherchez n'existe pas ou a été déplacée.</p>
        <a href="{{ url()->previous() }}" class="btn btn-primary me-2">
            <i class="fas fa-arrow-left me-2"></i> Retour
        </a>
        <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
            <i class="fas fa-home me-2"></i> Accueil
        </a>
    </div>
</div>
@endsection