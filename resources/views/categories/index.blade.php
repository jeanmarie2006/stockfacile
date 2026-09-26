@extends('layouts.app')
@section('titre', 'Catégories')
@section('contenu')
<h1 class="mb-5 text-2xl font-extrabold text-slate-900">Catégories</h1>
<form method="post" action="{{ route('categories.store') }}" class="card mb-5 flex max-w-xl gap-3 p-4">@csrf<input name="nom" class="input" placeholder="Nouvelle catégorie (ex. Jouets)" required maxlength="60" aria-label="Nom de la catégorie"><button class="btn-primary">Ajouter</button></form>
<div class="card max-w-xl overflow-hidden"><ul class="divide-y divide-slate-100">
@forelse($categories as $c)
  <li class="flex items-center justify-between px-5 py-3"><div><b class="text-slate-900">{{ $c->nom }}</b> <span class="text-sm text-slate-400">· {{ $c->produits_count }} produit(s)</span></div>
    <form method="post" action="{{ route('categories.destroy', $c) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600" aria-label="Supprimer {{ $c->nom }}">🗑</button></form></li>
@empty<li class="px-5 py-10 text-center text-slate-500">Aucune catégorie.</li>@endforelse
</ul></div>
@endsection
