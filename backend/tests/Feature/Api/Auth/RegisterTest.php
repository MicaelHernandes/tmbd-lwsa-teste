<?php

describe('Teste de validação ao registrar usuario', function (){

    it('deve falhar ao tentar registrar usuario sem passar os campos obrigatórios', function () {
        $response = $this->postJson(route('auth.register'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password']);
    });

});

