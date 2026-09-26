@extends('layouts.app')
@section('titre', 'Nouveau mouvement')
@section('contenu')
@php $hideErrors = true; $type = old('type', $type); $sel = old('produit_id', $choisi); @endphp
<a href="{{ route('mouvements.index') }}" class="text-sm font-semibold text-slate-500 hover:text-brand-700">← Historique</a>
<h1 class="mb-5 mt-2 text-2xl font-extrabold text-slate-900">Enregistrer un mouvement</h1>

<form method="get" action="{{ route('code') }}" class="card mb-5 flex max-w-2xl flex-wrap items-end gap-3 p-4">
  <input type="hidden" name="type" value="{{ $type }}">
  <div class="min-w-48 flex-1"><label class="label" for="code">Scanner un code-barres / QR</label><input id="code" name="code" class="input" placeholder="Scannez le code ou tapez-le, puis Entrée" autocomplete="off" @if(!$sel) autofocus @endif></div>
  <button class="btn-ghost">Rechercher</button>
  @error('code')<p class="w-full text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
</form>

<form method="post" action="{{ route('mouvements.store') }}" class="card max-w-2xl space-y-5 p-6" id="form-mvt">
  @csrf
  <div class="grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1" role="radiogroup" aria-label="Type de mouvement">
    <label class="cursor-pointer"><input type="radio" name="type" value="sortie" class="peer sr-only" @checked($type === 'sortie')><span class="block rounded-lg py-2.5 text-center text-sm font-bold text-slate-500 peer-checked:bg-brand-700 peer-checked:text-white peer-checked:shadow">− Sortie</span></label>
    <label class="cursor-pointer"><input type="radio" name="type" value="entree" class="peer sr-only" @checked($type === 'entree')><span class="block rounded-lg py-2.5 text-center text-sm font-bold text-slate-500 peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:shadow">＋ Entrée</span></label>
  </div>
  <div><label class="label" for="produit_id">Produit</label>
    <select id="produit_id" name="produit_id" class="input" required>
      <option value="">Choisir un produit…</option>
      @foreach($produits as $p)<option value="{{ $p->id }}" data-stock="{{ $p->quantite }}" data-unite="{{ $p->unite }}" @selected($sel == $p->id)>{{ $p->nom }} — {{ $p->quantite }} {{ $p->unite }}</option>@endforeach
    </select>@error('produit_id')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror
    <p id="stock" class="mt-1 text-xs text-slate-500"></p></div>
  <div class="grid gap-4 sm:grid-cols-2">
    <div><label class="label" for="quantite">Quantité</label><input id="quantite" name="quantite" type="number" min="1" class="input text-lg font-bold" value="{{ old('quantite', 1) }}" required>@error('quantite')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror</div>
    <div><label class="label" for="motif">Motif</label><select id="motif" name="motif" class="input" required></select>@error('motif')<p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>@enderror</div>
  </div>
  <div><label class="label" for="note">Note (facultatif)</label><input id="note" name="note" class="input" maxlength="160" value="{{ old('note') }}" placeholder="Ex. livraison du 12/09, client X…"></div>
  <div class="flex gap-2"><button class="btn-primary">Enregistrer</button><a href="{{ route('mouvements.index') }}" class="btn-ghost">Annuler</a></div>
</form>
<script>
  const MOTIFS = @json($motifs), OLD = @json(old('motif'));
  const motif = document.getElementById('motif'), sel = document.getElementById('produit_id'), stock = document.getElementById('stock');
  function motifs() {
    const t = document.querySelector('input[name=type]:checked').value;
    motif.innerHTML = Object.entries(MOTIFS[t]).map(([k, v]) => `<option value="${k}" ${OLD === k ? 'selected' : ''}>${v}</option>`).join('');
  }
  function info() { const o = sel.selectedOptions[0]; stock.textContent = o && o.dataset.stock !== undefined ? `Stock actuel : ${o.dataset.stock} ${o.dataset.unite}` : ''; }
  document.querySelectorAll('input[name=type]').forEach(r => r.addEventListener('change', motifs)); sel.addEventListener('change', info); motifs(); info();
</script>
@endsection
