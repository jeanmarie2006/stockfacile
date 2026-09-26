<?php

namespace App\Http\Controllers;

use App\Models\Mouvement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MouvementController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['produit' => 'nullable|integer', 'type' => 'nullable|in:entree,sortie', 'du' => 'nullable|date', 'au' => 'nullable|date']);
        $q = Mouvement::with(['produit:id,nom,unite', 'user:id,name'])->latest('id');
        $q->when($request->query('produit'), fn ($w, $v) => $w->where('produit_id', $v))
            ->when($request->query('type'), fn ($w, $v) => $w->where('type', $v))
            ->when($request->query('du'), fn ($w, $v) => $w->whereDate('created_at', '>=', $v))
            ->when($request->query('au'), fn ($w, $v) => $w->whereDate('created_at', '<=', $v));

        return view('mouvements.index', ['mouvements' => $q->paginate(20)->withQueryString(), 'produits' => Produit::orderBy('nom')->get(['id', 'nom'])]);
    }

    public function create(Request $request)
    {
        return view('mouvements.create', [
            'produits' => Produit::orderBy('nom')->get(['id', 'nom', 'quantite', 'unite', 'code']),
            'choisi' => $request->query('produit'),
            'type' => $request->query('type', 'sortie'),
            'motifs' => Mouvement::MOTIFS,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'produit_id' => ['required', 'exists:produits,id'],
            'type' => ['required', Rule::in(['entree', 'sortie'])],
            'quantite' => ['required', 'integer', 'min:1', 'max:1000000'],
            'motif' => ['required', 'string', Rule::in(array_keys(Mouvement::MOTIFS[$request->input('type', 'sortie')] ?? []))],
            'note' => ['nullable', 'string', 'max:160'],
        ]);
        try {
            $m = Mouvement::enregistrer(Produit::findOrFail($data['produit_id']), $data['type'], (int) $data['quantite'], $data['motif'], $data['note'] ?? null, $request->user()->id);
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['quantite' => $e->getMessage()]);
        }
        $p = $m->produit;
        $avert = $p->en_rupture ? " Attention : {$p->nom} est en rupture de stock." : ($p->sous_seuil ? " Attention : le stock de {$p->nom} est sous le seuil d’alerte ({$p->quantite} restant)." : '');

        return redirect()->route('mouvements.index')->with('ok', 'Mouvement enregistré.'.$avert);
    }

    /** Recherche d'un produit par code-barres / QR (saisie au clavier ou lecteur USB). */
    public function parCode(Request $request)
    {
        $code = trim((string) $request->query('code'));
        $p = $code === '' ? null : Produit::where('code', $code)->first();
        if (! $p) {
            return redirect()->route('mouvements.create')->withErrors(['code' => "Aucun produit avec le code « {$code} »."]);
        }

        return redirect()->route('mouvements.create', ['produit' => $p->id, 'type' => $request->query('type', 'sortie')]);
    }
}
