<?php

describe('Verificação de autenticação para realizar operação de criação' , function (){
   it('Usuario não autenticado não pode criar favorito', function () {
       $response = $this->postJson(route('favorite_movies.store'), [
           'movie_id' => 1,
       ]);

       $response->assertUnauthorized();
   });
});
