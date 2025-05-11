@extends('layouts.header')

@section('title', 'Professoren Liste')

@section('content')
    <div class="professoren-container">
        <!-- Professorenliste -->
        <div class="professoren-buttons">
            <h2>Professorenliste</h2>
            @foreach ($professoren as $professor)
                <button class="professoren-item"
                        data-id="{{ $professor['kennung'] }}"
                        data-name="{{ $professor['name'] }}"
                        data-email="{{ $professor['email'] }}"
                        data-buero="{{ $professor['buero'] }}"
                        data-labore="{{ implode(', ', $professor['labore']) }}">
                    {{ $professor['name'] }}
                </button>
            @endforeach
        </div>

        <!-- Professoren Profilbereich -->
        <div id="professor-profile" class="professor-profile">
            <h2>Wählen Sie einen Professor</h2>
        </div>
    </div>
@endsection

<script src="{{ asset('js/professoren.js') }}"></script>
