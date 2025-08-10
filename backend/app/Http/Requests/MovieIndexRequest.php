<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieIndexRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'integer|min:1|nullable',
            'language' => 'string|size:5|in:pt-BR,en-US,es-ES,fr-FR,de-DE|nullable',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'page.integer' => 'O campo "page" deve ser um número inteiro.',
            'page.min' => 'O campo "page" deve ser pelo menos 1.',
            'language.string' => 'O campo "language" deve ser uma string.',
            'language.size' => 'O campo "language" deve ter exatamente 5 caracteres.',
            'language.in' => 'O campo "language" deve ser um código de idioma válido (pt-BR, en-US, es-ES, fr-FR, de-DE).',
        ];
    }
}
