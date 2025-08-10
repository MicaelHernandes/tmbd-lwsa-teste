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
});
