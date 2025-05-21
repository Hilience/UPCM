@extends('layouts.header')
@section('title', 'Terminkalender')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/Kalender.css') }}">

    <div class="kalender-seite">
        <h1>Terminkalender</h1>
        <div class="kalender-navigation">
            <div class="button-gruppe">
                <button id="btn-monat">Monatsansicht</button>
                <button id="btn-woche">Wochenansicht</button>
            </div>
            <div class="button-gruppe">
                <button id="btn-prev">◀</button>
                <span id="kalender-titel"></span>
                <button id="btn-next">▶</button>
            </div>
            <button id="btn-neu" class="btn-erstellen">Termin erstellen</button>
        </div>

        <div id="monatsansicht" class="kalender-ansicht">
            <div class="kalender-grid monat"></div>
        </div>
        <div id="wochensicht" class="kalender-ansicht" style="display: none;">
            <div id="wochen-grid" class="wochenansicht-grid"></div>
        </div>
    </div>

    <div id="termin-modal" class="modal hidden">
        <div class="modal-content">
            <h2>Termin erstellen / bearbeiten</h2>
            <form id="termin-form">
                <input type="text" name="titel" placeholder="Titel" required>
                <input type="date" name="datum" required>
                <input type="time" name="von" required>
                <input type="time" name="bis" required>
                <textarea name="kommentar" placeholder="Kommentar"></textarea>
                <select name="wiederholung">
                    <option value="einmalig">Einmalig</option>
                    <option value="taeglich">Täglich</option>
                    <option value="woechentlich">Wöchentlich</option>
                    <option value="alle2wochen">Alle 2 Wochen</option>
                    <option value="monatlich">Monatlich</option>
                </select>
                <input type="date" name="enddatum">
                <label>Farbe:</label>
                <div id="farb-auswahl"></div>
                <input type="hidden" name="farbe" id="farbe" value="#007bff">
                <button type="submit">Speichern</button>
                <button type="button" id="btn-loeschen" style="display:none;">Löschen</button>
                <button type="button" id="btn-schliessen">Abbrechen</button>
            </form>
        </div>
    </div>

    <div id="loeschen-bestätigung-modal" class="hidden">
        <div>
            <p>Diesen Termin wirklich löschen?</p>
            <button id="btn-bestätigen-loeschen">Löschen</button>
            <button id="btn-abbrechen-loeschen">Abbrechen</button>
        </div>
    </div>

    <div id="toast">Termin gespeichert!</div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.termineVomServer = @json($termine);
    </script>
    <script src="{{ asset('js/kalender.js') }}"></script>
    <script src="{{ asset('js/Kalender bearbeiten.js') }}"></script>
@endsection
