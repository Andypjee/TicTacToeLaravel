<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * 🔹 Toon het formulier om het profiel te bewerken.
     */
    public function edit(Request $request): View
    {
        // Haal de huidige ingelogde gebruiker op en stuur door naar de view
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * 🔹 Werk het profiel bij met gevalideerde data.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Vul het User-model met de gevalideerde invoer
        $request->user()->fill($request->validated());

        // Als het e-mailadres veranderd is, zet verificatie op null (verificatie opnieuw nodig)
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Sla de wijzigingen op in de database
        $request->user()->save();

        // Redirect terug naar het profielbewerkingsformulier met een statusmelding
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * 🔹 Verwijder het account van de gebruiker.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ✅ Valideer het wachtwoord van de gebruiker voor veiligheid
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout(); // Log de gebruiker uit

        $user->delete(); // Verwijder de gebruiker uit de database

        // Vernietig en reset de sessie
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect naar homepage na verwijdering
        return Redirect::to('/');
    }
}
