<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $textFields = [
            'name',
            'phone',
            'email',
            'website',
            'facebook_url',
            'instagram_url',
            'tiktok_url',
            'address',
            'latitude',
            'longitude',
            'description',
        ];
        $urlFields = ['website', 'facebook_url', 'instagram_url', 'tiktok_url'];
        $normalized = [];

        foreach ($textFields as $field) {
            $value = $this->input($field);

            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);

            if (in_array($field, $urlFields, true)
                && $value !== ''
                && ! Str::startsWith($value, ['http://', 'https://'])) {
                $value = 'https://' . ltrim($value, '/');
            }

            $normalized[$field] = $value === '' && ! in_array($field, ['name', 'phone', 'address', 'description'], true)
                ? null
                : $value;
        }

        $this->merge($normalized);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'not_regex:/[<>]/'],
            'category_id' => [
                'required',
                'integer',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('is_active', true)),
            ],
            'phone' => ['required', 'string', 'max:25', 'regex:/^[0-9+()\s.\-]+$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'facebook_url' => ['nullable', 'url:http,https', 'max:255'],
            'instagram_url' => ['nullable', 'url:http,https', 'max:255'],
            'tiktok_url' => ['nullable', 'url:http,https', 'max:255'],
            'address' => ['required', 'string', 'max:255', 'not_regex:/[<>]/'],
            'latitude' => ['nullable', 'required_with:longitude', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'required_with:latitude', 'numeric', 'between:-180,180'],
            'description' => ['required', 'string', 'max:1200', 'not_regex:/[<>]/'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:1048'],
            'cover_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2096'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'required_with' => 'El campo :attribute es obligatorio cuando se completa :values.',
            'latitude.required_with' => 'La latitud es obligatoria cuando se completa la longitud.',
            'longitude.required_with' => 'La longitud es obligatoria cuando se completa la latitud.',
            'string' => 'El campo :attribute debe ser texto.',
            'integer' => 'El campo :attribute debe ser un número entero.',
            'numeric' => 'El campo :attribute debe ser un número.',
            'email' => 'Ingresá una dirección de correo electrónico válida.',
            'url' => 'Ingresá una URL válida en el campo :attribute.',
            'max.string' => 'El campo :attribute no debe superar los :max caracteres.',
            'max' => 'El archivo :attribute no debe superar los :max KB.',
            'between.numeric' => 'El campo :attribute debe estar entre :min y :max.',
            'exists' => 'La categoría seleccionada no está disponible.',
            'file' => 'El campo :attribute debe ser un archivo válido.',
            'mimes' => 'El archivo :attribute debe ser de tipo: :values.',
            'regex' => 'El formato del campo :attribute no es válido.',
            'not_regex' => 'El campo :attribute solo puede contener texto plano, sin etiquetas HTML.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del negocio',
            'category_id' => 'rubro o categoría',
            'phone' => 'teléfono de contacto',
            'email' => 'correo electrónico',
            'website' => 'sitio web o catálogo',
            'facebook_url' => 'Facebook',
            'instagram_url' => 'Instagram',
            'tiktok_url' => 'TikTok',
            'address' => 'dirección o zona de referencia',
            'latitude' => 'latitud',
            'longitude' => 'longitud',
            'description' => 'descripción',
            'logo' => 'logo',
            'cover_image' => 'imagen de portada',
        ];
    }
}
