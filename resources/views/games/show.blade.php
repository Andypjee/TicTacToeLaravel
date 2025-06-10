@extends('layout') {{-- Gebruik de layout template --}}

@section('content')
    <h1>Tic-Tac-Toe Spel #{{ $game->id }}</h1>

    {{-- Toon spelerinformatie en status van het spel --}}
    <p><strong>Speler X:</strong> {{ $game->playerX->username }}</p>
    <p><strong>Speler O:</strong> {{ $game->playerO->username }}</p>
    <p><strong>Status:</strong> {{ $game->status }}</p>

    @php
        // 🔸 Initieer het speelbord (9 lege vakjes)
        $board = array_fill(0, 9, null);

        // 🔸 Vul het bord met gemaakte zetten (X of O)
        foreach ($game->moves as $move) {
            $symbol = $move->player_id === $game->player_x_id ? 'X' : 'O';
            $board[$move->position] = $symbol;
        }

        // 🔸 Bepaal wie aan de beurt is
        $currentTurn = $game->moves->count();
        $currentPlayer = $currentTurn % 2 === 0 ? $game->player_x_id : $game->player_o_id;
        $playerSymbol = $currentPlayer === $game->player_x_id ? 'X' : 'O';
    @endphp

    {{-- Stijlen voor het speelveld --}}
    <style>
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 60px);
            gap: 5px;
            margin: 20px 0;
        }

        .cell {
            width: 60px;
            height: 60px;
            text-align: center;
            line-height: 60px;
            border: 1px solid black;
            font-size: 24px;
            cursor: pointer;
        }

        .cell.filled {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
    </style>

    {{-- Toon huidige speler --}}
    <h3>Huidige speler: {{ $playerSymbol }}</h3>

    @if($game->status !== 'completed')
        {{-- Formulier voor een nieuwe zet --}}
        <form method="POST" action="{{ route('moves.store') }}" id="moveForm">
            @csrf

            {{-- Verborgen velden om game, speler en gekozen positie door te geven --}}
            <input type="hidden" name="game_id" value="{{ $game->id }}">
            <input type="hidden" name="player_id" value="{{ $currentPlayer }}">
            <input type="hidden" name="position" id="positionInput">

            {{-- Raster van 9 cellen voor het bord --}}
            <div class="grid">
                @for ($i = 0; $i < 9; $i++)
                    <div class="cell {{ $board[$i] ? 'filled' : '' }}" onclick="makeMove({{ $i }})">
                        {{ $board[$i] }}
                    </div>
                @endfor
            </div>
        </form>
    @else
        {{-- Als het spel is voltooid, toon de winnaar of gelijkspel --}}
        <h3>
            @if($game->winner)
                Winnaar: {{ $game->winner->username }}
            @else
                Gelijkspel!
            @endif
        </h3>
    @endif

    {{-- JavaScript-functie voor het uitvoeren van een zet --}}
    <script>
        function makeMove(position) {
            const cell = document.querySelectorAll('.cell')[position];
            if (cell.classList.contains('filled')) return; // Als de cel al bezet is, doe niets

            document.getElementById('positionInput').value = position; // Zet gekozen positie in formulier
            document.getElementById('moveForm').submit(); // Verstuur formulier
        }
    </script>
@endsection