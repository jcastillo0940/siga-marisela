<?php

namespace App\DTOs\Enrollment;

class UpdateEnrollmentDTO
{
    public function __construct(
        public readonly ?int $student_id = null,
        public readonly ?int $course_offering_id = null,
        public readonly ?string $enrollment_date = null,
        public readonly ?float $price_paid = null,
        public readonly ?string $status = null,
        public readonly ?float $discount = null,
        public readonly ?string $notes = null,
        public readonly ?bool $certificate_issued = null,
        public readonly ?string $certificate_issued_at = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            student_id: $data['student_id'] ?? null,
            course_offering_id: $data['course_offering_id'] ?? null,
            enrollment_date: $data['enrollment_date'] ?? null,
            price_paid: $data['price_paid'] ?? null,
            status: $data['status'] ?? null,
            discount: $data['discount'] ?? null,
            notes: $data['notes'] ?? null,
            certificate_issued: $data['certificate_issued'] ?? null,
            certificate_issued_at: $data['certificate_issued_at'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'student_id' => $this->student_id,
            'course_offering_id' => $this->course_offering_id,
            'enrollment_date' => $this->enrollment_date,
            'price_paid' => $this->price_paid,
            'status' => $this->status,
            'discount' => $this->discount,
            'notes' => $this->notes,
            'certificate_issued' => $this->certificate_issued,
            'certificate_issued_at' => $this->certificate_issued_at,
        ], fn($value) => $value !== null);
    }
}