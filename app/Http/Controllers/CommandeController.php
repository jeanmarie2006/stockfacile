<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\CommandeLigne;
use App\Models\Mouvement;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** Bons de commande fournisseur générés automatiquement à partir des produits sous le seuil d'alerte. */
class CommandeController extends Controller
{
    public function index()
    {
        return view('commandes.index', [
            'commandes' => Commande::with('fournisseur', 'lignes')->latest()->get(),
            'aCommander' => Produit::alerte()->count(),
        ]);
    }

    public function generer(Request $request)
    {
        $produits = Produit::alerte()->get()->groupBy(fn ($p) => $p->fournisseur_id ?? 0);
        if ($produits->isEmpty()) {
            return back()->with('ok', 'Aucun produit sous le seuil d’alerte : rien à commander.');
        }
        $n = 0;
        DB::transaction(function () use ($produits, $request, &$n) {
            foreach ($produits as $fid => $liste) {
                $c = Commande::create(['numero' => Commande::prochainNumero(), 'fournisseur_id' => $fid ?: null, 'user_id' => $request->user()->id]);
                foreach ($liste as $p) {
                    // on remonte le stock à deux fois le seuil d'alerte
                    CommandeLigne::create(['commande_id' => $c->id, 'produit_id' => $p->id, 'quantite' => max(1, $p->seuil_alerte * 2 - $p->quantite), 'prix_unitaire' => $p->prix_achat]);
                }
                $n++;
            }
        });

        return redirect()->route('commandes.index')->with('ok', "{$n} bon(s) de commande généré(s) à partir des produits sous le seuil.");
    }

    public function show(Commande $commande)
    {
        return view('commandes.show', ['commande' => $commande->load('fournisseur', 'lignes.produit', 'lignes.produit.categorie')]);
    }

    public function pdf(Commande $commande)
    {
        $commande->load('fournisseur', 'lignes.produit');

        return Pdf::loadView('pdf.commande', ['commande' => $commande])->setPaper('a4')->stream($commande->numero.'.pdf');
    }

    public function statut(Request $request, Commande $commande)
    {
        $statut = $request->validate(['statut' => ['required', 'in:envoyee,recue']])['statut'];
        abort_if($commande->statut === 'recue', 422, 'Cette commande est déjà réceptionnée.');
        DB::transaction(function () use ($commande, $statut, $request) {
            if ($statut === 'recue') {
                foreach ($commande->lignes()->with('produit')->get() as $l) {
                    Mouvement::enregistrer($l->produit, 'entree', $l->quantite, 'achat', "Réception {$commande->numero}", $request->user()->id);
                }
            }
            $commande->update(['statut' => $statut]);
        });

        return back()->with('ok', $statut === 'recue' ? 'Commande réceptionnée : le stock a été mis à jour.' : 'Commande marquée comme envoyée.');
    }

    public function destroy(Commande $commande)
    {
        abort_if($commande->statut === 'recue', 422, 'Une commande réceptionnée ne peut pas être supprimée.');
        $commande->delete();

        return redirect()->route('commandes.index')->with('ok', 'Bon de commande supprimé.');
    }
}
