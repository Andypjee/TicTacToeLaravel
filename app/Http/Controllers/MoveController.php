<?php

namespace App\Http\Controllers;

use App\Models\Move;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Services\TicTacToeService;

class MoveController extends Controller
{
    // 🔹 Toon een lijst met alle zetten
    public function index()
    {
        $moves = Move::all(); // Haal alle zetten op uit de database
        return view("moves.index", compact("moves")); // Geef ze door aan de view
    }

    // 🔹 Toon het formulier om een nieuwe zet te maken
    public function create()
    {
        $games = Game::all();     // Haal alle games op voor de keuzelijst
        $players = Player::all(); // Haal alle spelers op
        return view("moves.create", compact("games", "players")); // Toon het formulier
    }

    // 🔹 Verwerk en sla een nieuwe zet op
    public function store(Request $request, TicTacToeService $service)
    {
        // ✅ Validatie van het formulier
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'player_id' => 'required|integer|exists:players,id',
            'position' => 'required|integer|min:0|max:8', // Posities 0-8 (3x3 bord)
        ]);

        $game = Game::findOrFail($request->game_id); // Haal het spel op of faal

        // ✅ Controleer of het spel al voorbij is
        if ($game->status === 'completed') {
            return redirect()->back()->withErrors("Dit spel is al afgelopen.");
        }

        // ✅ Controleer of het de juiste beurt is
        if (!$service->isPlayerTurn($game, $request->player_id)) {
            return redirect()->back()->withErrors("Het is niet jouw beurt.");
        }

        // ✅ Controleer of de gekozen positie al bezet is
        if ($game->moves()->where('position', $request->position)->exists()) {
            return redirect()->back()->withErrors("Deze positie is al bezet.");
        }

        // ✅ Sla de zet op
        $move = Move::create([
            'game_id' => $request->game_id,
            'player_id' => $request->player_id,
            'position' => $request->position,
            'turn_number' => $game->moves()->count(), // Aantal zetten tot nu toe
        ]);

        // ✅ Zet de status van het spel op 'active' als het nog 'pending' is
        if ($game->status === 'pending') {
            $game->status = 'active';
            $game->save();
        }

        // ✅ Controleer of er een winnaar is
        $winnerId = $service->checkForWinner($game);
        if ($winnerId) {
            // ✅ Er is een winnaar: update spelstatus en winnaar
            $game->update([
                'winner_id' => $winnerId,
                'status' => 'completed'
            ]);
        } elseif ($service->isDraw($game)) {
            // ✅ Als het gelijkspel is (alle posities bezet zonder winnaar)
            $game->update(['status' => 'completed']);
        }

        // ✅ Stuur terug naar de detailpagina van het spel
        return redirect()->route("games.show", $game);
    }

    // 🔹 Toon een specifieke zet
    public function show(Move $move)
    {
        return view("moves.show", compact("move"));
    }

    // 🔹 Toon formulier om een zet te bewerken
    public function edit(Move $move)
    {
        $games = Game::all();     // Voor selectielijst
        $players = Player::all(); // Voor selectielijst
        return view("moves.edit", compact("move", "games", "players"));
    }

    // 🔹 Verwerk de bewerking van een zet
    public function update(Request $request, Move $move)
    {
        // ✅ Validatie van de invoer
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'player_id' => 'required|integer|exists:players,id',
            'position' => 'required|integer|min:0|max:8',
            'turn_number' => 'required|integer|min:0'
        ]);

        // ✅ Zet bijwerken in de database
        $move->update([
            'game_id' => $request->game_id,
            'player_id' => $request->player_id,
            'position' => $request->position,
            'turn_number' => $request->turn_number
        ]);

        // ✅ Redirect naar overzicht van zetten
        return redirect()->route("moves.index");
    }

    // 🔹 Verwijder een zet
    public function destroy(Move $move)
    {
        $move->delete(); // Zet verwijderen
        return redirect()->route("moves.index"); // Terug naar lijst
    }
}
