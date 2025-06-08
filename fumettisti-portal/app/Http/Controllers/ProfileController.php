<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Mostra il profilo dell'utente autenticato
     */
    public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profile.show', compact('user', 'profile'));
    }

    /**
     * Mostra il form per modificare il profilo
     */
    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Aggiorna il profilo dell'utente
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $data = $request->validated();

        // Gestione caricamento immagine profilo
        if ($request->hasFile('profile_image')) {
            // Elimina la vecchia immagine se presente
            if ($profile->profile_image && Storage::disk('public')->exists($profile->profile_image)) {
                Storage::disk('public')->delete($profile->profile_image);
            }

            // Salva la nuova immagine
            $imagePath = $request->file('profile_image')->store('profile-images', 'public');
            $data['profile_image'] = $imagePath;
        }
