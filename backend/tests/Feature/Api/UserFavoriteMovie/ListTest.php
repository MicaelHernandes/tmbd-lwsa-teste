<?php

use App\Models\User;

describe('Validação de Listagem de Filmes Favoritos', function () {

    it('deve retornar 401 se o usuário não estiver autenticado', function () {
        $response = $this->getJson(route('favorite_movies.index'));

        $response->assertStatus(401);
    });

    it('deve retornar 422 se o gênero for inválido', function () {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('favorite_movies.index', [
            'genre' => 'ab'
        ]));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['genre']);
    });

    it('deve retornar 422 se o gênero for muito longo', function () {

        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('favorite_movies.index', [
            'genre' => str_repeat('a', 256)
        ]));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['genre']);
    });
});

describe('Listagem de filmes favoritos', function () {
   it('Deve retornar 200 e uma arra de filmes favoritos', function () {
       $user = User::factory()->create();
       $this->actingAs($user);

       $response = $this->getJson(route('favorite_movies.index'));

       $response->assertStatus(200)
                ->assertJson([]);
   });
});
