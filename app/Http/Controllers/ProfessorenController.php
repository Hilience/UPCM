<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfessorenController extends Controller
{
    public function profs()
    {
        $path = storage_path('app/profile/users.json');
        $usersJson = file_get_contents($path);
        $users = json_decode($usersJson, true);

        $professoren = [];

        foreach ($users as $user) {
            if ($user['rolle'] === 'Prof') {
                $profilPfad = storage_path("app/profile/profil_{$user['kennung']}.json");

                if (file_exists($profilPfad)) {
                    $profilJson = file_get_contents($profilPfad);
                    $profilData = json_decode($profilJson, true);

                    $fertig = $profilData['fertig'] ?? false;

                    // Nur hinzufügen, wenn Profil fertig ist
                    if ($fertig) {
                        $professoren[] = [
                            'kennung' => $user['kennung'],
                            'name' => $profilData['profil']['name'] ?? 'Unbekannt',
                            'email' => $profilData['profil']['email'] ?? '',
                            'buero' => $profilData['profil']['buero'] ?? '',
                            'labore' => $profilData['profil']['labore'] ?? [],
                            'avatar' => $profilData['profil']['avatar'] ?? '',
                            'fertig' => true
                        ];
                    }
                }
            }
        }

        return view('professoren', [
            'professoren' => $professoren
        ]);
    }
}
