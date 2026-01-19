<?php

namespace App\DTOs\Lead;

class UpdateLeadDTO
{
    public function __construct(
        public readonly ?string $first_name = null,
        public readonly ?string $last_name = null,
        public readonly ?string $email = null,
        public readonly ?string $phone = null,
        public readonly ?string $phone_secondary = null,
        public readonly ?string $source = null,
        public readonly ?string $source_detail = null,
        public readonly ?string $status = null,
        public readonly ?string $notes = null,
        public readonly ?string $interests = null,
        public readonly ?string $follow_up_date = null,
        public readonly ?int $assigned_to = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'] ?? null,
            last_name: $data['last_name'] ?? null,
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            phone_secondary: $data['phone_secondary'] ?? null,
            source: $data['source'] ?? null,
            source_detail: $data['source_detail'] ?? null,
            status: $data['status'] ?? null,
            notes: $data['notes'] ?? null,
            interests: $data['interests'] ?? null,
            follow_up_date: $data['follow_up_date'] ?? null,
            assigned_to: $data['assigned_to'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'phone_secondary' => $this->phone_secondary,
            'source' => $this->source,
            'source_detail' => $this->source_detail,
            'status' => $this->status,
            'notes' => $this->notes,
            'interests' => $this->interests,
            'follow_up_date' => $this->follow_up_date,
            'assigned_to' => $this->assigned_to,
        ], fn($value) => $value !== null);
    }
}