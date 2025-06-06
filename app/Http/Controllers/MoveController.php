<?php

namespace App\Http\Controllers;

use App\Models\Move;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    public function index()
    {
        $moves = Move::all();
        return view("moves.index", compact("moves"));
    }

    public function create()
    {
        $games = Game::all();
        $players = Player::all();
        return view("moves.create", compact("games", "players"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'player_id' => 'required|integer|exists:players,id',
            'position' => 'required|integer|min:0|max:8',
            'turn_number' => 'required|integer|min:0'
        ]);

        Move::create([
            'game_id' => $request->game_id,
            'player_id' => $request->player_id,
            'position' => $request->position,
            'turn_number' => $request->turn_number
        ]);

        return redirect()->route("moves.index");
    }

    public function show(Move $move)
    {
        return view("moves.show", compact("move"));
    }

    public function edit(Move $move)
    {
        $games = Game::all();
        $players = Player::all();
        return view("moves.edit", compact("move", "games", "players"));
    }

    public function update(Request $request, Move $move)
    {
        $request->validate([
            'game_id' => 'required|integer|exists:games,id',
            'player_id' => 'required|integer|exists:players,id',
            'position' => 'required|integer|min:0|max:8',
            'turn_number' => 'required|integer|min:0'
        ]);

        $move->update([
            'game_id' => $request->game_id,
            'player_id' => $request->player_id,
            'position' => $request->position,
            'turn_number' => $request->turn_number
        ]);

        return redirect()->route("moves.index");
    }

    public function destroy(Move $move)
    {
        $move->delete();
        return redirect()->route("moves.index");
    }
}
