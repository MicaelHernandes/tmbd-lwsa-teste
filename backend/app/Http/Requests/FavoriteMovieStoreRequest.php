<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FavoriteMovieStoreRequest extends FormRequest
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
            'movie_id' => 'required|integer|min:1|unique:user_favorite_movies,movie_id,NULL,id,user_id,' . Auth::id(),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */

    public function messages(): array
    {
        return [
            'movie_id.required' => 'O campo movie_id é obrigatório.',
            'movie_id.integer' => 'O campo movie_id deve ser um número inteiro.',
            'movie_id.min' => 'O campo movie_id deve ser maior que zero.',
            'movie_id.unique' => 'Este filme já está adicionado aos favoritos.',
        ];
    }
}
