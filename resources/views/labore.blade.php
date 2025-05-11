@extends('layouts.header')

@section('title', 'Laborliste')

@section('content')
    <div class="laboren-container">
        <div class="laboren-buttons">
            <h2>Laborliste</h2>
            @foreach ($labore as $labor)
                <button class="laboren-item"
                        data-id="{{ $labor['slug'] }}"
                        data-name="{{ $labor['name'] }}"
                        data-raum="{{ $labor['raum'] }}"
                        data-verantwortliche="{{ implode(', ', $labor['verantwortliche']) }}"
                        data-projekte="{{ implode(', ', $labor['projekte']) }}"
                        data-stellen="{{ implode(', ', $labor['stellenangebote']) }}"
                        data-news="{{ implode('||', array_map(function($news) { return $news['titel'] . '::' . $news['inhalt']; }, $labor['news'])) }}"
                        data-feedback="{{ $labor['feedback'] }}">
                    {{ $labor['name'] }}
                </button>
            @endforeach
        </div>

        <div id="labor-profile" class="labor-profile">
            <h2>Wählen Sie ein Labor</h2>
        </div>
    </div>
@endsection

<script src="{{ asset('js/labore.js') }}"></script>
