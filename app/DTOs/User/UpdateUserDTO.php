<?php

namespace App\DTOs\User;

class UpdateUserDTO
{
    public function __construct(
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?string $gender = null,
        public readonly ?string $password = null,
        public readonly ?string $phone = null,
        public readonly ?string $position = null,
        public readonly ?array $roles = null,
        public readonly ?bool $isActive = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            gender: $data['gender'] ?? null,
            password: $data['password'] ?? null,
            phone: $data['phone'] ?? null,
            position: $data['position'] ?? null,
            roles: $data['roles'] ?? null,
            isActive: $data['is_active'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'password' => $this->password,
            'phone' => $this->phone,
            'position' => $this->position,
            'is_active' => $this->isActive,
        ], fn($value) => $value !== null);
    }
}