<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StockFacile — Gestion de stock pour petite boutique</title>
  <meta name="description" content="StockFacile : suivez vos produits, vos entrées et sorties et recevez des alertes de rupture. Pour boutiques, quincailleries et épiceries.">
  <meta name="theme-color" content="#0369a1">
  <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="bg-gradient-to-b from-brand-50 via-white to-slate-50">
<header class="mx-auto flex max-w-6xl items-center justify-between px-5 py-5">
  <a href="{{ route('accueil') }}" class="flex items-center gap-2.5 text-xl font-extrabold"><img src="{{ asset('icon-192.png') }}" alt="" class="h-10 w-10 rounded-xl">Stock<span class="text-brand-700">Facile</span></a>
  <nav class="flex gap-2"><a href="{{ route('installer') }}" class="btn-ghost hidden sm:inline-flex">⬇ Installer</a><a href="{{ route('login') }}" class="btn-primary">Se connecter</a></nav>
</header>
<main>
  <section class="mx-auto grid max-w-6xl items-center gap-12 px-5 pb-16 pt-8 lg:grid-cols-2 lg:pt-14">
    <div>
      <p class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand-100 bg-white px-3 py-1 text-xs font-semibold text-brand-700 shadow-sm"><span class="h-2 w-2 rounded-full bg-brand-500"></span> Boutiques · Quincailleries · Épiceries</p>
      <h1 class="text-4xl font-extrabold leading-[1.08] tracking-tight text-slate-900 sm:text-5xl">Ne tombez plus jamais <span class="text-brand-700">en rupture de stock.</span></h1>
      <p class="mt-5 max-w-xl text-lg text-slate-600">StockFacile vous montre en un coup d’œil ce qu’il reste, ce qui manque et ce qu’il faut commander. Simple pour le gérant, rapide pour le vendeur.</p>
      <div class="mt-8 flex flex-wrap gap-3">
        <form method="post" action="{{ url('/connexion') }}">@csrf<button name="demo" value="gerant" class="btn-primary px-6 py-3 text-base">Essayer la démo →</button></form>
        <a href="{{ route('login') }}" class="btn-ghost px-6 py-3 text-base">Connexion</a>
      </div>
    </div>
    <div class="card overflow-hidden p-5 shadow-xl shadow-slate-900/5" aria-hidden="true">
      <div class="mb-4 grid grid-cols-3 gap-3 text-center">
        <div class="rounded-xl bg-brand-50 p-3"><p class="text-xs text-brand-700">Valeur du stock</p><p class="font-extrabold">2 486 500 F</p></div>
        <div class="rounded-xl bg-amber-50 p-3"><p class="text-xs text-amber-700">Sous le seuil</p><p class="font-extrabold">4 produits</p></div>
        <div class="rounded-xl bg-rose-50 p-3"><p class="text-xs text-rose-700">En rupture</p><p class="font-extrabold">1 produit</p></div>
      </div>
      <div class="flex h-28 items-end gap-1.5">@foreach([40,62,35,80,55,90,48,70,30,85,60,45,75,66] as $h)<i class="flex-1 rounded-t bg-gradient-to-t from-brand-700 to-brand-500" style="height: {{ $h }}%"></i>@endforeach</div>
      <ul class="mt-4 space-y-2 text-sm">
        <li class="flex justify-between rounded-lg bg-rose-50 px-3 py-2"><span>Cadenas 40 mm</span><b class="text-rose-700">Rupture</b></li>
        <li class="flex justify-between rounded-lg bg-amber-50 px-3 py-2"><span>Ciment 50 kg</span><b class="text-amber-700">6 restants</b></li>
        <li class="flex justify-between rounded-lg bg-slate-50 px-3 py-2"><span>Riz parfumé 25 kg</span><b>5 restants</b></li>
      </ul>
    </div>
  </section>
  <section class="mx-auto grid max-w-6xl gap-5 px-5 pb-20 sm:grid-cols-2 lg:grid-cols-3">
    @foreach([['📦','Produits et catégories','Nom, code-barres, prix, quantité et seuil d’alerte pour chaque article.'],['🔁','Entrées et sorties','Chaque mouvement est daté, expliqué (vente, casse, achat…) et attribué à un utilisateur.'],['🔔','Alertes automatiques','Les produits sous le seuil ou en rupture apparaissent en rouge et en orange dès la connexion.'],['🧾','Bons de commande','Générés automatiquement pour les produits à réapprovisionner, en PDF, avec réception qui met le stock à jour.'],['👥','Gérant et vendeur','Le vendeur enregistre les mouvements ; seul le gérant modifie les prix, les produits et voit les rapports.'],['📈','Rapports mensuels','Chiffre d’affaires, marge et top ventes, exportables en PDF ou en Excel (CSV).']] as [$i,$t,$d])
      <article class="card p-6"><div class="mb-3 grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-xl">{{ $i }}</div><h3 class="font-bold text-slate-900">{{ $t }}</h3><p class="mt-1 text-sm text-slate-600">{{ $d }}</p></article>
    @endforeach
  </section>
</main>
<footer class="border-t border-slate-200 bg-white py-8 text-center text-sm text-slate-500">Projet de démonstration — boutique fictive · <a href="{{ route('installer') }}" class="font-semibold text-brand-700 hover:underline">Installer l’application</a> · Réalisé par <a class="font-semibold text-brand-700 hover:underline" href="https://sedjame-vianney.vercel.app" target="_blank" rel="noopener">Sedjame Vianney</a></footer>
<script>if ('serviceWorker' in navigator && (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});</script>
</body>
</html>
