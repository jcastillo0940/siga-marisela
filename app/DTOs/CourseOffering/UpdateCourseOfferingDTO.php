<?php

namespace App\DTOs\CourseOffering;

class UpdateCourseOfferingDTO
{
    public function __construct(
        public readonly ?int $course_id = null,
        public readonly ?string $location = null,
        public readonly ?string $start_date = null,
        public readonly ?string $end_date = null,
        public readonly ?float $price = null,
        public readonly ?int $duration_hours = null,
        public readonly ?bool $is_generation = null,
        public readonly ?string $generation_name = null,
        public readonly ?int $min_students = null,
        public readonly ?int $max_students = null,
        public readonly ?bool $certificate_included = null,
        public readonly ?string $status = null,
        public readonly ?string $notes = null,
        public readonly ?bool $is_active = null,
        public readonly ?array $class_dates = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            course_id: $data['course_id'] ?? null,
            location: $data['location'] ?? null,
            start_date: $data['start_date'] ?? null,
            end_date: $data['end_date'] ?? null,
            price: $data['price'] ?? null,
            duration_hours: $data['duration_hours'] ?? null,
            is_generation: $data['is_generation'] ?? null,
            generation_name: $data['generation_name'] ?? null,
            min_students: $data['min_students'] ?? null,
            max_students: $data['max_students'] ?? null,
            certificate_included: $data['certificate_included'] ?? null,
            status: $data['status'] ?? null,
            notes: $data['notes'] ?? null,
            is_active: $data['is_active'] ?? null,
            class_dates: $data['class_dates'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'course_id' => $this->course_id,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'price' => $this->price,
            'duration_hours' => $this->duration_hours,
            'is_generation' => $this->is_generation,
            'generation_name' => $this->generation_name,
            'min_students' => $this->min_students,
            'max_students' => $this->max_students,
            'certificate_included' => $this->certificate_included,
            'status' => $this->status,
            'notes' => $this->notes,
            'is_active' => $this->is_active,
        ], fn($value) => $value !== null);
    }
}