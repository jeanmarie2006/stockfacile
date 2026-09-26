@extends('layouts.app')
@php $edit = $produit->exists; @endphp
@section('titre', $edit ? 'Modifier le produit' : 'Nouveau produit')
@section('contenu')
@php $hideErrors = true; @endphp
<a href="{{ $edit ? route('produits.show', $produit) : route('produits.index') }}" class="text-sm font-semibold text-slate-500 hover:text-brand-700">← Retour</a>
<h1 class="mb-5 mt-2 text-2xl font-extrabold text-slate-900">{{ $edit ? 'Modifier « '.$produit->nom.' »' : 'Nouveau produit' }}</h1>
<form method="post" action="{{ $edit ? route('produits.update', $produit) : route('produits.store') }}" class="card grid max-w-3xl gap-4 p-6 sm:grid-cols-2">
  @csrf @if($edit) @method('PUT') @endif
  @php $f = fn($n) => old($n, $produit->$n); $err = fn($n) => $errors->first($n); @endphp
  <div class="sm:col-span-2"><label class="label" for="nom">Nom du produit</label><input id="nom" name="nom" class="input" value="{{ $f('nom') }}" required>@if($err('nom'))<p class="mt-1 text-xs font-semibold text-rose-600">{{ $err('nom') }}</p>@endif</div>
  <div><label class="label" for="code">Code-barres / QR (facultatif)</label><input id="code" name="code" class="input" value="{{ $f('code') }}" placeholder="Scannez ou saisissez">@if($err('code'))<p class="mt-1 text-xs font-semibold text-rose-600">{{ $err('code') }}</p>@endif</div>
  <div><label class="label" for="categorie_id">Catégorie</label><select id="categorie_id" name="categorie_id" class="input"><option value="">—</option>@foreach($categories as $c)<option value="{{ $c->id }}" @selected($f('categorie_id') == $c->id)>{{ $c->nom }}</option>@endforeach</select></div>
  <div><label class="label" for="fournisseur_id">Fournisseur habituel</label><select id="fournisseur_id" name="fournisseur_id" class="input"><option value="">—</option>@foreach($fournisseurs as $c)<option value="{{ $c->id }}" @selected($f('fournisseur_id') == $c->id)>{{ $c->nom }}</option>@endforeach</select></div>
  <div><label class="label" for="unite">Unité</label><input id="unite" name="unite" class="input" value="{{ $f('unite') }}" required></div>
  <div><label class="label" for="prix_achat">Prix d’achat (FCFA)</label><input id="prix_achat" name="prix_achat" type="number" min="0" class="input" value="{{ $f('prix_achat') ?? 0 }}" required>@if($err('prix_achat'))<p class="mt-1 text-xs font-semibold text-rose-600">{{ $err('prix_achat') }}</p>@endif</div>
  <div><label class="label" for="prix">Prix de vente (FCFA)</label><input id="prix" name="prix" type="number" min="0" class="input" value="{{ $f('prix') ?? 0 }}" required>@if($err('prix'))<p class="mt-1 text-xs font-semibold text-rose-600">{{ $err('prix') }}</p>@endif</div>
  <div><label class="label" for="seuil_alerte">Seuil d’alerte</label><input id="seuil_alerte" name="seuil_alerte" type="number" min="0" class="input" value="{{ $f('seuil_alerte') }}" required></div>
  @unless($edit)<div><label class="label" for="quantite">Quantité initiale en stock</label><input id="quantite" name="quantite" type="number" min="0" class="input" value="{{ old('quantite', 0) }}" required></div>@else<p class="self-end text-xs text-slate-500">La quantité se modifie uniquement par des mouvements de stock (traçabilité).</p>@endunless
  <div class="flex gap-2 sm:col-span-2"><button class="btn-primary">{{ $edit ? 'Enregistrer' : 'Créer le produit' }}</button><a class="btn-ghost" href="{{ route('produits.index') }}">Annuler</a></div>
</form>
@if($edit)
<form method="post" action="{{ route('produits.destroy', $produit) }}" class="mt-6" onsubmit="return confirm('Supprimer définitivement ce produit et son historique ?')">@csrf @method('DELETE')<button class="btn-danger btn-sm">🗑 Supprimer ce produit</button></form>
@endif
@endsection
