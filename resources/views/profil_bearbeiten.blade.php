<head>
    <link rel="stylesheet" href="/css/pbearbeiten.css">
</head>
@extends('layouts.header')

@section('title', 'Profil bearbeiten')

@section('content')
    <div class="profil-container">
        <h2 class="profil-title">Profil bearbeiten</h2>

        <form action="{{ url('/profil/bearbeiten') }}" method="POST" class="profil-form" enctype="multipart/form-data">
            @csrf

            <label>Name:
                <input type="text" name="name" value="{{ old('name', $profil['name']) }}">
            </label>

            <label>Email:
                <input type="email" name="email" value="{{ old('email', $profil['email']) }}">
            </label>

            @if($rolle === 'Student')
                <label>Studiengang:
                    <input type="text" name="studiengang" value="{{ old('studiengang', $profil['studiengang']) }}">
                </label>

                <label>Semester:
                    <input type="number" name="semester" value="{{ old('semester', $profil['semester']) }}">
                </label>
            @elseif($rolle === 'Prof')
                <label>Büro:
                    <input type="text" name="buero" value="{{ old('buero', $profil['buero']) }}">
                </label>

                <label>Labore (ein Labor pro Zeile):
                    <textarea name="labore" rows="4">{{ old('labore', is_array($profil['labore']) ? implode("\n", $profil['labore']) : $profil['labore']) }}</textarea>
                </label>
            @endif

            <!-- Bild Upload hinzufügen -->
            <label>Profilbild:
                <input type="file" name="avatar">
            </label>

            <button type="submit" class="btn">Speichern</button>
        </form>
    </div>
@endsection
