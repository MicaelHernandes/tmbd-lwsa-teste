<?php

use App\Models\User;

describe('Verificação de validações para realizar operação de criação' , function (){
   it('Usuario não autenticado não pode criar favorito', function () {
       $response = $this->postJson(route('favorite_movies.store'), [
           'movie_id' => 1,
       ]);

       $response->assertUnauthorized();
   });

   it('Requisição deve conter o campo movie_id', function () {
       $user = User::factory()->create();
       $response = $this->actingAs($user)->postJson(route('favorite_movies.store'));

       $response->assertUnprocessable()
                ->assertJsonValidationErrors(['movie_id']);
   });

   it('Campo movie_id deve ser um número inteiro', function () {
       $user = User::factory()->create();
       $response = $this->actingAs($user)->postJson(route('favorite_movies.store'), [
           'movie_id' => 'abc',
       ]);

       $response->assertUnprocessable()
                ->assertJsonValidationErrors(['movie_id']);
   });

    it('Campo movie_id deve ser maior que zero', function () {
         $user = User::factory()->create();
         $response = $this->actingAs($user)->postJson(route('favorite_movies.store'), [
              'movie_id' => 0,
         ]);

         $response->assertUnprocessable()
                 ->assertJsonValidationErrors(['movie_id']);
    });
});

describe('Criação de favorito', function () {
    it('Usuário autenticado pode criar favorito', function () {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson(route('favorite_movies.store'), [
            'movie_id' => 1,
        ]);

        $response->assertCreated()
                 ->assertJson([
                     'message' => 'Filme adicionado aos favoritos com sucesso.',
                 ]);
    });

    it('Usuário não pode adicionar o mesmo filme mais de uma vez aos favoritos', function () {
        $user = User::factory()->create();
        $this->actingAs($user)->postJson(route('favorite_movies.store'), [
            'movie_id' => 1,
        ]);

        $response = $this->actingAs($user)->postJson(route('favorite_movies.store'), [
            'movie_id' => 1,
        ]);

        $response->assertUnprocessable()
                 ->assertJson([
                     'message' => 'Este filme já está adicionado aos favoritos.',
                 ]);
    });
});
