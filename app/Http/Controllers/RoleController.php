<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\PermissionService;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    // ❌ ELIMINAR el constructor con middleware
    
    public function __construct(
        private readonly RoleService $roleService,
        private readonly PermissionService $permissionService
    ) {}

    public function index(): View
    {
        $roles = $this->roleService->getAllRoles();
        return view('roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = $this->permissionService->getAllPermissions()
            ->groupBy('module');
        
        return view('roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());

            return redirect()
                ->route('roles.index')
                ->with('success', "Rol {$role->name} creado exitosamente");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al crear rol: ' . $e->getMessage());
        }
    }

    public function show(int $id): View
    {
        $role = $this->roleService->getRoleById($id);
        return view('roles.show', compact('role'));
    }

    public function edit(int $id): View
    {
        $role = $this->roleService->getRoleById($id);
        $permissions = $this->permissionService->getAllPermissions()
            ->groupBy('module');
        
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(UpdateRoleRequest $request, int $id): RedirectResponse
    {
        try {
            $role = $this->roleService->updateRole($id, $request->validated());

            return redirect()
                ->route('roles.show', $role->id)
                ->with('success', "Rol {$role->name} actualizado exitosamente");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar rol: ' . $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->roleService->deleteRole($id);

            return redirect()
                ->route('roles.index')
                ->with('success', 'Rol eliminado exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage());
        }
    }
}