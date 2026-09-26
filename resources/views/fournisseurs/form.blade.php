@extends('layouts.app')
@section('titre', 'Fournisseur')
@section('contenu')
@php $hideErrors = true; $edit = $fournisseur->exists; @endphp
<a href="{{ route('fournisseurs.index') }}" class="text-sm font-semibold text-slate-500 hover:text-brand-700">← Fournisseurs</a>
<h1 class="mb-5 mt-2 text-2xl font-extrabold text-slate-900">{{ $edit ? 'Modifier le fournisseur' : 'Nouveau fournisseur' }}</h1>
<form method="post" action="{{ $edit ? route('fournisseurs.update', $fournisseur) : route('fournisseurs.store') }}" class="card grid max-w-2xl gap-4 p-6 sm:grid-cols-2">
  @csrf @if($edit) @method('PUT') @endif
  @foreach([['nom','Nom','text',true],['telephone','Téléphone','text',false],['email','E-mail','email',false],['adresse','Adresse','text',false]] as [$n,$l,$t,$r])
    <div class="{{ $n === 'nom' || $n === 'adresse' ? 'sm:col-span-2' : '' }}"><label class="label" for="{{ $n }}">{{ $l }}</label><input id="{{ $n }}" name="{{ $n }}" type="{{ $t }}" class="input" value="{{ old($n, $fournisseur->$n) }}" @required($r)>@error($n)<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror</div>
  @endforeach
  <div class="flex gap-2 sm:col-span-2"><button class="btn-primary">Enregistrer</button><a href="{{ route('fournisseurs.index') }}" class="btn-ghost">Annuler</a></div>
</form>
@endsection
