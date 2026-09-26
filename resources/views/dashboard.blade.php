@extends('layouts.app')
@section('titre', 'Tableau de bord')
@section('contenu')
@php $max = max(1, $serie->max(fn($s) => max($s['entree'], $s['sortie']))); @endphp
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
  <div><h1 class="text-2xl font-extrabold text-slate-900">Bonjour {{ explode(' ', auth()->user()->name)[0] }} 👋</h1><p class="text-sm text-slate-500">Voici l’état de votre stock aujourd’hui.</p></div>
  <div class="flex gap-2"><a href="{{ route('mouvements.create', ['type' => 'sortie']) }}" class="btn-primary">− Sortie</a><a href="{{ route('mouvements.create', ['type' => 'entree']) }}" class="btn-ghost">＋ Entrée</a></div>
</div>

<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
  <div class="card p-5"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Produits</p><p class="mt-1 text-3xl font-extrabold text-slate-900">{{ $nbProduits }}</p></div>
  @if(auth()->user()->estGerant())
  <div class="card bg-gradient-to-br from-brand-700 to-brand-600 p-5 text-white !border-transparent"><p class="text-xs font-bold uppercase tracking-wide text-brand-100">Valeur du stock (achat)</p><p class="mt-1 text-2xl font-extrabold">@fcfa($valeur)</p><p class="text-xs text-brand-100">Valeur de vente : @fcfa($valeurVente)</p></div>
  @endif
  <a href="{{ route('produits.index', ['etat' => 'alerte']) }}" class="card p-5 transition hover:shadow-md"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">Sous le seuil</p><p class="mt-1 text-3xl font-extrabold text-amber-500">{{ $alertes->count() }}</p></a>
  <a href="{{ route('produits.index', ['etat' => 'rupture']) }}" class="card p-5 transition hover:shadow-md"><p class="text-xs font-bold uppercase tracking-wide text-slate-400">En rupture</p><p class="mt-1 text-3xl font-extrabold text-rose-600">{{ $ruptures->count() }}</p></a>
</div>

@if($ruptures->count() || $alertes->count())
<section class="card mt-6 border-amber-200 p-5" aria-labelledby="al-t">
  <div class="mb-3 flex items-center justify-between"><h2 id="al-t" class="font-extrabold text-slate-900">🔔 Alertes de stock</h2>@if(auth()->user()->estGerant())<form method="post" action="{{ route('commandes.generer') }}">@csrf<button class="btn-primary btn-sm">Générer les bons de commande</button></form>@endif</div>
  <ul class="grid gap-2 md:grid-cols-2">
    @foreach($ruptures as $p)<li class="flex items-center justify-between rounded-xl bg-rose-50 px-4 py-2.5 text-sm"><a href="{{ route('produits.show', $p) }}" class="font-semibold text-slate-800 hover:underline">{{ $p->nom }}</a><span class="badge bg-rose-100 text-rose-700">Rupture</span></li>@endforeach
    @foreach($alertes as $p)<li class="flex items-center justify-between rounded-xl bg-amber-50 px-4 py-2.5 text-sm"><a href="{{ route('produits.show', $p) }}" class="font-semibold text-slate-800 hover:underline">{{ $p->nom }}</a><span class="badge bg-amber-100 text-amber-700">{{ $p->quantite }} / seuil {{ $p->seuil_alerte }}</span></li>@endforeach
  </ul>
</section>
@endif

<div class="mt-6 grid gap-6 lg:grid-cols-3">
  <section class="card p-5 lg:col-span-2" aria-labelledby="mv-t">
    <div class="flex items-center justify-between"><h2 id="mv-t" class="font-extrabold text-slate-900">Mouvements des 14 derniers jours</h2>
      <span class="flex gap-3 text-xs font-semibold text-slate-500"><span><i class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-emerald-500"></i>Entrées</span><span><i class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-brand-700"></i>Sorties</span></span></div>
    <div class="mt-5 flex h-44 items-end gap-1.5" role="img" aria-label="Histogramme des quantités entrées et sorties par jour">
      @foreach($serie as $s)
        <div class="flex h-full flex-1 flex-col justify-end gap-0.5" title="{{ \App\Support\Fmt::date($s['jour'], 'd M') }} : {{ $s['entree'] }} entrées, {{ $s['sortie'] }} sorties">
          <i class="block rounded-t bg-emerald-500" style="height: {{ $s['entree'] / $max * 45 }}%"></i>
          <i class="block rounded-t bg-brand-700" style="height: {{ $s['sortie'] / $max * 55 }}%"></i>
        </div>
      @endforeach
    </div>
    <div class="mt-1 flex gap-1.5 text-[10px] text-slate-400">@foreach($serie as $s)<span class="flex-1 text-center">{{ \Carbon\Carbon::parse($s['jour'])->format('d') }}</span>@endforeach</div>
  </section>
  <section class="card p-5" aria-labelledby="tp-t">
    <h2 id="tp-t" class="mb-3 font-extrabold text-slate-900">🏆 Meilleures ventes (30 j)</h2>
    <ol class="space-y-3">@forelse($top as $i => $t)<li class="flex items-center gap-3 text-sm"><span class="grid h-6 w-6 place-items-center rounded-full bg-brand-50 text-xs font-bold text-brand-700">{{ $i + 1 }}</span><span class="flex-1 truncate font-semibold">{{ $t->produit->nom }}</span><b>{{ $t->total }}</b></li>@empty<li class="text-sm text-slate-500">Aucune vente.</li>@endforelse</ol>
  </section>
</div>

<section class="card mt-6 overflow-hidden" aria-labelledby="rc-t">
  <div class="flex items-center justify-between p-5 pb-3"><h2 id="rc-t" class="font-extrabold text-slate-900">Derniers mouvements</h2><a href="{{ route('mouvements.index') }}" class="text-sm font-bold text-brand-700 hover:underline">Tout voir →</a></div>
  <div class="overflow-x-auto"><table class="w-full"><tbody class="divide-y divide-slate-100">
    @foreach($recents as $m)
      <tr><td class="td"><span class="badge {{ $m->type === 'entree' ? 'bg-emerald-50 text-emerald-700' : 'bg-brand-50 text-brand-700' }}">{{ $m->type === 'entree' ? '＋ Entrée' : '− Sortie' }}</span></td>
        <td class="td font-semibold">{{ $m->produit->nom }}</td><td class="td">{{ $m->quantite }} {{ $m->produit->unite }}</td><td class="td text-slate-500">{{ $m->motif_label }}</td><td class="td text-slate-500">{{ $m->user?->name }}</td><td class="td text-right text-slate-400">@datefr($m->created_at, 'd M, H:i')</td></tr>
    @endforeach
  </tbody></table></div>
</section>
@endsection
