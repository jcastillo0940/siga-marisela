<?php

namespace App\DTOs\Lead;

class CreateLeadDTO
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $phone,
        public readonly ?string $phone_secondary = null,
        public readonly string $source = 'web',
        public readonly ?string $source_detail = null,
        public readonly string $status = 'nuevo',
        public readonly ?string $notes = null,
        public readonly ?string $interests = null,
        public readonly ?string $follow_up_date = null,
        public readonly ?int $assigned_to = null,

        // --- Campos integrados del CSV, Registro Público y Pago ---
        public readonly ?string $student_photo = null,
        public readonly ?string $who_fills_form = null,
        public readonly ?string $age = null,
        public readonly ?string $birth_date_text = null,
        public readonly ?string $address_full = null,
        public readonly ?string $parent_name = null, // Agregado para corregir error 500
        public readonly ?string $parent_relationship = null, // Agregado para corregir error 500
        public readonly ?string $parent_phone = null,
        public readonly ?string $occupation = null,
        public readonly ?string $parent_occupation = null,
        public readonly ?bool $has_previous_experience = false,
        public readonly ?string $previous_experience_detail = null,
        public readonly ?string $motivation = null,
        public readonly ?string $social_media_handle = null,
        public readonly ?string $medical_notes_lead = null,
        public readonly ?string $payment_receipt_path = null,
        public readonly ?string $payment_status = 'pending',
        public readonly ?int $course_offering_id = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            email: $data['email'],
            phone: $data['phone'],
            phone_secondary: $data['phone_secondary'] ?? null,
            source: $data['source'] ?? 'web',
            source_detail: $data['source_detail'] ?? null,
            status: $data['status'] ?? 'nuevo',
            notes: $data['notes'] ?? null,
            interests: $data['interests'] ?? null,
            follow_up_date: $data['follow_up_date'] ?? null,
            assigned_to: $data['assigned_to'] ?? null,

            // Mapeo de campos adicionales
            student_photo: $data['student_photo'] ?? null,
            who_fills_form: $data['who_fills_form'] ?? null,
            age: $data['age'] ?? null,
            birth_date_text: $data['birth_date_text'] ?? null,
            address_full: $data['address_full'] ?? null,
            parent_name: $data['parent_name'] ?? null,
            parent_relationship: $data['parent_relationship'] ?? null,
            parent_phone: $data['parent_phone'] ?? null,
            occupation: $data['occupation'] ?? null,
            parent_occupation: $data['parent_occupation'] ?? null,
            has_previous_experience: filter_var($data['has_previous_experience'] ?? false, FILTER_VALIDATE_BOOLEAN),
            previous_experience_detail: $data['previous_experience_detail'] ?? null,
            motivation: $data['motivation'] ?? null,
            social_media_handle: $data['social_media_handle'] ?? null,
            medical_notes_lead: $data['medical_notes_lead'] ?? null,
            payment_receipt_path: $data['payment_receipt_path'] ?? null,
            payment_status: $data['payment_status'] ?? 'pending',
            course_offering_id: isset($data['course_offering_id']) ? (int)$data['course_offering_id'] : null
        );
    }

    public function toArray(): array
    {
        return [
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
            
            // Campos extendidos
            'student_photo' => $this->student_photo,
            'who_fills_form' => $this->who_fills_form,
            'age' => $this->age,
            'birth_date_text' => $this->birth_date_text,
            'address_full' => $this->address_full,
            'parent_name' => $this->parent_name,
            'parent_relationship' => $this->parent_relationship,
            'parent_phone' => $this->parent_phone,
            'occupation' => $this->occupation,
            'parent_occupation' => $this->parent_occupation,
            'has_previous_experience' => $this->has_previous_experience,
            'previous_experience_detail' => $this->previous_experience_detail,
            'motivation' => $this->motivation,
            'social_media_handle' => $this->social_media_handle,
            'medical_notes_lead' => $this->medical_notes_lead,
            'payment_receipt_path' => $this->payment_receipt_path,
            'payment_status' => $this->payment_status,
            'course_offering_id' => $this->course_offering_id,
        ];
    }
}