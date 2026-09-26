<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
  h1 { font-size: 22px; margin: 0; color: #0369a1; } .muted { color: #64748b; }
  table { width: 100%; border-collapse: collapse; margin-top: 20px; } th { background: #0369a1; color: #fff; text-align: left; padding: 8px; font-size: 11px; }
  td { padding: 8px; border-bottom: 1px solid #e2e8f0; } .r { text-align: right; } .total td { font-weight: bold; font-size: 14px; border-top: 2px solid #0369a1; }
</style></head><body>
  <table style="margin:0"><tr><td style="border:0;padding:0"><h1>BON DE COMMANDE</h1><span class="muted">N° {{ $commande->numero }} · {{ $commande->created_at->format('d/m/Y') }}</span></td>
    <td style="border:0;padding:0;text-align:right"><b>Quincaillerie &amp; Épicerie Mawuli</b><br><span class="muted">Cotonou, Bénin</span></td></tr></table>
  <p style="margin-top:22px"><b>Fournisseur :</b> {{ $commande->fournisseur?->nom ?? '—' }}<br>@if($commande->fournisseur?->telephone)<span class="muted">Tél. {{ $commande->fournisseur->telephone }}</span>@endif</p>
  <table><thead><tr><th>Produit</th><th class="r">Quantité</th><th class="r">Prix unitaire</th><th class="r">Total</th></tr></thead><tbody>
  @foreach($commande->lignes as $l)<tr><td>{{ $l->produit->nom }}</td><td class="r">{{ $l->quantite }} {{ $l->produit->unite }}</td><td class="r">@fcfa($l->prix_unitaire)</td><td class="r">@fcfa($l->quantite * $l->prix_unitaire)</td></tr>@endforeach
  <tr class="total"><td colspan="3" class="r">TOTAL</td><td class="r">@fcfa($commande->total)</td></tr></tbody></table>
  <p class="muted" style="margin-top:30px">Merci de confirmer la disponibilité et le délai de livraison.</p>
</body></html>
