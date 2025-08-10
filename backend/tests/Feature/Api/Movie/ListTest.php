<?php

describe('Validação de listagem de filmes', function () {
    it('deve retornar erro quando o parâmetro "page" for inválido', function () {
        $response = $this->getJson(route('movies.index', ['page' => 'invalid']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page']);
    });

    it('deve retornar erro quando o parâmetro "page" for menor que 1', function () {
        $response = $this->getJson(route('movies.index', ['page' => 0]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['page']);
    });

    it('deve retornar erro quando o parâmetro "language" não tiver 5 caracteres', function () {
        $response = $this->getJson(route('movies.index', ['language' => 'pt']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language']);
    });

    it('deve retornar erro quando o parâmetro "language" for inválido', function () {
        $response = $this->getJson(route('movies.index', ['language' => 'xx-XX']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language']);
    });

    it('deve retornar erro quando o parâmetro "language" não seguir o padrão', function () {
        $response = $this->getJson(route('movies.index', ['language' => 'ptbr1']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['language']);
    });
});

describe('Listagem de filmes', function () {
    it('deve retornar resultados quando não houver parâmetros', function () {
        $response = $this->getJson(route('movies.index'));

        $response->assertStatus(200);
    });

    it('deve retornar resultados quando os parâmetros forem válidos', function () {
        $response = $this->getJson(route('movies.index', [
            'page' => 1,
            'language' => 'pt-BR'
        ]));

        $response->assertStatus(200);
    });

    it('deve retornar resultados com language em inglês', function () {
        $response = $this->getJson(route('movies.index', ['language' => 'en-US']));

        $response->assertStatus(200);
    });
});
