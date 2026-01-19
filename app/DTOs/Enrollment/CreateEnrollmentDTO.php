<?php

namespace App\DTOs\Enrollment;

class CreateEnrollmentDTO
{
    public function __construct(
        public readonly int $student_id,
        public readonly int $course_offering_id,
        public readonly string $enrollment_date,
        public readonly float $price_paid,
        public readonly string $status = 'inscrito',
        public readonly float $discount = 0,
        public readonly ?string $notes = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            student_id: $data['student_id'],
            course_offering_id: $data['course_offering_id'],
            enrollment_date: $data['enrollment_date'],
            price_paid: $data['price_paid'],
            status: $data['status'] ?? 'inscrito',
            discount: $data['discount'] ?? 0,
            notes: $data['notes'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'student_id' => $this->student_id,
            'course_offering_id' => $this->course_offering_id,
            'enrollment_date' => $this->enrollment_date,
            'price_paid' => $this->price_paid,
            'status' => $this->status,
            'discount' => $this->discount,
            'notes' => $this->notes,
        ];
    }
}