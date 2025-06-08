<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'nullable',
                'string',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/',
                'min:10',
                'max:20',
                Rule::unique('profiles', 'phone')->ignore($this->user()->profile->id ?? null)
            ],
            'company_address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'phone.unique' => 'Questo numero di telefono è già in uso.',
            'phone.regex' => 'Il formato del numero di telefono non è valido.',
            'phone.min' => 'Il numero di telefono deve essere di almeno 10 caratteri.',
            'phone.max' => 'Il numero di telefono non può superare i 20 caratteri.',
            'profile_image.image' => 'Il file deve essere un\'immagine.',
            'profile_image.mimes' => 'L\'immagine deve essere in formato JPEG, PNG, JPG o GIF.',
            'profile_image.max' => 'L\'immagine non può superare i 2MB.',
            'company_address.max' => 'L\'indirizzo della società non può superare i 500 caratteri.',
            'description.max' => 'La descrizione non può superare i 1000 caratteri.'
        ];
    }
}
