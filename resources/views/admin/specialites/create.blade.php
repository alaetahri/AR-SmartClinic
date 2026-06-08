@extends('layouts.app')

@section('title', 'Nouvelle Spécialité')

@section('content')
<div class="container py-4">

    <div class="page-title">
        <h2><i class="fas fa-plus-circle me-2"></i> Nouvelle Spécialité</h2>
        <p>Ajouter une nouvelle spécialité médicale</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-stethoscope me-2"></i>Informations de la Spécialité
                </div>
                <div class="card-body p-4">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.specialites.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-500">Nom de la Spécialité <span class="text-danger">*</span></label>
                            <input type="text" name="nom"
                                class="form-control @error('nom') is-invalid @enderror"
                                value="{{ old('nom') }}"
                                placeholder="Ex: Cardiologie">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-500">Description</label>
                            <textarea name="description" class="form-control" rows="4"
                                placeholder="Description de la spécialité...">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                            <a href="{{ route('admin.specialites.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Annuler
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection