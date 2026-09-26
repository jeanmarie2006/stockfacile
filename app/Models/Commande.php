<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Commande extends Model
{
    protected $table = 'commandes';

    protected $guarded = [];

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(CommandeLigne::class);
    }

    public function getTotalAttribute(): int
    {
        return $this->lignes->sum(fn ($l) => $l->quantite * $l->prix_unitaire);
    }

    public static function prochainNumero(): string
    {
        return 'BC-'.now()->format('Ym').'-'.str_pad((string) (static::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count() + 1), 3, '0', STR_PAD_LEFT);
    }
}
