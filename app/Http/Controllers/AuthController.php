<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $kennung = trim($request->input('kennung'));
        $password = trim($request->input('password'));

        $path = storage_path('profile/users.json');
        if (file_exists($path)) {
            $usersJson = file_get_contents($path);
            $users = json_decode($usersJson, true);

            if (is_array($users)) {
                foreach ($users as $user) {
                    if (
                        $user['kennung'] === $kennung &&
                        $user['password'] === $password
                    ) {
                        session([
                            'kennung' => $kennung,
                            'rolle' => $user['rolle'] ?? 'Student'  // Fallback, falls nicht gesetzt
                        ]);

                        // Unterschiedliche Weiterleitung je nach Rolle
                        if ($user['rolle'] === 'Prof') {
                            return redirect('/home');  // Professoren-Startseite
                        } else {
                            return redirect('/home');       // Studenten-Startseite
                        }
                    }
                }
            }
        }

        return back()->with('error', 'LoginDatenfehler');
    }

    // Logout Funktion
    public function logout(Request $request)
    {
        // Session löschen
        $request->session()->flush();
        return redirect('/login')->with('success', 'Erfolgreich abgemeldet!');
    }
}
