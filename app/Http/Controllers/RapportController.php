<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Rapport mensuel exportable (PDF ou CSV lisible dans Excel). */
class RapportController extends Controller
{
    private function data(Request $request): array
    {
        $mois = preg_match('/^\d{4}-\d{2}$/', (string) $request->query('mois')) ? $request->query('mois') : now()->format('Y-m');
        [$y, $m] = explode('-', $mois);
        $lignes = Mouvement::whereYear('created_at', $y)->whereMonth('created_at', $m)
            ->select('produit_id', DB::raw("sum(case when type='entree' then quantite else 0 end) as entrees"), DB::raw("sum(case when type='sortie' then quantite else 0 end) as sorties"),
                DB::raw("sum(case when type='sortie' and motif='vente' then quantite else 0 end) as ventes"))
            ->groupBy('produit_id')->with('produit:id,nom,unite,prix,prix_achat,quantite')->get()
            ->sortByDesc('ventes')->values();
        $ca = $lignes->sum(fn ($l) => $l->ventes * ($l->produit->prix ?? 0));
        $marge = $lignes->sum(fn ($l) => $l->ventes * (($l->produit->prix ?? 0) - ($l->produit->prix_achat ?? 0)));

        return [
            'mois' => $mois, 'lignes' => $lignes, 'ca' => $ca, 'marge' => $marge,
            'valeur' => Produit::all()->sum(fn ($p) => $p->valeur),
            'ruptures' => Produit::where('quantite', '<=', 0)->count(),
            'alertes' => Produit::alerte()->where('quantite', '>', 0)->count(),
        ];
    }

    public function index(Request $request)
    {
        return view('rapports.index', $this->data($request));
    }

    public function pdf(Request $request)
    {
        $d = $this->data($request);

        return Pdf::loadView('pdf.rapport', $d)->setPaper('a4')->stream("rapport-stock-{$d['mois']}.pdf");
    }

    public function csv(Request $request)
    {
        $d = $this->data($request);

        return response()->streamDownload(function () use ($d) {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF"); // UTF-8 pour Excel
            fputcsv($f, ['Produit', 'Entrées', 'Sorties', 'Ventes', 'Prix de vente (FCFA)', 'Chiffre d\'affaires (FCFA)', 'Stock actuel'], ';');
            foreach ($d['lignes'] as $l) {
                fputcsv($f, [$l->produit->nom, $l->entrees, $l->sorties, $l->ventes, $l->produit->prix, $l->ventes * $l->produit->prix, $l->produit->quantite], ';');
            }
            fclose($f);
        }, "rapport-stock-{$d['mois']}.csv", ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
