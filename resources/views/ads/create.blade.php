@extends('layouts.app')

@section('title', 'Publier une annonce')
@section('meta_description', 'Publiez votre annonce sur VORTEX ADS.')

@section('content')
<div class="vortex-form-page">
    <div class="vortex-form-shell">
        <div class="vortex-form-header">
            <div class="vortex-auth-brand" aria-label="VORTEX ADS">
                <span class="vortex-brand-word">VORTEX</span>
                <span class="vortex-brand-sub">ADS</span>
            </div>
            <h1>Publier une annonce</h1>
            <p>Déposez votre article en quelques secondes et donnez-lui la meilleure visibilité possible.</p>
        </div>

        <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data" class="vortex-form-card" novalidate>
            @csrf

            <div class="vortex-form-grid two-cols">
                <div class="vortex-field">
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Ex: MacBook Pro 14 pouces" required>
                    @error('title') <span class="vortex-error">{{ $message }}</span> @enderror
                </div>

                <div class="vortex-field">
                    <label for="category">Catégorie</label>
                    <select id="category" name="category" required>
                        <option value="">Choisir une catégorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <span class="vortex-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="vortex-field">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" placeholder="Décrivez votre produit, son état, ses accessoires et la raison de la vente." required>{{ old('description') }}</textarea>
                @error('description') <span class="vortex-error">{{ $message }}</span> @enderror
            </div>

            <div class="vortex-form-grid two-cols">
                <div class="vortex-field">
                    <label for="price">Prix (FCFA)</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" placeholder="150000" min="0" step="100" required>
                    @error('price') <span class="vortex-error">{{ $message }}</span> @enderror
                </div>

                <div class="vortex-field">
                    <label for="condition">État</label>
                    <select id="condition" name="condition" required>
                        <option value="">Choisir l'état</option>
                        @foreach($conditions as $value => $label)
                            <option value="{{ $value }}" {{ old('condition') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('condition') <span class="vortex-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="vortex-form-grid two-cols">
                <div class="vortex-field">
                    <label for="location">Localisation</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" placeholder="Abidjan, Cocody, Yopougon" required>
                    @error('location') <span class="vortex-error">{{ $message }}</span> @enderror
                </div>

                <div class="vortex-field">
                    <label for="photo">Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp" class="vortex-file-input">
                    @error('photo') <span class="vortex-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="vortex-form-actions">
                <a href="{{ route('home') }}" class="vortex-btn ghost">Annuler</a>
                <button type="submit" class="vortex-btn neon">Publier</button>
            </div>
        </form>
    </div>
</div>
@endsection
