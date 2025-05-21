<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function show()
    {
        $kennung = session('kennung');
        $rolle = session('rolle');

        if (!$kennung || !$rolle) {
            return redirect('/login')->with('error', 'Nicht authentifiziert');
        }

        $profil = $this->ladeProfil($kennung, $rolle);

        return view('profil', [
            'rolle' => $rolle,
            'profil' => $profil
        ]);
    }

    public function bearbeiten()
    {
        $kennung = session('kennung');
        $rolle = session('rolle');

        if (!$kennung || !$rolle) {
            return redirect('/login')->with('error', 'Nicht authentifiziert');
        }

        $profil = $this->ladeProfil($kennung, $rolle);

        return view('profil_bearbeiten', [
            'rolle' => $rolle,
            'profil' => $profil
        ]);
    }

    public function speichern(Request $request)
    {
        $kennung = session('kennung');
        $rolle = session('rolle');

        if (!$kennung || !$rolle) {
            return redirect('/login')->with('error', 'Nicht authentifiziert');
        }

        // Bild-Upload
        $avatarName = null;
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');

            $request->validate([
                'avatar' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            ]);

            $avatarName = $kennung . '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('images'), $avatarName);
        }

        // Grunddaten
        $daten = [
            'name' => $request->input('name'),
            'email' => $request->input('email')
        ];

        $alleFelderAusgefuellt = !empty($daten['name']) && !empty($daten['email']);

        if ($rolle === 'Student') {
            $daten['studiengang'] = $request->input('studiengang');
            $daten['semester'] = $request->input('semester');

            $alleFelderAusgefuellt = $alleFelderAusgefuellt &&
                !empty($daten['studiengang']) &&
                !empty($daten['semester']);
        } elseif ($rolle === 'Prof') {
            $daten['buero'] = $request->input('buero');

            // labore (Textarea) → Array umwandeln (pro Zeile)
            $laboreInput = $request->input('labore');
            $laboreArray = array_filter(array_map('trim', explode("\n", $laboreInput)));
            $daten['labore'] = $laboreArray;

            $alleFelderAusgefuellt = $alleFelderAusgefuellt &&
                !empty($daten['buero']) &&
                !empty($laboreArray);
        }

        // Avatar hinzufügen
        if ($avatarName) {
            $daten['avatar'] = $avatarName;
        }

        // Speichern als JSON
        $profilPfad = storage_path("app/profile/profil_{$kennung}.json");
        file_put_contents($profilPfad, json_encode([
            'profil' => $daten,
            'fertig' => $alleFelderAusgefuellt
        ], JSON_PRETTY_PRINT));

        return redirect('/profil')->with('success', 'Profil erfolgreich aktualisiert!');
    }

    private function ladeProfil($kennung, $rolle)
    {
        $profilPfad = storage_path("app/profile/profil_{$kennung}.json");

        if (!file_exists($profilPfad)) {
            // Leeres Profil anlegen
            $leeresProfil = $rolle === 'Prof'
                ? ['name' => '', 'email' => '', 'buero' => '', 'labore' => []]
                : ['name' => '', 'email' => '', 'studiengang' => '', 'semester' => ''];

            file_put_contents($profilPfad, json_encode(['profil' => $leeresProfil], JSON_PRETTY_PRINT));
        }

        $profilJson = file_get_contents($profilPfad);
        $profilData = json_decode($profilJson, true);

        $profil = $profilData['profil'];

        // Alt-Daten: labore (falls noch als String gespeichert)
        if ($rolle === 'Prof' && isset($profil['labore']) && !is_array($profil['labore'])) {
            $profil['labore'] = array_filter(array_map('trim', explode("\n", $profil['labore'])));
        }

        return $profil;
    }
}
