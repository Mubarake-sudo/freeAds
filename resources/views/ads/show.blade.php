@extends('layouts.app')

@section('title', $ad->title)
@section('meta_description', Str::limit($ad->description, 160))

@section('content')
<div class="vortex-detail-page">
    <div class="vortex-detail-shell">
        <div class="vortex-detail-grid">
            <div class="vortex-detail-image-wrap">
                <img src="{{ $ad->photo_url }}" alt="{{ $ad->title }}">
                @if($ad->condition)
                    <span class="vortex-detail-badge">{{ $ad->condition_label }}</span>
                @endif
            </div>

            <div class="vortex-detail-info">
                <div class="vortex-detail-topline">
                    <span>{{ strtoupper($ad->category) }}</span>
                    <span>{{ $ad->created_at->diffForHumans() }}</span>
                </div>

                <h1>{{ $ad->title }}</h1>
                <div class="vortex-price-large">{{ number_format((float) $ad->price, 0, ',', ' ') }} FCFA</div>

                <div class="vortex-detail-meta">
                    <span>📍 {{ $ad->location }}</span>
                </div>

                <div class="vortex-seller-box">
                    <div class="vortex-seller-avatar">{{ strtoupper(substr($ad->user->login ?? 'V', 0, 1)) }}</div>
                    <div>
                        <strong>{{ $ad->user->login }}</strong>
                        <small>{{ $ad->user->ads()->count() }} annonce(s)</small>
                    </div>
                </div>

                @php
                    $sellerPhone = $ad->user->phone_number ? preg_replace('/\s+/', '', $ad->user->phone_number) : null;
                @endphp

                @if($sellerPhone)
                    <a href="tel:{{ rawurlencode($sellerPhone) }}" class="vortex-btn neon full">Appeler le vendeur</a>
                @elseif($ad->user->email)
                    <a href="mailto:{{ $ad->user->email }}?subject={{ rawurlencode('À propos de votre annonce : ' . $ad->title) }}" class="vortex-btn neon full">Envoyer un email au vendeur</a>
                @else
                    <a href="{{ route('login') }}" class="vortex-btn ghost full">Connectez-vous pour voir le contact</a>
                @endif

                @auth
                    @if(auth()->id() === $ad->user_id)
                        <div class="vortex-owner-actions">
                            <a href="{{ route('ads.edit', $ad) }}" class="vortex-btn ghost">Modifier</a>
                            <form method="POST" action="{{ route('ads.destroy', $ad) }}" onsubmit="return confirm('Supprimer cette annonce ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="vortex-btn danger">Supprimer</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <div class="vortex-description-box">
            <h2>Description</h2>
            <p>{!! nl2br(e($ad->description)) !!}</p>
        </div>
    </div>
</div>
@endsection
