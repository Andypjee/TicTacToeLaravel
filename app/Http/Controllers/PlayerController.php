<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    // 🔹 Toon een lijst met alle spelers
    public function index()
    {
        $players = Player::all(); // Alle spelers ophalen uit de database
        return view("players.index", compact("players")); // Doorgeven aan de view
    }

    // 🔹 Toon het formulier om een nieuwe speler aan te maken
    public function create()
    {
        return view("players.create"); // Alleen het formulier tonen
    }

    // 🔹 Verwerk het formulier en sla een nieuwe speler op
    public function store(Request $request)
    {
        // ✅ Validatie van de input: username en wachtwoord zijn verplicht
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:100'
        ]);

        // ✅ Speler aanmaken in de database
        Player::create([
            'username' => $request->username,
            'password' => $request->password // Let op: wachtwoord niet gehashed hier!
        ]);

        // ✅ Terug naar overzicht van spelers
        return redirect()->route("players.index");
    }

    // 🔹 Toon details van een specifieke speler
    public function show(Player $player)
    {
        return view("players.show", compact("player"));
    }

    // 🔹 Toon het formulier om een speler te bewerken
    public function edit(Player $player)
    {
        return view("players.edit", compact("player"));
    }

    // 🔹 Verwerk het formulier om een speler te updaten
    public function update(Request $request, Player $player)
    {
        // ✅ Validatie van invoer
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|max:100'
        ]);

        // ✅ Spelergegevens bijwerken
        $player->update([
            'username' => $request->username,
            'password' => $request->password // Let op: wachtwoord niet gehashed hier!
        ]);

        // ✅ Terug naar spelerslijst
        return redirect()->route("players.index");
    }

    // 🔹 Verwijder een speler uit de database
    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route("players.index");
    }
}
