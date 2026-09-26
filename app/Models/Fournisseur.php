<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    protected $guarded = [];

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class);
    }
}
