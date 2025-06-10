@extends('layout') {{-- Uitbreiding van de basislayout --}}

@section('content')
    <h1>Alle spellen</h1>

    {{-- Link naar pagina om een nieuw spel te starten --}}
    <a href="{{ route('games.create') }}">Nieuw spel starten</a>

    {{-- Lijst van alle spellen --}}
    <ul>
        {{-- Loop door alle spellen en maak een lijstitem --}}
        @foreach($games as $game)
            <li>
                {{-- Link naar de detailpagina van het spel --}}
                <a href="{{ route('games.show', $game) }}">
                    Spel #{{ $game->id }} – Status: {{ $game->status }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection
