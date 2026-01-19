<?php

namespace App\Http\Controllers;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    // ❌ ELIMINAR el constructor con middleware
    
    public function __construct(
        private readonly UserService $userService,
        private readonly RoleService $roleService
    ) {}

    public function index(Request $request): View
    {
        $includeInactive = $request->boolean('include_inactive');
        $users = $this->userService->getAllUsers($includeInactive);

        return view('users.index', compact('users', 'includeInactive'));
    }

    public function create(): View
    {
        $roles = $this->roleService->getAllRoles();
        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $dto = CreateUserDTO::fromRequest($request->validated());
            $user = $this->userService->createUser($dto);

            return redirect()
                ->route('users.index')
                ->with('success', "Usuario {$user->name} creado exitosamente");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function show(int $id): View
    {
        $user = $this->userService->getUserById($id);
        return view('users.show', compact('user'));
    }

    public function edit(int $id): View
    {
        $user = $this->userService->getUserById($id);
        $roles = $this->roleService->getAllRoles();
        
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, int $id): RedirectResponse
    {
        try {
            $dto = UpdateUserDTO::fromRequest($request->validated());
            $user = $this->userService->updateUser($id, $dto);

            return redirect()
                ->route('users.show', $user->id)
                ->with('success', "Usuario {$user->name} actualizado exitosamente");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->userService->deleteUser($id);

            return redirect()
                ->route('users.index')
                ->with('success', 'Usuario eliminado exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id): RedirectResponse
    {
        try {
            $user = $this->userService->toggleUserStatus($id);
            $status = $user->is_active ? 'activado' : 'desactivado';

            return back()
                ->with('success', "Usuario {$status} exitosamente");

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
    }
}