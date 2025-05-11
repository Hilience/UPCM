@extends('layouts.header')

@section('title', 'Profil')

@section('content')
    <div class="profil-container">
        <h2 class="profil-title">Mein Profil ({{ $rolle }})</h2>
        <div class="profil-header">
            <img src="{{ asset('images/' . ($profil['avatar'] ?? 'avatar.png')) }}" alt="Profilbild" class="profil-avatar">
            <h2>{{ $profil['name'] }}</h2>
            <p class="rolle">{{ $rolle }}</p>
        </div>
        <ul>
            <li><strong>Name:</strong> {{ $profil['name'] }}</li>
            <li><strong>Email:</strong> {{ $profil['email'] }}</li>

            @if($rolle === 'Student')
                <li><strong>Studiengang:</strong> {{ $profil['studiengang'] }}</li>
                <li><strong>Semester:</strong> {{ $profil['semester'] }}</li>
            @elseif($rolle === 'Prof')
                <li><strong>Büroraum:</strong> {{ $profil['buero'] }}</li>
                <li>
                    <strong>Labore:</strong>
                    @if(is_array($profil['labore']))
                        <ul>
                            @foreach($profil['labore'] as $labor)
                                <li>{{ $labor }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $profil['labore'] }}
                    @endif
                </li>
            @endif
        </ul>

        <a href="{{ url('/profil/bearbeiten') }}" class="btn">Profil bearbeiten</a>
    </div>
@endsection
