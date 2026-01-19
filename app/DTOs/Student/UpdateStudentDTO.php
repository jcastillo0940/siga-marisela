<?php

namespace App\DTOs\Student;

class UpdateStudentDTO
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $email = null,
        public readonly ?string $gender = null,
        public readonly ?string $phone = null,
        public readonly ?string $phone_secondary = null,
        public readonly ?string $birth_date = null,
        public readonly ?string $identification = null,
        public readonly ?string $identification_type = null,
        public readonly ?string $address = null,
        public readonly ?string $city = null,
        public readonly ?string $country = null,
        public readonly ?string $emergency_contact_name = null,
        public readonly ?string $emergency_contact_phone = null,
        public readonly ?string $emergency_contact_relationship = null,
        public readonly ?string $medical_notes = null,
        public readonly ?string $emotional_notes = null,
        public readonly ?string $goals = null,
        public readonly ?string $status = null,
        public readonly ?bool $is_active = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'] ?? null,
            last_name: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
            gender: $data['gender'] ?? null,
            phone: $data['phone'] ?? null,
            phone_secondary: $data['phone_secondary'] ?? null,
            birth_date: $data['birth_date'] ?? null,
            identification: $data['identification'] ?? null,
            identification_type: $data['identification_type'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            country: $data['country'] ?? null,
            emergency_contact_name: $data['emergency_contact_name'] ?? null,
            emergency_contact_phone: $data['emergency_contact_phone'] ?? null,
            emergency_contact_relationship: $data['emergency_contact_relationship'] ?? null,
            medical_notes: $data['medical_notes'] ?? null,
            emotional_notes: $data['emotional_notes'] ?? null,
            goals: $data['goals'] ?? null,
            status: $data['status'] ?? null,
            is_active: $data['is_active'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'phone_secondary' => $this->phone_secondary,
            'birth_date' => $this->birth_date,
            'identification' => $this->identification,
            'identification_type' => $this->identification_type,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'emergency_contact_relationship' => $this->emergency_contact_relationship,
            'medical_notes' => $this->medical_notes,
            'emotional_notes' => $this->emotional_notes,
            'goals' => $this->goals,
            'status' => $this->status,
            'is_active' => $this->is_active,
        ], fn($value) => $value !== null);
    }
}