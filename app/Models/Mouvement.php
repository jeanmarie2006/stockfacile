<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mouvement extends Model
{
    protected $table = 'mouvements';

    protected $guarded = [];

    public const MOTIFS = [
        'entree' => ['achat' => 'Achat fournisseur', 'retour' => 'Retour client', 'inventaire' => 'Correction d’inventaire', 'autre' => 'Autre entrée'],
        'sortie' => ['vente' => 'Vente', 'casse' => 'Perte / casse', 'usage' => 'Usage interne', 'inventaire' => 'Correction d’inventaire', 'autre' => 'Autre sortie'],
    ];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMotifLabelAttribute(): string
    {
        return self::MOTIFS[$this->type][$this->motif] ?? $this->motif;
    }

    /** Enregistre un mouvement et met à jour le stock de façon atomique (refuse une sortie supérieure au stock). */
    public static function enregistrer(Produit $produit, string $type, int $quantite, string $motif, ?string $note, ?int $userId): self
    {
        return \DB::transaction(function () use ($produit, $type, $quantite, $motif, $note, $userId) {
            $p = Produit::whereKey($produit->id)->lockForUpdate()->firstOrFail();
            if ($type === 'sortie' && $quantite > $p->quantite) {
                throw new \DomainException("Stock insuffisant pour « {$p->nom} » : {$p->quantite} {$p->unite} disponible(s).");
            }
            $p->quantite += $type === 'entree' ? $quantite : -$quantite;
            $p->save();

            return self::create([
                'produit_id' => $p->id, 'type' => $type, 'quantite' => $quantite, 'motif' => $motif,
                'note' => $note, 'stock_apres' => $p->quantite, 'user_id' => $userId,
            ]);
        });
    }
}
