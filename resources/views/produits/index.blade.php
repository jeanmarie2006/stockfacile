@extends('layouts.app')
@section('titre', 'Produits')
@section('contenu')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
  <h1 class="text-2xl font-extrabold text-slate-900">Produits <span class="text-slate-400">({{ $produits->total() }})</span></h1>
  @if(auth()->user()->estGerant())<a href="{{ route('produits.create') }}" class="btn-primary">＋ Nouveau produit</a>@endif
</div>
<form method="get" class="card mb-5 grid gap-3 p-4 md:grid-cols-4" role="search">
  <input name="q" value="{{ request('q') }}" class="input md:col-span-2" placeholder="Nom ou code-barres…" aria-label="Recherche">
  <select name="categorie" class="input" aria-label="Catégorie"><option value="">Toutes les catégories</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected(request('categorie') == $c->id)>{{ $c->nom }}</option>@endforeach</select>
  <div class="flex gap-2"><select name="etat" class="input" aria-label="État"><option value="">Tous les états</option><option value="ok" @selected(request('etat')==='ok')>Stock normal</option><option value="alerte" @selected(request('etat')==='alerte')>Sous le seuil</option><option value="rupture" @selected(request('etat')==='rupture')>En rupture</option></select><button class="btn-primary">Filtrer</button></div>
</form>
<div class="card overflow-hidden"><div class="overflow-x-auto"><table class="w-full">
  <thead class="bg-slate-50"><tr><th class="th">Produit</th><th class="th">Catégorie</th><th class="th text-right">Prix vente</th><th class="th text-right">Stock</th><th class="th">État</th><th class="th"></th></tr></thead>
  <tbody class="divide-y divide-slate-100">
  @forelse($produits as $p)
    <tr class="hover:bg-slate-50">
      <td class="td"><a href="{{ route('produits.show', $p) }}" class="font-semibold text-slate-900 hover:text-brand-700">{{ $p->nom }}</a>@if($p->code)<span class="block text-xs text-slate-400">{{ $p->code }}</span>@endif</td>
      <td class="td text-slate-500">{{ $p->categorie?->nom ?? '—' }}</td>
      <td class="td text-right">@fcfa($p->prix)</td>
      <td class="td text-right font-bold">{{ $p->quantite }} <span class="font-normal text-slate-400">{{ $p->unite }}</span></td>
      <td class="td">@if($p->etat === 'rupture')<span class="badge bg-rose-100 text-rose-700">Rupture</span>@elseif($p->etat === 'alerte')<span class="badge bg-amber-100 text-amber-700">Stock bas</span>@else<span class="badge bg-emerald-50 text-emerald-700">OK</span>@endif</td>
      <td class="td whitespace-nowrap text-right"><a class="btn-ghost btn-sm" href="{{ route('mouvements.create', ['produit' => $p->id, 'type' => 'sortie']) }}">− / ＋</a>@if(auth()->user()->estGerant()) <a class="btn-ghost btn-sm" href="{{ route('produits.edit', $p) }}">✎</a>@endif</td>
    </tr>
  @empty
    <tr><td colspan="6" class="td py-12 text-center text-slate-500">Aucun produit ne correspond à votre recherche.</td></tr>
  @endforelse
  </tbody></table></div></div>
{{ $produits->links() }}
@endsection
