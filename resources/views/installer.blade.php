<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Installer StockFacile</title>
  <meta name="theme-color" content="#0369a1">
  <link rel="icon" type="image/svg+xml" href="{{ asset('icon.svg') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
</head>
<body class="bg-gradient-to-b from-brand-50 via-white to-slate-50">
<header class="mx-auto flex max-w-5xl items-center justify-between px-5 py-5">
  <a href="{{ route('accueil') }}" class="flex items-center gap-2.5 text-lg font-extrabold"><img src="{{ asset('icon-192.png') }}" alt="" class="h-9 w-9 rounded-xl">Stock<span class="text-brand-700">Facile</span></a>
  <a href="{{ route('accueil') }}" class="btn-ghost">← Retour</a>
</header>
<main class="mx-auto max-w-5xl px-5 pb-20">
  <section class="grid items-center gap-8 pb-10 pt-4 md:grid-cols-[1fr_auto]">
    <div>
      <img src="{{ asset('icon-192.png') }}" alt="" width="84" height="84" class="mb-5 rounded-[22px] shadow-xl shadow-slate-900/15">
      <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Installer StockFacile</h1>
      <p class="mt-3 max-w-xl text-lg text-slate-600">Installez gratuitement l’application sur votre téléphone, votre tablette ou votre ordinateur : elle s’ouvre en plein écran comme une vraie application et reste consultable même sans connexion après la première ouverture.</p>
      <div class="mt-6 flex flex-wrap items-center gap-3">
        <button id="install" class="btn-primary hidden px-7 py-3 text-base">⬇ Installer maintenant</button>
        <p id="msg" class="rounded-xl bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">Si le bouton n’apparaît pas, suivez les étapes ci-dessous pour votre appareil.</p>
      </div>
    </div>
    <figure id="qr" class="mx-auto hidden text-center md:block">
      <div class="w-44 rounded-2xl bg-white p-3 shadow-xl ring-1 ring-slate-200">{!! $qr ?? '' !!}</div>
      <figcaption class="mt-2 text-xs font-semibold text-slate-500">Scannez avec votre téléphone<br>pour l’installer</figcaption>
    </figure>
  </section>
  <section class="grid gap-5 md:grid-cols-3">
    @foreach([
      ['📱','Android (téléphone ou tablette)',['Ouvrez ce site avec <b>Chrome</b> (ou Edge, Samsung Internet).','Touchez <b>Installer</b> ci-dessus, ou le menu <b>⋮</b> puis <b>Installer l’application</b>.','Validez : l’icône apparaît sur l’écran d’accueil.']],
      ['🍎','iPhone et iPad',['Ouvrez ce site avec <b>Safari</b>.','Touchez <b>Partager</b> (carré avec une flèche vers le haut).','Choisissez <b>Sur l’écran d’accueil</b>, puis <b>Ajouter</b>.']],
      ['💻','Ordinateur',['Ouvrez ce site avec <b>Chrome</b> ou <b>Edge</b>.','Cliquez sur <b>Installer</b> ci-dessus ou sur l’icône ⊕ de la barre d’adresse.','Confirmez : l’application s’ouvre dans sa propre fenêtre.']],
    ] as [$i,$t,$steps])
      <article class="card p-6"><div class="mb-3 flex items-center gap-3"><span class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-2xl">{{ $i }}</span><h2 class="font-extrabold leading-tight text-slate-900">{{ $t }}</h2></div>
        <ol class="space-y-2.5 text-sm text-slate-600">@foreach($steps as $n => $s)<li class="flex gap-3"><span class="mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-slate-100 text-[11px] font-bold">{{ $n + 1 }}</span><span>{!! $s !!}</span></li>@endforeach</ol></article>
    @endforeach
  </section>
</main>
<script>
  let ev = null; const b = document.getElementById('install'), m = document.getElementById('msg');
  addEventListener('beforeinstallprompt', (e) => { e.preventDefault(); ev = e; b.classList.remove('hidden'); m.classList.add('hidden'); });
  b.addEventListener('click', async () => { if (!ev) return; ev.prompt(); await ev.userChoice; ev = null; b.classList.add('hidden'); });
  if (matchMedia('(display-mode: standalone)').matches) { m.textContent = '✓ L’application est déjà installée sur cet appareil.'; }
  if ('serviceWorker' in navigator && (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});
  document.getElementById('qr').classList.remove('hidden');
</script>
</body>
</html>
