<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Meine Seite')</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/professoren.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/labore.css') }}">
</head>
<body>

{{-- Gemeinsamer Header --}}
<header>
    <div class="container">
        <nav>
            <ul class="menu-left">
                <li><a href="/home">Home</a></li>
                <li><a href="/laborliste">Laborliste</a></li>
                <li><a href="/professoren">Professoren</a></li>
                <li><a href="/kalender">Terminkalender</a></li>

                {{-- Nur sichtbar, wenn die Rolle in der Session "Prof" ist --}}
                @if(session('rolle') === 'Prof')
                    <li><a href="/meine-labore">Meine Labore</a></li>
                @endif
            </ul>
            <ul class="menu-right">
                <li><input type="text" placeholder="Suche..."></li>
                <li><a href="/profil">Profil</a></li>
                <li><a href="{{ route('logout') }}">Logout</a></li>
            </ul>
        </nav>
    </div>
</header>

{{-- Hauptinhalt der Seite --}}
<main>
    <div class="container">
        @yield('content')
    </div>
</main>

</body>
</html>
