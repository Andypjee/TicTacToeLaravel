{{-- resources/views/players/create.blade.php --}}
@extends('layout') {{-- Gebruik de basis layout van de applicatie --}}

@section('content')
    <h1>Nieuwe speler aanmaken</h1>

    {{-- Formulier om een nieuwe speler toe te voegen --}}
    <form action="{{ route('players.store') }}" method="POST">
        @csrf {{-- Beveiliging tegen CSRF-aanvallen --}}

        {{-- Invoerveld voor gebruikersnaam --}}
        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" id="username" required><br><br>

        {{-- Invoerveld voor wachtwoord --}}
        <label for="password">Wachtwoord:</label>
        <input type="text" name="password" id="password" required><br><br>

        {{-- Knop om het formulier te verzenden --}}
        <button type="submit">Opslaan</button>
    </form>

    {{-- Link terug naar het overzicht van spelers --}}
    <a href="{{ route('players.index') }}">← Terug naar spelerslijst</a>
@endsection