<head>
    <link rel="stylesheet" href="/css/home.css">
</head>
@extends('layouts.header')

@section('title', 'Startseite')

@section('content')
    <div class="content">
        <section class="labs-projects">
            <h2>Meine Labore & Projekte</h2>
            <div class="lab-list">
                <div class="lab">
                    <h3>Labor 1</h3>
                    <p>Projektbeschreibung 1...</p>
                </div>
                <div class="lab">
                    <h3>Labor 2</h3>
                    <p>Projektbeschreibung 2...</p>
                </div>
            </div>
        </section>

        <aside class="sidebar">
            <div class="sidebar-section">
                <h3>Meine Termine</h3>
                <ul>
                    <li>Termineintrag 1</li>
                    <li>Termineintrag 2</li>
                </ul>
            </div>

            <div class="sidebar-section">
                <h3>Labornachrichten</h3>
                <ul>
                    <li>Nachricht 1</li>
                    <li>Nachricht 2</li>
                </ul>
            </div>
        </aside>
    </div>
@endsection
