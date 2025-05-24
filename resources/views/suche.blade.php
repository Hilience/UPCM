<head>
    <link rel="stylesheet" href="/css/suche.css">
</head>

@extends('layouts.header')

@section('content')
    <?php
    $searchTerm = request('eingabe');
    $results = [];

    // Immer suchen (auch bei leerem Suchbegriff)
    $profilePath = storage_path('app/profile');
    $profileFiles = file_exists($profilePath) ? scandir($profilePath) : [];

    foreach ($profileFiles as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
            // Extrahiere Kennung aus Dateinamen
            $kennung = str_replace(['profil_', '.json'], '', basename($file));
            $content = file_get_contents($profilePath . '/' . $file);
            $profile = json_decode($content, true);

            if (isset($profile['profil']['name']) && isset($profile['profil']['buero'])) {
                $name = $profile['profil']['name'];
                if (empty($searchTerm) || stripos($name, $searchTerm) !== false) {
                    $results[] = [
                        'type' => 'profil',
                        'data' => $profile,
                        'kennung' => $kennung
                    ];
                }
            }
        }
    }


    $laborePath = storage_path('app/labore');
    $laboreFiles = file_exists($laborePath) ? scandir($laborePath) : [];

    foreach ($laboreFiles as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
            $content = file_get_contents($laborePath . '/' . $file);
            $labor = json_decode($content, true);

            if (isset($labor['name'])) {
                $name = $labor['name'];
                if (empty($searchTerm) || stripos($name, $searchTerm) !== false) {
                    $results[] = ['type' => 'labor', 'data' => $labor];
                }
            }
        }
    }
    ?>
    <div class="container mx-auto px-4 max-w-3xl">
        <!-- Suchleiste -->
        <div class="suche-container">
            <form class="suche-formular" method="GET">
                <input
                    type="text"
                    name="eingabe"
                    value="{{ $searchTerm }}"
                    placeholder="Suche..."
                    class="suche-eingabe"
                >
            </form>
            <!-- Suchergebnisse -->
            <div class="suchergebnisse">
                @forelse($results as $result)
                    @if($result['type'] === 'profil')
                        <a href="/professoren?kennung={{ $result['kennung'] }}" class="ergebnis-karte profil-karte">
                            <h3 class="ergebnis-titel profil-titel">🎓 {{ $result['data']['profil']['name'] }}</h3>
                            <div class="ergebnis-details">
                                @if(isset($result['data']['profil']['buero']))
                                    <p>Raum {{ $result['data']['profil']['buero'] }}</p>
                                    <p>Leitung: {{ implode(', ', $result['data']['profil']['labore']) }}</p>
                                @else
                                    <p>Studiengang: {{ $result['data']['profil']['studiengang'] }}</p>
                                @endif
                            </div>
                        </a>
                    @else
                        <a href="/laborliste?name={{ urlencode($result['data']['name']) }}"
                           class="ergebnis-karte labor-karte">
                            <h3 class="ergebnis-titel labor-titel">🔬 {{ $result['data']['name'] }}</h3>
                            <div class="ergebnis-details">
                                <p>Raum {{ $result['data']['raum'] }}</p>
                                <p>Verantwortliche: {{ implode(', ', $result['data']['verantwortliche']) }}</p>
                            </div>
                        </a>
                    @endif
                @empty
                    <div class="keine-ergebnisse">
                        {{ $searchTerm ? 'Keine Ergebnisse gefunden' : 'Bitte Suchbegriff eingeben' }}
                    </div>
                @endforelse
            </div>
        </div>
@endsection
