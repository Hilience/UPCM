<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function termine()
    {
        $kennung = session('kennung');

        if (!$kennung) {
            abort(403, 'Keine Benutzerkennung in der Session gefunden.');
        }

        $dateiPfad = storage_path("app/profile/profil_{$kennung}.json");

        if (!file_exists($dateiPfad)) {
            file_put_contents($dateiPfad, json_encode(['termine' => []], JSON_PRETTY_PRINT));
        }

        $json = file_get_contents($dateiPfad);
        $profil = json_decode($json, true);
        $termine = $profil['termine'] ?? [];

        return view('kalender', ['termine' => $termine]);
    }

    public function speichereTermine(Request $request)
    {
        $kennung = session('kennung');
        if (!$kennung) return response()->json(['error' => 'Keine Benutzerkennung'], 403);

        $termine = $request->input('termine');
        if (!is_array($termine)) return response()->json(['error' => 'Ungültige Termindaten'], 400);

        $dateiPfad = storage_path("app/profile/profil_{$kennung}.json");
        file_put_contents($dateiPfad, json_encode(['termine' => $termine], JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }

    public function loescheTermin(Request $request)
    {
        $kennung = session('kennung');
        if (!$kennung) return response()->json(['error' => 'Keine Benutzerkennung'], 403);

        $id = $request->input('id');
        if (!$id) return response()->json(['error' => 'ID fehlt'], 400);

        $dateiPfad = storage_path("app/profile/profil_{$kennung}.json");
        $json = file_get_contents($dateiPfad);
        $profil = json_decode($json, true);
        $profil['termine'] = array_values(array_filter($profil['termine'], fn($t) => $t['id'] !== $id));
        file_put_contents($dateiPfad, json_encode($profil, JSON_PRETTY_PRINT));

        return response()->json(['success' => true]);
    }
}
