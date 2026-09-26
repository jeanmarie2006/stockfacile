<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\Route;

Route::view('/installer', 'installer')->name('installer');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'form'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login'])->middleware('throttle:8,1');
});
Route::post('/deconnexion', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/', fn () => auth()->check() ? redirect()->route('dashboard') : view('accueil'))->name('accueil');

Route::middleware('auth')->group(function () {
    Route::get('/tableau-de-bord', DashboardController::class)->name('dashboard');

    // Produits : tout le monde consulte ; seul le gérant modifie et supprime
    Route::get('/produits', [ProduitController::class, 'index'])->name('produits.index');
    Route::middleware('role:gerant')->group(function () {
        Route::get('/produits/nouveau', [ProduitController::class, 'create'])->name('produits.create');
        Route::post('/produits', [ProduitController::class, 'store'])->name('produits.store');
        Route::get('/produits/{produit}/modifier', [ProduitController::class, 'edit'])->name('produits.edit');
        Route::put('/produits/{produit}', [ProduitController::class, 'update'])->name('produits.update');
        Route::delete('/produits/{produit}', [ProduitController::class, 'destroy'])->name('produits.destroy');
    });
    Route::get('/produits/{produit}', [ProduitController::class, 'show'])->name('produits.show')->where('produit', '[0-9]+');

    // Mouvements : gérant et vendeur
    Route::get('/mouvements', [MouvementController::class, 'index'])->name('mouvements.index');
    Route::get('/mouvements/nouveau', [MouvementController::class, 'create'])->name('mouvements.create');
    Route::post('/mouvements', [MouvementController::class, 'store'])->name('mouvements.store');
    Route::get('/recherche-code', [MouvementController::class, 'parCode'])->name('code');

    // Réservé au gérant
    Route::middleware('role:gerant')->group(function () {
        Route::resource('categories', CategorieController::class)->only(['index', 'store', 'destroy']);
        Route::resource('fournisseurs', FournisseurController::class)->except(['show']);
        Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes.index');
        Route::post('/commandes/generer', [CommandeController::class, 'generer'])->name('commandes.generer');
        Route::get('/commandes/{commande}', [CommandeController::class, 'show'])->name('commandes.show');
        Route::get('/commandes/{commande}/pdf', [CommandeController::class, 'pdf'])->name('commandes.pdf');
        Route::post('/commandes/{commande}/statut', [CommandeController::class, 'statut'])->name('commandes.statut');
        Route::delete('/commandes/{commande}', [CommandeController::class, 'destroy'])->name('commandes.destroy');
        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports');
        Route::get('/rapports/pdf', [RapportController::class, 'pdf'])->name('rapports.pdf');
        Route::get('/rapports/csv', [RapportController::class, 'csv'])->name('rapports.csv');
        Route::resource('utilisateurs', UtilisateurController::class)->only(['index', 'store', 'destroy']);
    });
});
