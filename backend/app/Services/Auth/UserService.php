<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class UserService
{
    private readonly Model $model;

    function __construct(User $model)
    {
        $this->model = $model;
    }

    public function register(array $data): User
    {
        $user = $this->model->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        return $user;
    }

    public function login(array $data): string
    {
        if (!auth()->attempt($data)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais informadas não são válidas.'],
            ]);
        }

        return auth()->user()->createToken('auth_token')->plainTextToken;
    }
}
