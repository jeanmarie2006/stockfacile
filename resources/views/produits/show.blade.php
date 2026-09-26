@extends('layouts.app')
@section('titre', $produit->nom)
@section('contenu')
<a href="{{ route('produits.index') }}" class="text-sm font-semibold text-slate-500 hover:text-brand-700">← Produits</a>
<div class="mb-6 mt-2 flex flex-wrap items-start justify-between gap-3">
  <div><h1 class="text-2xl font-extrabold text-slate-900">{{ $produit->nom }}</h1><p class="text-sm text-slate-500">{{ $produit->categorie?->nom ?? 'Sans catégorie' }}@if($produit->code) · code {{ $produit->code }}@endif @if($produit->fournisseur) · {{ $produit->fournisseur->nom }}@endif</p></div>
  <div class="flex gap-2"><a class="btn-primary" href="{{ route('mouvements.create', ['produit' => $produit->id, 'type' => 'sortie']) }}">− Sortie</a><a class="btn-ghost" href="{{ route('mouvements.create', ['produit' => $produit->id, 'type' => 'entree']) }}">＋ Entrée</a>@if(auth()->user()->estGerant())<a class="btn-ghost" href="{{ route('produits.edit', $produit) }}">✎ Modifier</a>@endif</div>
</div>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <div class="card p-5 {{ $produit->etat === 'rupture' ? '!border-rose-300 !bg-rose-50' : ($produit->etat === 'alerte' ? '!border-amber-300 !bg-amber-50' : '') }}"><p class="text-xs font-bold uppercase text-slate-400">En stock</p><p class="text-3xl font-extrabold">{{ $produit->quantite }} <span class="text-base font-semibold text-slate-400">{{ $produit->unite }}</span></p><p class="text-xs font-semibold {{ $produit->etat === 'ok' ? 'text-emerald-600' : ($produit->etat === 'alerte' ? 'text-amber-700' : 'text-rose-700') }}">{{ ['ok' => 'Stock normal', 'alerte' => 'Sous le seuil d’alerte', 'rupture' => 'Rupture de stock'][$produit->etat] }}</p></div>
  <div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Seuil d’alerte</p><p class="text-3xl font-extrabold">{{ $produit->seuil_alerte }}</p></div>
  <div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Prix de vente</p><p class="text-xl font-extrabold">@fcfa($produit->prix)</p>@if(auth()->user()->estGerant())<p class="text-xs text-slate-500">Achat : @fcfa($produit->prix_achat)</p>@endif</div>
  @if(auth()->user()->estGerant())<div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Valeur du stock</p><p class="text-xl font-extrabold">@fcfa($produit->valeur)</p></div>@endif
</div>
<section class="card mt-6 overflow-hidden"><h2 class="p-5 pb-3 font-extrabold text-slate-900">Historique des mouvements</h2>
  <div class="overflow-x-auto"><table class="w-full"><thead class="bg-slate-50"><tr><th class="th">Date</th><th class="th">Type</th><th class="th text-right">Quantité</th><th class="th">Motif</th><th class="th text-right">Stock après</th><th class="th">Par</th></tr></thead><tbody class="divide-y divide-slate-100">
  @forelse($mouvements as $m)
    <tr><td class="td">@datefr($m->created_at, 'd M Y, H:i')</td><td class="td"><span class="badge {{ $m->type === 'entree' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">{{ $m->type === 'entree' ? '＋ Entrée' : '− Sortie' }}</span></td><td class="td text-right font-bold">{{ $m->quantite }}</td><td class="td text-slate-500">{{ $m->motif_label }}@if($m->note) <span class="text-slate-400">· {{ $m->note }}</span>@endif</td><td class="td text-right">{{ $m->stock_apres }}</td><td class="td text-slate-500">{{ $m->user?->name }}</td></tr>
  @empty<tr><td colspan="6" class="td py-10 text-center text-slate-500">Aucun mouvement.</td></tr>@endforelse
  </tbody></table></div></section>
{{ $mouvements->links() }}
@endsection
