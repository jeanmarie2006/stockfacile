<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::orderBy('role')->orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:100'],
            'role' => ['required', Rule::in(['gerant', 'vendeur'])],
        ]);
        User::create([...$data, 'email' => strtolower($data['email'])]);

        return back()->with('ok', 'Utilisateur créé.');
    }

    public function destroy(Request $request, User $utilisateur)
    {
        abort_if($utilisateur->id === $request->user()->id, 422, 'Vous ne pouvez pas supprimer votre propre compte.');
        abort_if($utilisateur->estGerant() && User::where('role', 'gerant')->count() <= 1, 422, 'Il doit rester au moins un gérant.');
        $utilisateur->delete();

        return back()->with('ok', 'Utilisateur supprimé.');
    }
}
