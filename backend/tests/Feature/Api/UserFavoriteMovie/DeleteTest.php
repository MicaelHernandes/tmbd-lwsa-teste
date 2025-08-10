<?php

describe('Verificação de campos obrigatórios ao deletar um filme favorito', function () {
    it('Deve retornar erro quando usuario não autenticado tenta deletar um filme favorito', function () {
        $this->deleteJson(route('favorite_movies.destroy', [
            'movie_id' => 1,
        ]))->assertUnauthorized();
    });
});
