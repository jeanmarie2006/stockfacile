@extends('layouts.app')
@section('titre', 'Bons de commande')
@section('contenu')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
  <div><h1 class="text-2xl font-extrabold text-slate-900">Bons de commande fournisseur</h1><p class="text-sm text-slate-500">{{ $aCommander }} produit(s) sous le seuil d’alerte à réapprovisionner.</p></div>
  <form method="post" action="{{ route('commandes.generer') }}">@csrf<button class="btn-primary" @disabled(!$aCommander)>⚙ Générer automatiquement</button></form>
</div>
<div class="grid gap-4 md:grid-cols-2">
@forelse($commandes as $c)
  <a href="{{ route('commandes.show', $c) }}" class="card block p-5 transition hover:-translate-y-0.5 hover:shadow-md">
    <div class="flex items-center justify-between"><b class="text-lg text-slate-900">{{ $c->numero }}</b>
      <span class="badge {{ ['brouillon' => 'bg-slate-100 text-slate-600', 'envoyee' => 'bg-amber-100 text-amber-700', 'recue' => 'bg-emerald-100 text-emerald-700'][$c->statut] }}">{{ ['brouillon' => 'Brouillon', 'envoyee' => 'Envoyée', 'recue' => 'Reçue'][$c->statut] }}</span></div>
    <p class="mt-1 text-sm text-slate-500">{{ $c->fournisseur?->nom ?? 'Fournisseur non défini' }} · @datefr($c->created_at)</p>
    <p class="mt-3 text-sm text-slate-600">{{ $c->lignes->count() }} ligne(s) · <b>@fcfa($c->total)</b></p>
  </a>
@empty
  <div class="card col-span-full py-14 text-center text-slate-500">Aucun bon de commande. Générez-les depuis les alertes de stock.</div>
@endforelse
</div>
@endsection
