<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Mouvement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:80', 'categorie' => 'nullable|integer', 'etat' => 'nullable|in:ok,alerte,rupture']);
        $q = Produit::with('categorie')->orderBy('nom');
        if ($t = trim((string) $request->query('q'))) {
            $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $t).'%';
            $q->where(fn ($w) => $w->where('nom', 'like', $like)->orWhere('code', 'like', $like));
        }
        if ($c = $request->query('categorie')) {
            $q->where('categorie_id', $c);
        }
        match ($request->query('etat')) {
            'rupture' => $q->where('quantite', '<=', 0),
            'alerte' => $q->where('quantite', '>', 0)->whereColumn('quantite', '<=', 'seuil_alerte'),
            'ok' => $q->whereColumn('quantite', '>', 'seuil_alerte'),
            default => null,
        };

        return view('produits.index', ['produits' => $q->paginate(15)->withQueryString(), 'categories' => Categorie::orderBy('nom')->get()]);
    }

    public function create()
    {
        return view('produits.form', ['produit' => new Produit(['seuil_alerte' => 5, 'unite' => 'pièce']), 'categories' => Categorie::orderBy('nom')->get(), 'fournisseurs' => Fournisseur::orderBy('nom')->get()]);
    }

    private function rules(?Produit $p = null): array
    {
        return [
            'nom' => ['required', 'string', 'max:120'],
            'code' => ['nullable', 'string', 'max:40', Rule::unique('produits', 'code')->ignore($p?->id)],
            'categorie_id' => ['nullable', 'exists:categories,id'],
            'fournisseur_id' => ['nullable', 'exists:fournisseurs,id'],
            'prix_achat' => ['required', 'integer', 'min:0', 'max:100000000'],
            'prix' => ['required', 'integer', 'min:0', 'max:100000000'],
            'seuil_alerte' => ['required', 'integer', 'min:0', 'max:100000'],
            'unite' => ['required', 'string', 'max:20'],
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules() + ['quantite' => ['required', 'integer', 'min:0', 'max:1000000']]);
        $data['code'] = ($data['code'] ?? null) ?: null;
        $qte = $data['quantite'];
        $p = Produit::create([...$data, 'quantite' => 0]);
        if ($qte > 0) {
            Mouvement::enregistrer($p, 'entree', $qte, 'inventaire', 'Stock initial', $request->user()->id);
        }

        return redirect()->route('produits.show', $p)->with('ok', 'Produit créé.');
    }

    public function show(Produit $produit)
    {
        return view('produits.show', ['produit' => $produit->load('categorie', 'fournisseur'), 'mouvements' => $produit->mouvements()->with('user:id,name')->latest()->paginate(10)]);
    }

    public function edit(Produit $produit)
    {
        return view('produits.form', ['produit' => $produit, 'categories' => Categorie::orderBy('nom')->get(), 'fournisseurs' => Fournisseur::orderBy('nom')->get()]);
    }

    public function update(Request $request, Produit $produit)
    {
        $data = $request->validate($this->rules($produit));
        $data['code'] = ($data['code'] ?? null) ?: null;
        $produit->update($data);

        return redirect()->route('produits.show', $produit)->with('ok', 'Produit mis à jour. Pour changer la quantité, enregistrez un mouvement de stock.');
    }

    public function destroy(Produit $produit)
    {
        $produit->delete();

        return redirect()->route('produits.index')->with('ok', 'Produit supprimé.');
    }
}
