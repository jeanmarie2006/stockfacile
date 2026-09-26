<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        return view('categories.index', ['categories' => Categorie::withCount('produits')->orderBy('nom')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['nom' => ['required', 'string', 'max:60', 'unique:categories,nom']]);
        Categorie::create($data);

        return back()->with('ok', 'Catégorie ajoutée.');
    }

    public function destroy(Categorie $category)
    {
        $category->delete();

        return back()->with('ok', 'Catégorie supprimée (ses produits sont conservés, sans catégorie).');
    }
}
