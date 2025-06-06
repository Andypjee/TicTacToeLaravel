<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index()
    {
        $players = Player::all();
        return view("players.index", compact("players"));
    }

    public function create()
    {
        return view("players.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:100'
        ]);

        Player::create([
            'username' => $request->username,
            'password' => $request->password
        ]);

        return redirect()->route("players.index");
    }

    public function show(Player $player)
    {
        return view("players.show", compact("player"));
    }

    public function edit(Player $player)
    {
        return view("players.edit", compact("player"));
    }

    public function update(Request $request, Player $player)
    {
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:100'
        ]);

        $player->update([
            'username' => $request->username,
            'password' => $request->password
        ]);

        return redirect()->route("players.index");
    }

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route("players.index");
    }
}


