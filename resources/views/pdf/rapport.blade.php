<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
  h1 { font-size: 22px; margin: 0; color: #0369a1; } .muted { color: #64748b; }
  table { width: 100%; border-collapse: collapse; margin-top: 16px; } th { background: #0369a1; color: #fff; text-align: left; padding: 7px; font-size: 11px; }
  td { padding: 7px; border-bottom: 1px solid #e2e8f0; } .r { text-align: right; }
  .kpi td { border: 1px solid #e2e8f0; text-align: center; padding: 10px; } .kpi b { font-size: 15px; display: block; }
</style></head><body>
  <h1>Rapport de stock — {{ \App\Support\Fmt::date($mois.'-01', 'F Y') }}</h1><span class="muted">Quincaillerie &amp; Épicerie Mawuli · édité le {{ now()->format('d/m/Y') }}</span>
  <table class="kpi"><tr><td><span class="muted">Chiffre d’affaires</span><b>@fcfa($ca)</b></td><td><span class="muted">Marge brute</span><b>@fcfa($marge)</b></td><td><span class="muted">Valeur du stock</span><b>@fcfa($valeur)</b></td><td><span class="muted">Ruptures / bas</span><b>{{ $ruptures }} / {{ $alertes }}</b></td></tr></table>
  <table><thead><tr><th>Produit</th><th class="r">Entrées</th><th class="r">Sorties</th><th class="r">Ventes</th><th class="r">CA</th><th class="r">Stock</th></tr></thead><tbody>
  @foreach($lignes as $l)<tr><td>{{ $l->produit->nom }}</td><td class="r">{{ $l->entrees }}</td><td class="r">{{ $l->sorties }}</td><td class="r">{{ $l->ventes }}</td><td class="r">@fcfa($l->ventes * $l->produit->prix)</td><td class="r">{{ $l->produit->quantite }}</td></tr>@endforeach
  </tbody></table>
</body></html>
