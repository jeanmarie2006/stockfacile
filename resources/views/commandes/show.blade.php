@extends('layouts.app')
@section('titre', $commande->numero)
@section('contenu')
<a href="{{ route('commandes.index') }}" class="text-sm font-semibold text-slate-500 hover:text-brand-700">← Bons de commande</a>
<div class="mb-6 mt-2 flex flex-wrap items-start justify-between gap-3">
  <div><h1 class="text-2xl font-extrabold text-slate-900">{{ $commande->numero }}</h1><p class="text-sm text-slate-500">{{ $commande->fournisseur?->nom ?? 'Fournisseur non défini' }} · @datefr($commande->created_at) · <b>{{ ['brouillon' => 'Brouillon', 'envoyee' => 'Envoyée', 'recue' => 'Reçue'][$commande->statut] }}</b></p></div>
  <div class="flex flex-wrap gap-2">
    <a class="btn-ghost" href="{{ route('commandes.pdf', $commande) }}" target="_blank">📄 PDF</a>
    @if($commande->statut === 'brouillon')<form method="post" action="{{ route('commandes.statut', $commande) }}">@csrf<input type="hidden" name="statut" value="envoyee"><button class="btn-ghost">✉ Marquer envoyée</button></form>@endif
    @if($commande->statut !== 'recue')<form method="post" action="{{ route('commandes.statut', $commande) }}" onsubmit="return confirm('Confirmer la réception ? Le stock sera augmenté des quantités commandées.')">@csrf<input type="hidden" name="statut" value="recue"><button class="btn-primary">📥 Marquer reçue</button></form>@endif
  </div>
</div>
<div class="card overflow-hidden"><div class="overflow-x-auto"><table class="w-full">
  <thead class="bg-slate-50"><tr><th class="th">Produit</th><th class="th text-right">Quantité</th><th class="th text-right">Prix unitaire</th><th class="th text-right">Total</th></tr></thead>
  <tbody class="divide-y divide-slate-100">@foreach($commande->lignes as $l)<tr><td class="td font-semibold">{{ $l->produit->nom }}<span class="block text-xs font-normal text-slate-400">Stock actuel : {{ $l->produit->quantite }} {{ $l->produit->unite }}</span></td><td class="td text-right">{{ $l->quantite }} {{ $l->produit->unite }}</td><td class="td text-right">@fcfa($l->prix_unitaire)</td><td class="td text-right font-bold">@fcfa($l->quantite * $l->prix_unitaire)</td></tr>@endforeach</tbody>
  <tfoot><tr class="bg-slate-50"><td colspan="3" class="td text-right font-bold">Total</td><td class="td text-right text-lg font-extrabold">@fcfa($commande->total)</td></tr></tfoot></table></div></div>
@if($commande->statut !== 'recue')<form method="post" action="{{ route('commandes.destroy', $commande) }}" class="mt-5" onsubmit="return confirm('Supprimer ce bon de commande ?')">@csrf @method('DELETE')<button class="btn-danger btn-sm">🗑 Supprimer</button></form>@endif
@endsection
