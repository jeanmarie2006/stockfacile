<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('titre', 'Tableau de bord') — StockFacile</title>
  <meta name="theme-color" content="#0369a1">
  <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body>
@php
  $u = auth()->user();
  $nav = [
    ['dashboard', '📊', 'Tableau de bord', null],
    ['produits.index', '📦', 'Produits', null],
    ['mouvements.index', '🔁', 'Mouvements', null],
    ['commandes.index', '🧾', 'Commandes', 'gerant'],
    ['fournisseurs.index', '🚚', 'Fournisseurs', 'gerant'],
    ['categories.index', '🏷️', 'Catégories', 'gerant'],
    ['rapports', '📈', 'Rapports', 'gerant'],
    ['utilisateurs.index', '👥', 'Utilisateurs', 'gerant'],
  ];
@endphp
<div class="flex min-h-screen">
  <aside id="menu" class="no-print fixed inset-y-0 left-0 z-40 hidden w-64 shrink-0 flex-col border-r border-slate-200 bg-white p-4 md:static md:flex">
    <a href="{{ route('dashboard') }}" class="mb-6 flex items-center gap-2.5 px-2 text-lg font-extrabold tracking-tight">
      <img src="{{ asset('icon-192.png') }}" alt="" class="h-9 w-9 rounded-xl"> <span>Stock<span class="text-brand-700">Facile</span></span>
    </a>
    <nav class="flex-1 space-y-1" aria-label="Navigation principale">
      @foreach($nav as [$route, $icon, $label, $role])
        @if(!$role || $u->role === $role)
          <a href="{{ route($route) }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition {{ request()->routeIs(explode('.', $route)[0].'*') ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-100' }}"><span aria-hidden="true">{{ $icon }}</span>{{ $label }}</a>
        @endif
      @endforeach
    </nav>
    <div class="mt-4 rounded-xl bg-slate-50 p-3 text-sm">
      <b class="block truncate">{{ $u->name }}</b>
      <span class="badge {{ $u->estGerant() ? 'bg-brand-100 text-brand-700' : 'bg-amber-100 text-amber-700' }}">{{ $u->estGerant() ? 'Gérant' : 'Vendeur' }}</span>
      <form method="post" action="{{ route('logout') }}" class="mt-2">@csrf<button class="btn-ghost btn-sm w-full">Se déconnecter</button></form>
    </div>
    <a href="{{ route('installer') }}" class="mt-3 px-2 text-xs font-semibold text-brand-700 hover:underline">⬇ Installer l’application</a>
  </aside>

  <div class="flex min-w-0 flex-1 flex-col">
    <header class="no-print sticky top-0 z-30 flex items-center gap-3 border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur md:hidden">
      <button type="button" id="burger" class="btn-ghost btn-sm" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
      <b class="text-lg">Stock<span class="text-brand-700">Facile</span></b>
      <a href="{{ route('mouvements.create') }}" class="btn-primary btn-sm ml-auto">＋ Mouvement</a>
    </header>
    <main class="mx-auto w-full max-w-6xl flex-1 px-4 py-6 md:px-8">
      @if(session('ok'))<div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">✓ {{ session('ok') }}</div>@endif
      @if($errors->any() && !isset($hideErrors))<div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-800">@foreach($errors->all() as $e)<p>⚠ {{ $e }}</p>@endforeach</div>@endif
      @yield('contenu')
    </main>
  </div>
</div>
<script>
  const b = document.getElementById('burger'), m = document.getElementById('menu');
  if (b) b.addEventListener('click', () => { const o = m.classList.toggle('hidden') === false; m.classList.toggle('flex', o); b.setAttribute('aria-expanded', o) });
  if ('serviceWorker' in navigator && (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});
</script>
</body>
</html>
