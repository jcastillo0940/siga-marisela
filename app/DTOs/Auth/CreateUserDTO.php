<?php

namespace App\DTOs\User;

class CreateUserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $gender,
        public readonly string $password,
        public readonly ?string $phone = null,
        public readonly ?string $position = null,
        public readonly array $roles = [],
        public readonly bool $isActive = true
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            gender: $data['gender'] ?? 'male',
            password: $data['password'],
            phone: $data['phone'] ?? null,
            position: $data['position'] ?? null,
            roles: $data['roles'] ?? [],
            isActive: $data['is_active'] ?? true
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'password' => $this->password,
            'phone' => $this->phone,
            'position' => $this->position,
            'is_active' => $this->isActive,
        ];
    }
}