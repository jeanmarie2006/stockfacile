<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Produit;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $produits = Produit::with('categorie')->get();
        $valeur = $produits->sum(fn ($p) => $p->valeur);
        $valeurVente = $produits->sum(fn ($p) => max(0, $p->quantite) * $p->prix);

        // 14 derniers jours : entrées / sorties
        $debut = now()->subDays(13)->startOfDay();
        $rows = Mouvement::where('created_at', '>=', $debut)
            ->select(DB::raw('DATE(created_at) as jour'), 'type', DB::raw('sum(quantite) as total'))
            ->groupBy('jour', 'type')->get();
        $serie = collect(range(13, 0))->map(function ($i) use ($rows) {
            $j = now()->subDays($i)->toDateString();

            return [
                'jour' => $j,
                'entree' => (int) ($rows->first(fn ($r) => $r->jour === $j && $r->type === 'entree')->total ?? 0),
                'sortie' => (int) ($rows->first(fn ($r) => $r->jour === $j && $r->type === 'sortie')->total ?? 0),
            ];
        });

        $top = Mouvement::where('type', 'sortie')->where('motif', 'vente')->where('created_at', '>=', now()->subDays(30))
            ->select('produit_id', DB::raw('sum(quantite) as total'))->groupBy('produit_id')->orderByDesc('total')->limit(5)->with('produit:id,nom,unite')->get();

        return view('dashboard', [
            'nbProduits' => $produits->count(),
            'valeur' => $valeur,
            'valeurVente' => $valeurVente,
            'ruptures' => $produits->filter->en_rupture->sortBy('nom'),
            'alertes' => $produits->filter->sous_seuil->sortBy('quantite'),
            'recents' => Mouvement::with(['produit:id,nom,unite', 'user:id,name'])->latest()->limit(8)->get(),
            'serie' => $serie,
            'top' => $top,
        ]);
    }
}
