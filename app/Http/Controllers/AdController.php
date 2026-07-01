<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdController extends Controller
{
    /**
     * Affiche toutes les annonces avec recherche et filtres.
     * Les filtres sont appliqués via les scopes du modèle Ad.
     */
    public function index(Request $request): View
    {
        $query = Ad::with('user')->latest();

        // Recherche par mots-clés (titre ou description)
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filtre par localisation
        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        // Filtre par gamme de prix
        if ($request->filled('price_min') || $request->filled('price_max')) {
            $query->byPriceRange(
                $request->price_min ? (float) $request->price_min : null,
                $request->price_max ? (float) $request->price_max : null
            );
        }

        // Filtre par condition (état)
        if ($request->filled('condition')) {
            $query->byCondition($request->condition);
        }

        $ads = $query->paginate(8)->withQueryString();

        return view('welcome', [
            'ads'        => $ads,
            'categories' => Ad::CATEGORIES,
            'conditions' => Ad::CONDITIONS,
            'filters'    => $request->only(['search', 'category', 'location', 'price_min', 'price_max', 'condition']),
        ]);
    }

    /**
     * Affiche le formulaire de création d'annonce.
     * Requiert authentification et email vérifié.
     */
    public function create(): View
    {
        return view('ads.create', [
            'categories' => Ad::CATEGORIES,
            'conditions' => Ad::CONDITIONS,
        ]);
    }

    /**
     * Enregistre une nouvelle annonce en base.
     * Valide toutes les données et gère l'upload de photo.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'min:5', 'max:150'],
            'category'    => ['required', 'string', 'in:' . implode(',', Ad::CATEGORIES)],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'price'       => ['required', 'numeric', 'min:0', 'max:999999999'],
            'location'    => ['required', 'string', 'max:100'],
            'condition'   => ['required', 'in:new,good,used'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ], [
            'title.required'       => 'Le titre est obligatoire.',
            'title.min'            => 'Le titre doit contenir au moins 5 caractères.',
            'category.required'    => 'La catégorie est obligatoire.',
            'category.in'          => 'La catégorie sélectionnée est invalide.',
            'description.required' => 'La description est obligatoire.',
            'description.min'      => 'La description doit contenir au moins 20 caractères.',
            'price.required'       => 'Le prix est obligatoire.',
            'price.numeric'        => 'Le prix doit être un nombre.',
            'price.min'            => 'Le prix ne peut pas être négatif.',
            'location.required'    => 'La localisation est obligatoire.',
            'condition.required'   => 'L\'état est obligatoire.',
            'photo.image'          => 'Le fichier doit être une image.',
            'photo.mimes'          => 'Formats acceptés : JPEG, PNG, JPG, WEBP.',
            'photo.max'            => 'La photo ne doit pas dépasser 5 Mo.',
        ]);

        // Gestion de l'upload de photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('ads', 'public');
        }

        $ad = $request->user()->ads()->create([
            'title'       => $validated['title'],
            'category'    => $validated['category'],
            'description' => $validated['description'],
            'price'       => $validated['price'],
            'location'    => $validated['location'],
            'condition'   => $validated['condition'],
            'photo'       => $photoPath,
        ]);

        return redirect()->route('ads.show', $ad)
            ->with('success', 'Annonce publiée avec succès !');
    }

    /**
     * Affiche le détail d'une annonce.
     */
    public function show(Ad $ad): View
    {
        $ad->load('user');
        return view('ads.show', compact('ad'));
    }

    /**
     * Affiche le formulaire de modification.
     * Seul le propriétaire de l'annonce peut y accéder.
     */
    public function edit(Ad $ad): View
    {
        $this->authorize('update', $ad);

        return view('ads.edit', [
            'ad'         => $ad,
            'categories' => Ad::CATEGORIES,
            'conditions' => Ad::CONDITIONS,
        ]);
    }

    /**
     * Met à jour une annonce existante.
     * Seul le propriétaire peut modifier. Gère le remplacement de photo.
     */
    public function update(Request $request, Ad $ad): RedirectResponse
    {
        $this->authorize('update', $ad);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'min:5', 'max:150'],
            'category'    => ['required', 'string', 'in:' . implode(',', Ad::CATEGORIES)],
            'description' => ['required', 'string', 'min:20', 'max:5000'],
            'price'       => ['required', 'numeric', 'min:0', 'max:999999999'],
            'location'    => ['required', 'string', 'max:100'],
            'condition'   => ['required', 'in:new,good,used'],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ]);

        // Remplacement de la photo si une nouvelle est uploadée
        if ($request->hasFile('photo')) {
            // Suppression de l'ancienne photo
            if ($ad->photo) {
                Storage::disk('public')->delete($ad->photo);
            }
            $validated['photo'] = $request->file('photo')->store('ads', 'public');
        } else {
            unset($validated['photo']);
        }

        $ad->update($validated);

        return redirect()->route('ads.show', $ad)
            ->with('success', 'Annonce mise à jour avec succès !');
    }

    /**
     * Supprime une annonce.
     * Seul le propriétaire peut supprimer. Supprime aussi la photo associée.
     */
    public function destroy(Ad $ad): RedirectResponse
    {
        $this->authorize('delete', $ad);

        // Suppression de la photo si elle existe
        if ($ad->photo) {
            Storage::disk('public')->delete($ad->photo);
        }

        $ad->delete();

        return redirect()->route('home')
            ->with('success', 'Annonce supprimée avec succès.');
    }
}
