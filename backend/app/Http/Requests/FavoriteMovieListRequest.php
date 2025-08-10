<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FavoriteMovieListRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'genre' => 'nullable|string|min:3|max:255',
        ];
    }

    /**
     * Get the validation messages for the request.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'genre.string' => 'O gênero deve ser uma string.',
            'genre.min' => 'O gênero deve ter pelo menos 3 caracteres.',
            'genre.max' => 'O gênero não pode ter mais de 255 caracteres.',
        ];
    }
}
