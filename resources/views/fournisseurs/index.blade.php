@extends('layouts.app')
@section('titre', 'Fournisseurs')
@section('contenu')
<div class="mb-5 flex items-center justify-between"><h1 class="text-2xl font-extrabold text-slate-900">Fournisseurs</h1><a href="{{ route('fournisseurs.create') }}" class="btn-primary">＋ Nouveau fournisseur</a></div>
<div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
@forelse($fournisseurs as $f)
  <article class="card p-5"><h2 class="font-extrabold text-slate-900">{{ $f->nom }}</h2><p class="text-sm text-slate-500">{{ $f->produits_count }} produit(s)</p>
    <ul class="mt-3 space-y-1 text-sm text-slate-600">@if($f->telephone)<li>☎ {{ $f->telephone }}</li>@endif @if($f->email)<li>✉ {{ $f->email }}</li>@endif @if($f->adresse)<li>⌂ {{ $f->adresse }}</li>@endif</ul>
    <div class="mt-4 flex gap-2"><a class="btn-ghost btn-sm" href="{{ route('fournisseurs.edit', $f) }}">✎ Modifier</a><form method="post" action="{{ route('fournisseurs.destroy', $f) }}" onsubmit="return confirm('Supprimer ce fournisseur ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600">🗑</button></form></div></article>
@empty<p class="card col-span-full py-12 text-center text-slate-500">Aucun fournisseur.</p>@endforelse
</div>
@endsection
