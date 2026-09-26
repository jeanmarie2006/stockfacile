<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 60)->unique();
            $table->timestamps();
        });

        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('telephone', 30)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('adresse', 160)->nullable();
            $table->timestamps();
        });

        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 120);
            $table->string('code', 40)->nullable()->unique();            // code interne ou code-barres / QR
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->nullOnDelete();
            $table->unsignedInteger('prix_achat')->default(0);           // FCFA
            $table->unsignedInteger('prix')->default(0);                 // prix de vente, FCFA
            $table->integer('quantite')->default(0);
            $table->unsignedInteger('seuil_alerte')->default(5);
            $table->string('unite', 20)->default('pièce');
            $table->timestamps();
            $table->index('nom');
        });

        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->string('type', 10)->index();                         // entree | sortie
            $table->unsignedInteger('quantite');
            $table->string('motif', 40);
            $table->string('note', 160)->nullable();
            $table->integer('stock_apres');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero', 20)->unique();
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->nullOnDelete();
            $table->string('statut', 12)->default('brouillon');          // brouillon | envoyee | recue
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('commande_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')->constrained('commandes')->cascadeOnDelete();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            $table->unsignedInteger('quantite');
            $table->unsignedInteger('prix_unitaire')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['commande_lignes', 'commandes', 'mouvements', 'produits', 'fournisseurs', 'categories'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
