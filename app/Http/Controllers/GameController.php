<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // 🔹 Toon een overzicht van alle spellen
    public function index()
    {
        $games = Game::all(); // Haal alle spellen uit de database
        return view('games.index', compact('games')); // Stuur ze door naar de view
    }

    // 🔹 Toon het formulier om een nieuw spel aan te maken
    public function create()
    {
        $players = \App\Models\Player::all(); // Haal alle spelers op voor selectie
        return view('games.create', compact('players')); // Toon het formulier
    }

    // 🔹 Verwerk het formulier en maak een nieuw spel aan
    public function store(Request $request)
    {
        // ✅ Valideer dat beide spelers bestaan en verschillend zijn
        $request->validate([
            'player_x_id' => 'required|exists:players,id',
            'player_o_id' => 'required|exists:players,id|different:player_x_id', 
            // different:player_x_id voorkomt dat dezelfde speler beide kanten speelt
        ]);

        // ✅ Maak het nieuwe spel aan met status 'pending' (nog niet gestart)
        Game::create([
            'player_x_id' => $request->player_x_id,
            'player_o_id' => $request->player_o_id,
            'status' => 'pending',
        ]);

        // ✅ Redirect terug naar de spellenlijst met succesbericht
        return redirect()->route('games.index')->with('success', 'Spel succesvol aangemaakt!');
    }

    // 🔹 Toon detailpagina van één spel
    public function show(Game $game)
    {
        // Bereken wie er aan de beurt is aan de hand van het aantal zetten
        $currentTurn = $game->moves()->count(); // Aantal zetten gedaan
        // Als het even aantal zetten zijn, is speler X aan de beurt, anders O
        $currentPlayer = $currentTurn % 2 === 0 ? $game->player_x_id : $game->player_o_id;

        // Stuur het spel en de huidige speler door naar de view
        return view('games.show', compact('game', 'currentPlayer'));
    }
}
