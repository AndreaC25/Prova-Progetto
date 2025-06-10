<?php
// app/Http/Requests/ProfileUpdateRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $userId = auth()->id();

        return [
            // Dati utente base
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId)
            ],

            // Dati profilo
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\+39)?[0-9]{9,10}$/',
                Rule::unique('profiles')->ignore(auth()->user()->profile->id ?? null)
            ],
            'company_address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'birth_date' => 'nullable|date|before:today',
            'website' => 'nullable|url|max:255',

            // Avatar
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Social links
            'social_links.facebook' => 'nullable|url',
            'social_links.instagram' => 'nullable|url',
            'social_links.twitter' => 'nullable|url',
            'social_links.linkedin' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Il nome è obbligatorio.',
            'email.required' => 'L\'email è obbligatoria.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.unique' => 'Questa email è già in uso.',

            'phone.regex' => 'Inserisci un numero di telefono italiano valido (es. +39 123 456 7890).',
            'phone.unique' => 'Questo numero di telefono è già in uso.',

            'company_address.max' => 'L\'indirizzo non può superare i 500 caratteri.',
            'bio.max' => 'La descrizione non può superare i 1000 caratteri.',
            'birth_date.before' => 'La data di nascita deve essere nel passato.',
            'website.url' => 'Inserisci un URL valido per il sito web.',

            'avatar.image' => 'Il file deve essere un\'immagine.',
            'avatar.mimes' => 'L\'immagine deve essere in formato JPEG, PNG, JPG o GIF.',
            'avatar.max' => 'L\'immagine non può superare i 2MB.',

            'social_links.*.url' => 'Inserisci un URL valido per i social network.',
        ];
    }

    /**
     * Prepara i dati per la validazione
     */
    protected function prepareForValidation()
    {
        // Normalizza il numero di telefono
        if ($this->phone) {
            $phone = preg_replace('/[^0-9+]/', '', $this->phone);

            // Aggiungi prefisso italiano se manca
            if (!str_starts_with($phone, '+39') && !str_starts_with($phone, '39')) {
                if (strlen($phone) === 10) {
                    $phone = '+39' . $phone;
                }
            }

            $this->merge(['phone' => $phone]);
        }
    }
}
