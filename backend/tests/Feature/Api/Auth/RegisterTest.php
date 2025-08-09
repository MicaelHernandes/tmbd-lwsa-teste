<?php

describe('Teste de validação ao registrar usuario', function (){

    it('deve falhar ao tentar registrar usuario sem passar os campos obrigatórios', function () {
        $response = $this->postJson(route('auth.register'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name', 'email', 'password', 'password_confirmation']);
    });

    it('deve falhar ao tentar registrar usuario com email inválido', function () {

        $correctPassword = fake()->password(8, 20);

        $response = $this->postJson(route('auth.register'), [
            'name' => fake()->name(),
            'email' => 'invalid-email',
            'password' => $correctPassword,
            'password_confirmation' => $correctPassword,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    });

    it('deve falhar ao tentar registrar usuario com senha muito curta', function () {
        $response = $this->postJson(route('auth.register'), [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    });

    it('deve falhar ao tentar registrar usuario com senha não confirmada', function () {
        $correctPassword = fake()->password(8, 20);

        $response = $this->postJson(route('auth.register'), [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => $correctPassword,
            'password_confirmation' => 'different_password',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    });
});

describe('Teste de registro de usuário', function () {

    it('deve registrar um novo usuário com dados válidos', function () {
        $correctPassword = fake()->password(8, 20);

        $response = $this->postJson(route('auth.register'), [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => $correctPassword,
            'password_confirmation' => $correctPassword,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'user']);
    });
});

