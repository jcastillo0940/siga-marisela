<?php

namespace App\Services;

use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function getAllUsers(bool $includeInactive = false): Collection
    {
        $query = User::with('roles');

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->orderBy('name')->get();
    }

    public function getUserById(int $id): User
    {
        return User::with('roles.permissions')->findOrFail($id);
    }

    public function createUser(CreateUserDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            $userData = $dto->toArray();
            $userData['password'] = Hash::make($dto->password);

            $user = User::create($userData);

            // Asignar roles
            if (!empty($dto->roles)) {
                $roles = Role::whereIn('id', $dto->roles)->get();
                $user->roles()->attach($roles);
            }

            // Log de auditoría
            AuditLog::logAction(
                action: 'created',
                model: 'User',
                modelId: $user->id,
                newValues: $user->toArray()
            );

            return $user->load('roles');
        });
    }

    public function updateUser(int $id, UpdateUserDTO $dto): User
    {
        return DB::transaction(function () use ($id, $dto) {
            $user = User::findOrFail($id);
            $oldValues = $user->toArray();

            $updateData = $dto->toArray();
            
            // Hash password si se proporciona
            if (isset($updateData['password'])) {
                $updateData['password'] = Hash::make($updateData['password']);
            }

            $user->update($updateData);

            // Actualizar roles si se proporcionan
            if ($dto->roles !== null) {
                $user->roles()->sync($dto->roles);
            }

            // Log de auditoría
            AuditLog::logAction(
                action: 'updated',
                model: 'User',
                modelId: $user->id,
                oldValues: $oldValues,
                newValues: $user->fresh()->toArray()
            );

            return $user->load('roles');
        });
    }

    public function deleteUser(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);
            $oldValues = $user->toArray();

            // Soft delete
            $deleted = $user->delete();

            // Log de auditoría
            AuditLog::logAction(
                action: 'deleted',
                model: 'User',
                modelId: $id,
                oldValues: $oldValues
            );

            return $deleted;
        });
    }

    public function toggleUserStatus(int $id): User
    {
        return DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);
            $oldStatus = $user->is_active;

            $user->update(['is_active' => !$oldStatus]);

            AuditLog::logAction(
                action: 'status_changed',
                model: 'User',
                modelId: $user->id,
                oldValues: ['is_active' => $oldStatus],
                newValues: ['is_active' => $user->is_active]
            );

            return $user;
        });
    }
}