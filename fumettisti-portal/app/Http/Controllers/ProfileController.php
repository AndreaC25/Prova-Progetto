<?php
// app/Http/Controllers/ProfileController.php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = Auth::user()->load(['profile', 'fumetti' => function($query) {
            $query->published()->latest()->take(6);
        }]);

        // Statistiche utente
        $stats = [
            'fumetti_pubblicati' => $user->fumetti()->published()->count(),
            'recensioni_scritte' => $user->reviews()->count(),
            'media_recensioni' => $user->fumetti()
                ->published()
                ->withAvg('approvedReviews', 'rating')
                ->get()
                ->avg('approved_reviews_avg_rating') ?? 0,
            'membro_da' => $user->created_at->diffForHumans(),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user()->load('profile');

        // Crea il profilo se non esiste
        if (!$user->profile) {
            $user->profile()->create([]);
            $user->load('profile');
        }

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Aggiorna dati utente
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Crea il profilo se non esiste
        if (!$user->profile) {
            $user->profile()->create([]);
        }

        $profileData = [
            'phone' => $validated['phone'] ?? null,
            'company_address' => $validated['company_address'] ?? null,
            'bio' => $validated['bio'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'website' => $validated['website'] ?? null,
            'social_links' => [
                'facebook' => $validated['social_links']['facebook'] ?? null,
                'instagram' => $validated['social_links']['instagram'] ?? null,
                'twitter' => $validated['social_links']['twitter'] ?? null,
                'linkedin' => $validated['social_links']['linkedin'] ?? null,
            ]
        ];

        // Gestione caricamento avatar
        if ($request->hasFile('avatar')) {
            // Elimina il vecchio avatar se presente
            if ($user->profile->avatar && !filter_var($user->profile->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            // Salva il nuovo avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profileData['avatar'] = $avatarPath;
        }

        // Aggiorna il profilo
        $user->profile->update($profileData);

        return redirect()->route('profile.show')->with('success', 'Profilo aggiornato con successo!');
    }

    /**
     * Show public profile of a user.
     */
    public function showPublic($id)
    {
        $user = User::with(['profile', 'fumetti' => function($query) {
            $query->published()->latest();
        }])->findOrFail($id);

        // Statistiche pubbliche
        $stats = [
            'fumetti_pubblicati' => $user->fumetti()->published()->count(),
            'media_recensioni' => $user->fumetti()
                ->published()
                ->withAvg('approvedReviews', 'rating')
                ->get()
                ->avg('approved_reviews_avg_rating') ?? 0,
            'membro_da' => $user->created_at->format('F Y'),
        ];

        return view('profile.public', compact('user', 'stats'));
    }

    /**
     * Upload avatar via AJAX
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

        if (!$user->profile) {
            $user->profile()->create([]);
        }

        // Elimina il vecchio avatar
        if ($user->profile->avatar && !filter_var($user->profile->avatar, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($user->profile->avatar);
        }

        // Salva il nuovo avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->profile->update(['avatar' => $avatarPath]);

        return response()->json([
            'success' => true,
            'avatar_url' => $user->profile->avatar_url,
            'message' => 'Avatar aggiornato con successo!'
        ]);
    }

    /**
     * Remove avatar
     */
    public function removeAvatar()
    {
        $user = Auth::user();

        if ($user->profile && $user->profile->avatar) {
            // Elimina il file se non è un URL esterno
            if (!filter_var($user->profile->avatar, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->profile->avatar);
            }

            $user->profile->update(['avatar' => null]);
        }

        return response()->json([
            'success' => true,
            'avatar_url' => $user->profile->avatar_url,
            'message' => 'Avatar rimosso con successo!'
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'La password attuale è obbligatoria.',
            'password.required' => 'La nuova password è obbligatoria.',
            'password.min' => 'La password deve avere almeno 8 caratteri.',
            'password.confirmed' => 'La conferma password non corrisponde.',
        ]);

        $user = Auth::user();

        // Verifica password attuale
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La password attuale non è corretta.']);
        }

        // Aggiorna password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password cambiata con successo!');
    }
}
