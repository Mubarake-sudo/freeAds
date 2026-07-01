@extends('layouts.app')

@section('title', 'Modifier — ' . $ad->title)

@section('content')
<div class="page-container">
    <div class="container">
        <div class="form-page-layout">

            <div class="form-page-header">
                <nav class="breadcrumb" aria-label="Fil d'Ariane">
                    <a href="{{ route('home') }}">Accueil</a>
                    <span>›</span>
                    <a href="{{ route('profile') }}">Mon Profil</a>
                    <span>›</span>
                    <span>Modifier l'annonce</span>
                </nav>
                <h1 class="page-title">Modifier l'annonce</h1>
            </div>

            <form method="POST" action="{{ route('ads.update', $ad) }}" enctype="multipart/form-data" class="ad-form" id="edit-ad-form" novalidate>
                @csrf
                @method('PUT')

                <div class="form-card">
                    <h2 class="form-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Informations
                    </h2>

                    <div class="form-group">
                        <label for="title" class="form-label">Titre <span class="required">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $ad->title) }}" class="form-input {{ $errors->has('title') ? 'input-error' : '' }}" required minlength="5" maxlength="150">
                        @error('title') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="category" class="form-label">Catégorie <span class="required">*</span></label>
                            <select id="category" name="category" class="form-input form-select {{ $errors->has('category') ? 'input-error' : '' }}" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $ad->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="condition" class="form-label">État <span class="required">*</span></label>
                            <select id="condition" name="condition" class="form-input form-select {{ $errors->has('condition') ? 'input-error' : '' }}" required>
                                @foreach($conditions as $value => $label)
                                    <option value="{{ $value }}" {{ old('condition', $ad->condition) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('condition') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description <span class="required">*</span></label>
                        <textarea id="description" name="description" class="form-input form-textarea {{ $errors->has('description') ? 'input-error' : '' }}" required minlength="20" maxlength="5000" rows="6">{{ old('description', $ad->description) }}</textarea>
                        @error('description') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price" class="form-label">Prix (FCFA) <span class="required">*</span></label>
                            <div class="input-wrap">
                                <input type="number" id="price" name="price" value="{{ old('price', $ad->price) }}" class="form-input {{ $errors->has('price') ? 'input-error' : '' }}" min="0" step="100" required>
                                <span class="input-suffix">FCFA</span>
                            </div>
                            @error('price') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label for="location" class="form-label">Localisation <span class="required">*</span></label>
                            <div class="input-wrap">
                                <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <input type="text" id="location" name="location" value="{{ old('location', $ad->location) }}" class="form-input {{ $errors->has('location') ? 'input-error' : '' }}" required maxlength="100">
                            </div>
                            @error('location') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <h2 class="form-section-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Photo
                    </h2>

                    @if($ad->photo)
                        <div class="current-photo">
                            <p class="form-hint">Photo actuelle :</p>
                            <img src="{{ $ad->photo_url }}" alt="Photo actuelle" class="current-photo-img">
                            <p class="form-hint">Uploadez une nouvelle photo pour remplacer celle-ci.</p>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="photo" class="form-label">Nouvelle photo</label>
                        <div class="file-upload-area" id="file-upload-area">
                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg,image/webp" class="file-upload-input" onchange="previewPhoto(this)">
                            <div class="file-upload-placeholder" id="file-placeholder">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <p><strong>Cliquez</strong> pour changer la photo</p>
                                <span>JPEG, PNG, WEBP — Max 5 Mo</span>
                            </div>
                            <img id="photo-preview" class="photo-preview" style="display:none" alt="Prévisualisation">
                        </div>
                        @error('photo') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('ads.show', $ad) }}" class="btn btn-outline" id="cancel-edit">Annuler</a>
                    <button type="submit" class="btn btn-primary btn-lg" id="submit-edit">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    const placeholder = document.getElementById('file-placeholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
