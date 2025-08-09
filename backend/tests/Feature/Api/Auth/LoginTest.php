<?php

use App\Models\User;

describe('Test de validação ao fazer login', function () {

    it('deve falhar ao tentar fazer login sem passar os campos obrigatórios', function () {
        $response = $this->postJson(route('auth.login'), []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email', 'password']);
    });

    it('deve falhar ao tentar fazer login com email inválido', function () {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'invalid-email',
            'password' => 'validPassword123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    });

    it('deve falhar ao mandar senha curta', function () {
        $response = $this->postJson(route('auth.login'), [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'short',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['password']);
    });
});

describe('Test de autenticação ao fazer login', function () {

    it('deve autenticar usuário com credenciais válidas', function () {
        $user = User::factory()->create([
            'password' => bcrypt('validPassword123'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'validPassword123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['token']);
    });

    it('deve falhar ao tentar fazer login com senha incorreta', function () {
        $user = User::factory()->create([
            'password' => bcrypt('validPassword123'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'wrongPassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson(['message' => 'Credenciais inválidas!']);
    });
});
