<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FavoriteMovieDeleteRequest extends FormRequest
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
            'movie_id' => ['required', 'integer', 'exists:user_favorite_movies,movie_id,user_id,' . Auth::id()],
        ];
    }

    public function messages(): array
    {
        return [
            'movie_id.required' => 'O campo movie_id é obrigatório.',
            'movie_id.integer' => 'O campo movie_id deve ser um número inteiro.',
            'movie_id.exists' => 'O filme não está na lista de favoritos do usuário autenticado.',
        ];
    }
}
