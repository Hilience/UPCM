<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaborController extends Controller
{
    public function liste()
    {
        $laborPfad = storage_path('app/labore');
        $laborDateien = glob($laborPfad . '/*.json');

        $labore = [];
        foreach ($laborDateien as $file) {
            $json = json_decode(file_get_contents($file), true);
            if ($json) {
                $labore[] = [
                    'name' => $json['name'],
                    'raum' => $json['raum'],
                    'slug' => Str::slug($json['name']),
                    'verantwortliche' => $json['verantwortliche'] ?? [],
                    'projekte' => $json['projekte'] ?? [],
                    'stellenangebote' => $json['stellenangebote'] ?? [],
                    'news' => $json['news'] ?? [],
                    'feedback' => $json['feedback'] ?? '',
                ];
            }
        }

        return view('labore', ['labore' => $labore]);
    }
}
