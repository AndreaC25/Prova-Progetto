<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FumettoUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && $this->route('fumetto')->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'issue_number' => 'required|integer|min:1|max:9999',
            'publication_year' => 'required|integer|min:1900|max:' . (date('Y') + 5),
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'plot' => 'nullable|string|max:2000',
            'magazine_id' => 'nullable|exists:magazines,id',
            'categories' => 'nullable|array|max:5',
            'categories.*' => 'exists:categories,id',
            'is_published' => 'boolean'
        ];
    }

    /**
     * Get custom error messages
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Il titolo del fumetto è obbligatorio.',
            'title.max' => 'Il titolo non può superare i 255 caratteri.',
            'issue_number.required' => 'Il numero del fumetto è obbligatorio.',
            'issue_number.integer' => 'Il numero del fumetto deve essere un numero intero.',
            'issue_number.min' => 'Il numero del fumetto deve essere almeno 1.',
            'issue_number.max' => 'Il numero del fumetto non può superare 9999.',
            'publication_year.required' => 'L\'anno di pubblicazione è obbligatorio.',
            'publication_year.integer' => 'L\'anno di pubblicazione deve essere un numero intero.',
            'publication_year.min' => 'L\'anno di pubblicazione non può essere precedente al 1900.',
            'publication_year.max' => 'L\'anno di pubblicazione non può essere troppo nel futuro.',
            'cover_image.image' => 'Il file deve essere un\'immagine.',
            'cover_image.mimes' => 'L\'immagine deve essere in formato JPEG, PNG, JPG, GIF o WebP.',
            'cover_image.max' => 'L\'immagine non può superare i 5MB.',
            'plot.max' => 'La trama non può superare i 2000 caratteri.',
            'magazine_id.exists' => 'La rivista selezionata non è valida.',
            'categories.array' => 'Le categorie devono essere un array.',
            'categories.max' => 'Puoi selezionare al massimo 5 categorie.',
            'categories.*.exists' => 'Una o più categorie selezionate non sono valide.'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_published' => $this->boolean('is_published', true)
        ]);
    }
}
