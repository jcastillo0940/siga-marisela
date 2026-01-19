<?php

namespace App\Http\Controllers\Api;

use App\DTOs\Auth\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthApiController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginDTO::fromRequest($request->validated());
            $result = $this->authService->login($dto);

            return response()->json([
                'success' => true,
                'message' => 'Login exitoso',
                'data' => [
                    'user' => new UserResource($result['user']),
                    'token' => $result['token'],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout exitoso',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user()->load('roles.permissions')),
        ]);
    }
}