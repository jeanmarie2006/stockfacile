<?php

namespace Tests\Feature;

use App\Models\Commande;
use App\Models\CommandeLigne;
use App\Models\Mouvement;
use App\Models\Produit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role): User
    {
        return User::create(['name' => ucfirst($role), 'email' => "$role@test.bj", 'password' => 'motdepasse', 'role' => $role]);
    }

    private function produit(int $qte = 10, int $seuil = 3): Produit
    {
        return Produit::create(['nom' => 'Riz 25 kg', 'quantite' => $qte, 'seuil_alerte' => $seuil, 'prix_achat' => 15000, 'prix' => 18000, 'unite' => 'sac']);
    }

    public function test_les_pages_protegees_exigent_une_connexion(): void
    {
        $this->get('/tableau-de-bord')->assertRedirect('/connexion');
        $this->get('/produits')->assertRedirect('/connexion');
    }

    public function test_connexion_et_mauvais_mot_de_passe(): void
    {
        $this->user('gerant');
        $this->post('/connexion', ['email' => 'gerant@test.bj', 'password' => 'faux'])->assertSessionHasErrors('email');
        $this->post('/connexion', ['email' => 'gerant@test.bj', 'password' => 'motdepasse'])->assertRedirect('/tableau-de-bord');
    }

    public function test_une_sortie_diminue_le_stock_et_est_historisee(): void
    {
        $vendeur = $this->user('vendeur');
        $p = $this->produit(10);
        $this->actingAs($vendeur)->post('/mouvements', ['produit_id' => $p->id, 'type' => 'sortie', 'quantite' => 4, 'motif' => 'vente'])->assertRedirect('/mouvements');

        $this->assertSame(6, $p->fresh()->quantite);
        $m = Mouvement::first();
        $this->assertSame(6, $m->stock_apres);
        $this->assertSame($vendeur->id, $m->user_id);
    }

    public function test_une_sortie_superieure_au_stock_est_refusee(): void
    {
        $p = $this->produit(2);
        $this->actingAs($this->user('vendeur'))->post('/mouvements', ['produit_id' => $p->id, 'type' => 'sortie', 'quantite' => 5, 'motif' => 'vente'])->assertSessionHasErrors('quantite');
        $this->assertSame(2, $p->fresh()->quantite);
        $this->assertSame(0, Mouvement::count());
    }

    public function test_motif_incoherent_avec_le_type_est_refuse(): void
    {
        $p = $this->produit();
        $this->actingAs($this->user('gerant'))->post('/mouvements', ['produit_id' => $p->id, 'type' => 'sortie', 'quantite' => 1, 'motif' => 'achat'])->assertSessionHasErrors('motif');
    }

    public function test_etats_du_stock(): void
    {
        $this->assertSame('ok', $this->produit(10, 3)->etat);
        $this->assertSame('alerte', Produit::create(['nom' => 'A', 'quantite' => 3, 'seuil_alerte' => 3])->etat);
        $this->assertSame('rupture', Produit::create(['nom' => 'B', 'quantite' => 0, 'seuil_alerte' => 3])->etat);
    }

    public function test_le_vendeur_ne_peut_ni_creer_de_produit_ni_voir_les_rapports(): void
    {
        $v = $this->user('vendeur');
        $this->actingAs($v)->get('/produits/nouveau')->assertForbidden();
        $this->actingAs($v)->post('/produits', ['nom' => 'X'])->assertForbidden();
        $this->actingAs($v)->get('/rapports')->assertForbidden();
        $this->actingAs($v)->get('/utilisateurs')->assertForbidden();
        $this->actingAs($v)->get('/produits')->assertOk();
    }

    public function test_le_gerant_cree_un_produit_avec_stock_initial_trace(): void
    {
        $this->actingAs($this->user('gerant'))->post('/produits', ['nom' => 'Ciment', 'prix_achat' => 4700, 'prix' => 5300, 'seuil_alerte' => 20, 'unite' => 'sac', 'quantite' => 50])->assertRedirect();
        $p = Produit::first();
        $this->assertSame(50, $p->quantite);
        $this->assertSame('Stock initial', $p->mouvements()->first()->note);
    }

    public function test_la_reception_d_une_commande_augmente_le_stock(): void
    {
        $g = $this->user('gerant');
        $p = $this->produit(1, 3);
        $c = Commande::create(['numero' => 'BC-TEST-001', 'user_id' => $g->id]);
        CommandeLigne::create(['commande_id' => $c->id, 'produit_id' => $p->id, 'quantite' => 12, 'prix_unitaire' => 15000]);

        $this->actingAs($g)->post("/commandes/{$c->id}/statut", ['statut' => 'recue'])->assertRedirect();
        $this->assertSame(13, $p->fresh()->quantite);
        $this->assertSame('recue', $c->fresh()->statut);
        $this->actingAs($g)->post("/commandes/{$c->id}/statut", ['statut' => 'recue'])->assertStatus(422);
        $this->assertSame(13, $p->fresh()->quantite);
    }

    public function test_impossible_de_supprimer_le_dernier_gerant(): void
    {
        $g = $this->user('gerant');
        $autre = User::create(['name' => 'Autre', 'email' => 'autre@test.bj', 'password' => 'motdepasse', 'role' => 'vendeur']);
        $this->actingAs($g)->delete("/utilisateurs/{$g->id}")->assertStatus(422);
        $this->actingAs($g)->delete("/utilisateurs/{$autre->id}")->assertRedirect();
    }
}
