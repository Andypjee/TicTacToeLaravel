@extends('layout') {{-- Gebruik de standaard layout van de app --}}

@section('content')
    <h1>Spelerslijst</h1>

    {{-- Link om een nieuwe speler toe te voegen --}}
    <a href="{{ route('players.create') }}">➕ Nieuwe speler aanmaken</a>

    <ul>
        @forelse ($players as $player)
            <li>
                {{-- Toon gebruikersnaam van de speler --}}
                {{ $player->username }}

                {{-- Link om de speler te bewerken --}}
                <a href="{{ route('players.edit', $player) }}">✏️</a>

                {{-- Formulier om de speler te verwijderen --}}
                <form action="{{ route('players.destroy', $player) }}" method="POST" style="display:inline">
                    @csrf {{-- CSRF-beveiliging --}}
                    @method('DELETE') {{-- Spoofing van DELETE HTTP-method via POST --}}
                    
                    {{-- Verwijderknop met bevestiging --}}
                    <button type="submit" onclick="return confirm('Weet je zeker dat je deze speler wilt verwijderen?')">🗑️</button>
                </form>
            </li>
        @empty
            {{-- Als er geen spelers zijn --}}
            <li>Geen spelers gevonden.</li>
        @endforelse
    </ul>
@endsection
