<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private const DEMOS = [
        'gerant' => ['gerant@stockfacile.bj', 'demo1234'],
        'vendeur' => ['vendeur@stockfacile.bj', 'demo1234'],
    ];

    public function form()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Boutons « démo » : identifiants publics du jeu de données de démonstration
        if ($demo = self::DEMOS[$request->input('demo')] ?? null) {
            $request->merge(['email' => $demo[0], 'password' => $demo[1]]);
        }
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        if (! Auth::attempt(['email' => strtolower($credentials['email']), 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withInput($request->only('email'))->withErrors(['email' => 'E-mail ou mot de passe incorrect.']);
        }
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('accueil');
    }
}
