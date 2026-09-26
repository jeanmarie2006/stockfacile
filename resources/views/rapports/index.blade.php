@extends('layouts.app')
@section('titre', 'Rapports')
@section('contenu')
<div class="mb-5 flex flex-wrap items-end justify-between gap-3">
  <div><h1 class="text-2xl font-extrabold text-slate-900">Rapport mensuel</h1><p class="text-sm text-slate-500">Ventes, marge et mouvements du mois choisi.</p></div>
  <form method="get" class="flex items-end gap-2"><div><label class="label" for="mois">Mois</label><input id="mois" name="mois" type="month" value="{{ $mois }}" class="input"></div><button class="btn-primary">Afficher</button>
    <a class="btn-ghost" href="{{ route('rapports.pdf', ['mois' => $mois]) }}" target="_blank">📄 PDF</a><a class="btn-ghost" href="{{ route('rapports.csv', ['mois' => $mois]) }}">📊 Excel (CSV)</a></form>
</div>
<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Chiffre d’affaires</p><p class="mt-1 text-2xl font-extrabold text-brand-700">@fcfa($ca)</p></div>
  <div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Marge brute</p><p class="mt-1 text-2xl font-extrabold text-emerald-600">@fcfa($marge)</p></div>
  <div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Valeur du stock</p><p class="mt-1 text-2xl font-extrabold">@fcfa($valeur)</p></div>
  <div class="card p-5"><p class="text-xs font-bold uppercase text-slate-400">Ruptures / bas</p><p class="mt-1 text-2xl font-extrabold"><span class="text-rose-600">{{ $ruptures }}</span> / <span class="text-amber-500">{{ $alertes }}</span></p></div>
</div>
<div class="card mt-6 overflow-hidden"><div class="overflow-x-auto"><table class="w-full">
  <thead class="bg-slate-50"><tr><th class="th">Produit</th><th class="th text-right">Entrées</th><th class="th text-right">Sorties</th><th class="th text-right">Ventes</th><th class="th text-right">CA</th><th class="th text-right">Stock</th></tr></thead>
  <tbody class="divide-y divide-slate-100">@forelse($lignes as $l)<tr><td class="td font-semibold">{{ $l->produit->nom }}</td><td class="td text-right text-emerald-700">{{ $l->entrees }}</td><td class="td text-right">{{ $l->sorties }}</td><td class="td text-right font-bold">{{ $l->ventes }}</td><td class="td text-right">@fcfa($l->ventes * $l->produit->prix)</td><td class="td text-right text-slate-500">{{ $l->produit->quantite }}</td></tr>@empty<tr><td colspan="6" class="td py-12 text-center text-slate-500">Aucun mouvement ce mois-ci.</td></tr>@endforelse</tbody></table></div></div>
@endsection
