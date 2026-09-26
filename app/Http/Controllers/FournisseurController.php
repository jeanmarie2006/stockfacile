<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    private function rules(): array
    {
        return ['nom' => ['required', 'string', 'max:100'], 'telephone' => ['nullable', 'string', 'max:30'], 'email' => ['nullable', 'email', 'max:120'], 'adresse' => ['nullable', 'string', 'max:160']];
    }

    public function index()
    {
        return view('fournisseurs.index', ['fournisseurs' => Fournisseur::withCount('produits')->orderBy('nom')->get()]);
    }

    public function create()
    {
        return view('fournisseurs.form', ['fournisseur' => new Fournisseur]);
    }

    public function store(Request $request)
    {
        Fournisseur::create($request->validate($this->rules()));

        return redirect()->route('fournisseurs.index')->with('ok', 'Fournisseur ajouté.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.form', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $fournisseur->update($request->validate($this->rules()));

        return redirect()->route('fournisseurs.index')->with('ok', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return back()->with('ok', 'Fournisseur supprimé.');
    }
}
