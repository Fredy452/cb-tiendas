<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'between:1,5'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Seleccioná una calificación antes de enviar.',
            'rating.integer' => 'La calificación debe ser un número entero.',
            'rating.between' => 'La calificación debe estar entre 1 y 5 estrellas.',
        ];
    }

    public function attributes(): array
    {
        return [
            'rating' => 'calificación',
        ];
    }
}
