<?php

namespace App\Http\Controllers\Auth;

use App\DTOs\Auth\LoginDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $dto = LoginDTO::fromRequest($request->validated());
            $result = $this->authService->login($dto);

            return redirect()
                ->intended(route('dashboard'))
                ->with('success', '¡Bienvenida de vuelta!');

        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'Sesión cerrada exitosamente');
    }
}