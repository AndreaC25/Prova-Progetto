<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Il rating è obbligatorio.',
            'rating.integer' => 'Il rating deve essere un numero.',
            'rating.min' => 'Il rating minimo è 1 stella.',
            'rating.max' => 'Il rating massimo è 5 stelle.',
            'comment.max' => 'Il commento non può superare i 1000 caratteri.',
        ];
    }
}
