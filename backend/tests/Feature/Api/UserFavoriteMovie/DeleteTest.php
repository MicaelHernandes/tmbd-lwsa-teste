<?php

use App\Models\User;

describe('Verificação de campos obrigatórios ao deletar um filme favorito', function () {
    it('Deve retornar erro quando usuario não autenticado tenta deletar um filme favorito', function () {
        $this->deleteJson(route('favorite_movies.destroy', [
            'movie_id' => 1,
        ]))->assertUnauthorized();
    });

    it('Deve retornar erro quando o ID do filme não é fornecido', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')
            ->deleteJson(route('favorite_movies.destroy', [
                'movie_id' => '',
            ]))
            ->assertUnprocessable()->assertJsonValidationErrors(['movie_id']);
    });

    it('Deve retornar erro quando o ID do filme não é um número inteiro', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')
            ->deleteJson(route('favorite_movies.destroy', [
                'movie_id' => 'abc',
            ]))
            ->assertUnprocessable()->assertJsonValidationErrors(['movie_id']);
    });

    it('Deve retornar erro quando o filme não está na lista de favoritos do usuário autenticado', function () {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum')
            ->deleteJson(route('favorite_movies.destroy', [
                'movie_id' => 9999,
            ]))
            ->assertUnprocessable()->assertJsonValidationErrors(['movie_id']);
    });
});
