@extends('layout') {{-- Uitbreiding van de basislayout --}}

@section('content')
    <h1>Start nieuw spel</h1>

    {{-- Formulier om een nieuw spel aan te maken --}}
    <form method="POST" action="{{ route('games.store') }}">
        @csrf {{-- CSRF-token ter beveiliging tegen cross-site requests --}}

        {{-- Selectievak voor speler X --}}
        <label>Speler X:
            <select name="player_x_id">
                {{-- Loop door alle spelers en maak opties --}}
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->username }}</option>
                @endforeach
            </select>
        </label>
        <br>

        {{-- Selectievak voor speler O --}}
        <label>Speler O:
            <select name="player_o_id">
                {{-- Zelfde lijst met spelers als hierboven --}}
                @foreach($players as $player)
                    <option value="{{ $player->id }}">{{ $player->username }}</option>
                @endforeach
            </select>
        </label>
        <br>

        {{-- Verzenden knop om het spel te starten --}}
        <button type="submit">Start spel</button>
    </form>
@endsection
