<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Fournisseur;
use App\Models\Mouvement;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Database\Seeder;

/** Boutique fictive « Quincaillerie & Épicerie Mawuli » à Cotonou : 30 produits et 30 jours de mouvements. */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        mt_srand(77);
        $gerant = User::create(['name' => 'Mawuli Agbo', 'email' => 'gerant@stockfacile.bj', 'password' => 'demo1234', 'role' => 'gerant']);
        $vendeur = User::create(['name' => 'Akouavi Dossou', 'email' => 'vendeur@stockfacile.bj', 'password' => 'demo1234', 'role' => 'vendeur']);

        $cat = collect(['Alimentation', 'Boissons', 'Hygiène & entretien', 'Quincaillerie', 'Peinture', 'Électricité'])->mapWithKeys(fn ($n) => [$n => Categorie::create(['nom' => $n])->id]);
        $f = [
            'sodeco' => Fournisseur::create(['nom' => 'Sodeco Distribution', 'telephone' => '+229 01 97 10 20 30', 'email' => 'commandes@sodeco.example', 'adresse' => 'Zone industrielle, Cotonou']),
            'bati' => Fournisseur::create(['nom' => 'Bâti-Matériaux Bénin', 'telephone' => '+229 01 96 11 22 33', 'email' => 'vente@batimateriaux.example', 'adresse' => 'Akpakpa, Cotonou']),
            'brasserie' => Fournisseur::create(['nom' => 'Grossiste des Boissons', 'telephone' => '+229 01 95 44 55 66', 'email' => null, 'adresse' => 'Marché Dantokpa']),
        ];

        // nom, catégorie, fournisseur, prix achat, prix vente, seuil, unité, ventes/jour moyennes
        $liste = [
            ['Riz parfumé 25 kg', 'Alimentation', 'sodeco', 15500, 18000, 8, 'sac', 1.2], ['Huile végétale 5 L', 'Alimentation', 'sodeco', 5200, 6000, 10, 'bidon', 1.6],
            ['Sucre en poudre 1 kg', 'Alimentation', 'sodeco', 700, 850, 20, 'paquet', 3], ['Pâtes spaghetti 500 g', 'Alimentation', 'sodeco', 380, 500, 30, 'paquet', 4],
            ['Tomate concentrée 400 g', 'Alimentation', 'sodeco', 480, 600, 24, 'boîte', 3.5], ['Lait en poudre 400 g', 'Alimentation', 'sodeco', 2100, 2500, 12, 'boîte', 1.5],
            ['Sardines 125 g', 'Alimentation', 'sodeco', 350, 450, 30, 'boîte', 4], ['Farine de blé 1 kg', 'Alimentation', 'sodeco', 550, 700, 15, 'paquet', 2],
            ['Eau minérale 1,5 L (pack de 6)', 'Boissons', 'brasserie', 1500, 1800, 10, 'pack', 3], ['Jus de fruit 1 L', 'Boissons', 'brasserie', 750, 1000, 15, 'bouteille', 3.5],
            ['Boisson gazeuse 33 cl (casier)', 'Boissons', 'brasserie', 4800, 5500, 6, 'casier', 1.4], ['Bière locale 65 cl (casier)', 'Boissons', 'brasserie', 6500, 7500, 6, 'casier', 1.5],
            ['Savon de Marseille', 'Hygiène & entretien', 'sodeco', 300, 400, 30, 'pièce', 3], ['Lessive en poudre 1 kg', 'Hygiène & entretien', 'sodeco', 1200, 1500, 12, 'paquet', 1.8],
            ['Eau de Javel 1 L', 'Hygiène & entretien', 'sodeco', 500, 700, 15, 'bouteille', 1.6], ['Papier toilette (lot de 4)', 'Hygiène & entretien', 'sodeco', 800, 1000, 15, 'lot', 2.2],
            ['Ciment 50 kg', 'Quincaillerie', 'bati', 4700, 5300, 20, 'sac', 2.4], ['Fer à béton 8 mm', 'Quincaillerie', 'bati', 2600, 3000, 30, 'barre', 3],
            ['Clous 4 pouces (kg)', 'Quincaillerie', 'bati', 900, 1200, 10, 'kg', 1.2], ['Cadenas 40 mm', 'Quincaillerie', 'bati', 1500, 2200, 6, 'pièce', .7],
            ['Marteau', 'Quincaillerie', 'bati', 2500, 3500, 4, 'pièce', .3], ['Tôle ondulée', 'Quincaillerie', 'bati', 3800, 4500, 25, 'feuille', 2.5],
            ['Peinture blanche 20 L', 'Peinture', 'bati', 21000, 25000, 4, 'seau', .4], ['Peinture jaune 4 L', 'Peinture', 'bati', 5200, 6500, 5, 'bidon', .5],
            ['Pinceau 5 cm', 'Peinture', 'bati', 600, 900, 10, 'pièce', .8], ['Rouleau de peinture', 'Peinture', 'bati', 1800, 2500, 5, 'pièce', .4],
            ['Ampoule LED 9 W', 'Électricité', 'bati', 700, 1000, 20, 'pièce', 2.2], ['Câble électrique 2,5 mm (rouleau)', 'Électricité', 'bati', 21000, 26000, 3, 'rouleau', .25],
            ['Prise murale double', 'Électricité', 'bati', 900, 1300, 10, 'pièce', .7], ['Multiprise 5 postes', 'Électricité', 'bati', 3200, 4200, 5, 'pièce', .5],
        ];

        $fin = ['Ciment 50 kg' => 6, 'Cadenas 40 mm' => 0, 'Eau de Javel 1 L' => 4, 'Peinture blanche 20 L' => 3, 'Sardines 125 g' => 9, 'Riz parfumé 25 kg' => 5];
        $users = [$gerant->id, $vendeur->id, $vendeur->id];
        foreach ($liste as $i => [$nom, $c, $fk, $pa, $pv, $seuil, $unite, $rythme]) {
            $p = Produit::create([
                'nom' => $nom, 'code' => '61'.str_pad((string) (2000 + $i * 7), 11, '0', STR_PAD_LEFT), 'categorie_id' => $cat[$c], 'fournisseur_id' => $f[$fk]->id,
                'prix_achat' => $pa, 'prix' => $pv, 'quantite' => 0, 'seuil_alerte' => $seuil, 'unite' => $unite,
            ]);
            $qte = (int) round($seuil * mt_rand(40, 80) / 10);
            $journal = [['entree', $qte, 'inventaire', 'Stock initial', now()->subDays(31)->setTime(8, 0)]];
            for ($j = 30; $j >= 0; $j--) {
                $d = now()->subDays($j);
                $v = (int) round($rythme * mt_rand(40, 170) / 100);
                if ($v > 0 && $qte >= $v) {
                    $qte -= $v;
                    $journal[] = ['sortie', $v, 'vente', null, $d->copy()->setTime(mt_rand(8, 19), mt_rand(0, 59))];
                }
                if ($qte <= $seuil * 1.2 && mt_rand(0, 100) > 55 && $j > 2) {
                    $r = (int) round($seuil * mt_rand(30, 60) / 10);
                    $qte += $r;
                    $journal[] = ['entree', $r, 'achat', 'Livraison fournisseur', $d->copy()->setTime(mt_rand(8, 11), mt_rand(0, 59))];
                }
                if (mt_rand(0, 100) > 96 && $qte > 2) {
                    $qte -= 1;
                    $journal[] = ['sortie', 1, 'casse', 'Article abîmé', $d->copy()->setTime(16, 30)];
                }
            }
            // scénarios de démonstration : quelques produits terminent sous le seuil ou en rupture
            if (isset($fin[$nom]) && $qte > $fin[$nom]) {
                $ecart = $qte - $fin[$nom];
                $qte = $fin[$nom];
                $journal[] = ['sortie', $ecart, 'vente', null, now()->subHours(3)];
            }
            $run = 0;
            foreach ($journal as [$type, $q, $motif, $note, $date]) {
                $run += $type === 'entree' ? $q : -$q;
                $m = Mouvement::create(['produit_id' => $p->id, 'type' => $type, 'quantite' => $q, 'motif' => $motif, 'note' => $note, 'stock_apres' => $run, 'user_id' => $users[array_rand($users)]]);
                $m->forceFill(['created_at' => $date, 'updated_at' => $date])->save();
            }
            $p->update(['quantite' => $run]);
        }
    }
}
