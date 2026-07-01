<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    /**
     * Les attributs modifiables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'photo',
        'price',
        'location',
        'condition',
    ];

    /**
     * Catégories disponibles pour les annonces.
     */
    public const CATEGORIES = [
        'Automobile',
        'Immobilier',
        'Électronique',
        'Informatique',
        'Vêtements',
        'Meubles',
        'Sports & Loisirs',
        'Emploi',
        'Services',
        'Autres',
    ];

    /**
     * États disponibles pour les annonces.
     */
    public const CONDITIONS = [
        'new'  => 'Neuf',
        'good' => 'Bon état',
        'used' => 'Occasion',
    ];

    /**
     * Une annonce appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retourne l'URL de la photo ou une image placeholder.
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return 'https://via.placeholder.com/400x300?text=No+Image';
    }

    /**
     * Retourne le libellé de la condition.
     */
    public function getConditionLabelAttribute(): string
    {
        return self::CONDITIONS[$this->condition] ?? $this->condition;
    }

    /**
     * Scope : recherche par titre ou description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope : filtre par catégorie.
     */
    public function scopeByCategory($query, ?string $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Scope : filtre par localisation.
     */
    public function scopeByLocation($query, ?string $location)
    {
        if ($location && $location !== 'all') {
            return $query->where('location', 'like', "%{$location}%");
        }
        return $query;
    }

    /**
     * Scope : filtre par gamme de prix.
     */
    public function scopeByPriceRange($query, ?float $min, ?float $max)
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope : filtre par condition (état).
     */
    public function scopeByCondition($query, ?string $condition)
    {
        if ($condition && $condition !== 'all') {
            return $query->where('condition', $condition);
        }
        return $query;
    }
}
