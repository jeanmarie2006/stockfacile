@extends('layouts.app')
@section('titre', 'Utilisateurs')
@section('contenu')
@php $hideErrors = true; @endphp
<h1 class="mb-5 text-2xl font-extrabold text-slate-900">Utilisateurs</h1>
@if($errors->any())<div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">@foreach($errors->all() as $e)<p>⚠ {{ $e }}</p>@endforeach</div>@endif
<div class="grid gap-6 lg:grid-cols-[1fr_360px]">
  <div class="card overflow-hidden"><table class="w-full"><thead class="bg-slate-50"><tr><th class="th">Nom</th><th class="th">E-mail</th><th class="th">Rôle</th><th class="th"></th></tr></thead><tbody class="divide-y divide-slate-100">
  @foreach($users as $u)<tr><td class="td font-semibold">{{ $u->name }}</td><td class="td text-slate-500">{{ $u->email }}</td><td class="td"><span class="badge {{ $u->estGerant() ? 'bg-brand-100 text-brand-700' : 'bg-amber-100 text-amber-700' }}">{{ $u->estGerant() ? 'Gérant' : 'Vendeur' }}</span></td>
    <td class="td text-right">@if($u->id !== auth()->id())<form method="post" action="{{ route('utilisateurs.destroy', $u) }}" onsubmit="return confirm('Supprimer cet utilisateur ?')">@csrf @method('DELETE')<button class="btn-ghost btn-sm text-rose-600" aria-label="Supprimer {{ $u->name }}">🗑</button></form>@endif</td></tr>@endforeach</tbody></table></div>
  <form method="post" action="{{ route('utilisateurs.store') }}" class="card space-y-3 self-start p-5">@csrf<h2 class="font-extrabold text-slate-900">Ajouter un utilisateur</h2>
    <div><label class="label" for="name">Nom</label><input id="name" name="name" class="input" value="{{ old('name') }}" required></div>
    <div><label class="label" for="email">E-mail</label><input id="email" name="email" type="email" class="input" value="{{ old('email') }}" required></div>
    <div><label class="label" for="password">Mot de passe</label><input id="password" name="password" type="password" class="input" minlength="8" required></div>
    <div><label class="label" for="role">Rôle</label><select id="role" name="role" class="input"><option value="vendeur">Vendeur (mouvements uniquement)</option><option value="gerant">Gérant (tous les droits)</option></select></div>
    <button class="btn-primary w-full">Créer</button></form>
</div>
@endsection
