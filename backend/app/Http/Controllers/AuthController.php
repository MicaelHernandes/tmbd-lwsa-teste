<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterUserRequest;
use App\Services\TMDB\Auth\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private readonly UserService $registerUserService;

    function __construct(UserService $service)
    {
        $this->registerUserService = $service;
    }

    public function register(AuthRegisterUserRequest $request)
    {
        try{
            $user = $this->registerUserService->register($request->validated());
            return response()->json(['message' => 'Usuario registrado com sucesso!', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Ocorreu um erro durante a solicitação!', 'error' => $e->getMessage()], 500);
        }
    }

    public function login(AuthLoginRequest $request)
    {
        try {
            $token = $this->registerUserService->login($request->validated());
            return response()->json(['message' => 'Login realizado com sucesso!', 'token' => $token], 200);
        }catch (ValidationException $e) {
            return response()->json(['message' => 'Credenciais inválidas!', 'errors' => $e->errors()], 401);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocorreu um erro durante a solicitação!', 'error' => $th->getMessage()], 500);
        }
    }
}
