<?php

describe('Validação de busca de filmes', function () {
    it('deve retornar erro quando o parâmetro "query" estiver ausente', function () {
        $response = $this->getJson(route('movies.search'));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['query']);
    });

    it('deve retornar erro quando o parâmetro "query" for vazio', function () {
        $response = $this->getJson(route('movies.search', ['query' => '']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['query']);
    });

    it('deve retornar erro quando o parâmetro "query" for muito curto', function () {
        $response = $this->getJson(route('movies.search', ['query' => 'a']));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['query']);
    });

    it('deve retornar erro quando o parâmetro "query" for muito longo', function () {
        $longQuery = str_repeat('a', 256);
        $response = $this->getJson(route('movies.search', ['query' => $longQuery]));

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['query']);
    });
});
