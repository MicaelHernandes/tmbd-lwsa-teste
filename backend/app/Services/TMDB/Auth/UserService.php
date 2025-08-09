<?php

namespace App\Services\TMDB\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

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
}
