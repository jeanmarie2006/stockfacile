@extends('layouts.app')
@section('titre', 'Mouvements')
@section('contenu')
<div class="mb-5 flex flex-wrap items-center justify-between gap-3">
  <h1 class="text-2xl font-extrabold text-slate-900">Historique des mouvements</h1>
  <a href="{{ route('mouvements.create') }}" class="btn-primary">＋ Enregistrer un mouvement</a>
</div>
<form method="get" class="card mb-5 grid gap-3 p-4 md:grid-cols-5">
  <select name="produit" class="input md:col-span-2" aria-label="Produit"><option value="">Tous les produits</option>@foreach($produits as $p)<option value="{{ $p->id }}" @selected(request('produit') == $p->id)>{{ $p->nom }}</option>@endforeach</select>
  <select name="type" class="input" aria-label="Type"><option value="">Entrées et sorties</option><option value="entree" @selected(request('type')==='entree')>Entrées</option><option value="sortie" @selected(request('type')==='sortie')>Sorties</option></select>
  <input type="date" name="du" value="{{ request('du') }}" class="input" aria-label="Du"><input type="date" name="au" value="{{ request('au') }}" class="input" aria-label="Au">
  <button class="btn-primary md:col-span-5 md:w-auto md:justify-self-start">Filtrer</button>
</form>
<div class="card overflow-hidden"><div class="overflow-x-auto"><table class="w-full">
  <thead class="bg-slate-50"><tr><th class="th">Date</th><th class="th">Produit</th><th class="th">Type</th><th class="th text-right">Qté</th><th class="th">Motif</th><th class="th text-right">Stock après</th><th class="th">Par</th></tr></thead>
  <tbody class="divide-y divide-slate-100">
  @forelse($mouvements as $m)
    <tr class="hover:bg-slate-50"><td class="td whitespace-nowrap">@datefr($m->created_at, 'd M Y, H:i')</td><td class="td font-semibold"><a class="hover:text-brand-700" href="{{ route('produits.show', $m->produit_id) }}">{{ $m->produit->nom }}</a></td>
      <td class="td"><span class="badge {{ $m->type === 'entree' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">{{ $m->type === 'entree' ? '＋ Entrée' : '− Sortie' }}</span></td>
      <td class="td text-right font-bold">{{ $m->quantite }}</td><td class="td text-slate-500">{{ $m->motif_label }}@if($m->note)<span class="block text-xs text-slate-400">{{ $m->note }}</span>@endif</td><td class="td text-right">{{ $m->stock_apres }}</td><td class="td text-slate-500">{{ $m->user?->name }}</td></tr>
  @empty<tr><td colspan="7" class="td py-12 text-center text-slate-500">Aucun mouvement pour ces critères.</td></tr>@endforelse
  </tbody></table></div></div>
{{ $mouvements->links() }}
@endsection
