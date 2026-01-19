<?php

namespace App\DTOs\CourseOffering;

class CreateCourseOfferingDTO
{
    public function __construct(
        public readonly int $course_id,
        public readonly string $location,
        public readonly string $start_date,
        public readonly string $end_date,
        public readonly float $price,
        public readonly int $duration_hours,
        public readonly bool $is_generation = false,
        public readonly ?string $generation_name = null,
        public readonly int $min_students = 5,
        public readonly int $max_students = 20,
        public readonly bool $certificate_included = true,
        public readonly string $status = 'programado',
        public readonly ?string $notes = null,
        public readonly bool $is_active = true,
        public readonly array $class_dates = []
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            course_id: $data['course_id'],
            location: $data['location'],
            start_date: $data['start_date'],
            end_date: $data['end_date'],
            price: $data['price'],
            duration_hours: $data['duration_hours'],
            is_generation: $data['is_generation'] ?? false,
            generation_name: $data['generation_name'] ?? null,
            min_students: $data['min_students'] ?? 5,
            max_students: $data['max_students'] ?? 20,
            certificate_included: $data['certificate_included'] ?? true,
            status: $data['status'] ?? 'programado',
            notes: $data['notes'] ?? null,
            is_active: $data['is_active'] ?? true,
            class_dates: $data['class_dates'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
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
        ];
    }
}