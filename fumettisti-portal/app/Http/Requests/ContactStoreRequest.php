<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Accessibile a tutti, anche ospiti
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|min:5|max:200',
            'message' => 'required|string|min:10|max:2000'
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Il nome è obbligatorio.',
            'name.min' => 'Il nome deve essere di almeno 2 caratteri.',
            'name.max' => 'Il nome non può superare i 100 caratteri.',
            'email.required' => 'L\'email è obbligatoria.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.max' => 'L\'email non può superare i 255 caratteri.',
            'subject.required' => 'L\'oggetto è obbligatorio.',
            'subject.min' => 'L\'oggetto deve essere di almeno 5 caratteri.',
            'subject.max' => 'L\'oggetto non può superare i 200 caratteri.',
            'message.required' => 'Il messaggio è obbligatorio.',
            'message.min' => 'Il messaggio deve essere di almeno 10 caratteri.',
            'message.max' => 'Il messaggio non può superare i 2000 caratteri.'
        ];
    }

    /**
     * Get custom attribute names
     */
    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'email',
            'subject' => 'oggetto',
            'message' => 'messaggio'
        ];
    }
}
