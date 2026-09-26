<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommandeLigne extends Model
{
    protected $table = 'commande_lignes';

    protected $guarded = [];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class);
    }
}
