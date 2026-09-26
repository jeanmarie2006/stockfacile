<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion — StockFacile</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="grid min-h-screen place-items-center bg-gradient-to-b from-brand-50 to-slate-50 px-4 py-10">
<div class="w-full max-w-md">
  <a href="{{ route('accueil') }}" class="mb-6 flex items-center justify-center gap-2.5 text-xl font-extrabold"><img src="{{ asset('icon-192.png') }}" alt="" class="h-10 w-10 rounded-xl">Stock<span class="text-brand-700">Facile</span></a>
  <form method="post" action="{{ url('/connexion') }}" class="card space-y-4 p-7">
    @csrf
    <div><h1 class="text-2xl font-extrabold text-slate-900">Connexion</h1><p class="text-sm text-slate-500">Accédez à la gestion de votre boutique.</p></div>
    @if($errors->any())<div role="alert" class="rounded-xl bg-rose-50 px-3.5 py-2.5 text-sm font-medium text-rose-700">{{ $errors->first() }}</div>@endif
    <div><label class="label" for="email">E-mail</label><input id="email" name="email" type="email" class="input" value="{{ old('email') }}" autocomplete="email" required></div>
    <div><label class="label" for="password">Mot de passe</label><input id="password" name="password" type="password" class="input" autocomplete="current-password" required></div>
    <button class="btn-primary w-full">Se connecter</button>
    <div class="grid gap-2 sm:grid-cols-2">
      <button name="demo" value="gerant" class="btn-ghost text-xs" formnovalidate>Démo : gérant</button>
      <button name="demo" value="vendeur" class="btn-ghost text-xs" formnovalidate>Démo : vendeur</button>
    </div>
  </form>
</div>
</body>
</html>
