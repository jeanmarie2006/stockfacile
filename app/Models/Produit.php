<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    protected $table = 'produits';

    protected $guarded = [];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(Mouvement::class);
    }

    public function getEnRuptureAttribute(): bool
    {
        return $this->quantite <= 0;
    }

    public function getSousSeuilAttribute(): bool
    {
        return $this->quantite > 0 && $this->quantite <= $this->seuil_alerte;
    }

    /** ok | alerte | rupture */
    public function getEtatAttribute(): string
    {
        return $this->en_rupture ? 'rupture' : ($this->sous_seuil ? 'alerte' : 'ok');
    }

    public function getValeurAttribute(): int
    {
        return max(0, $this->quantite) * $this->prix_achat;
    }

    public function scopeAlerte($query)
    {
        return $query->whereColumn('quantite', '<=', 'seuil_alerte');
    }
}
