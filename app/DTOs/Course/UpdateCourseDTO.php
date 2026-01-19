<?php

namespace App\DTOs\Course;

class UpdateCourseDTO
{
    public function __construct(
        public readonly ?string $code = null,
        public readonly ?string $name = null,
        public readonly ?int $duration_hours = null,
        public readonly ?float $price = null,
        public readonly ?string $description = null,
        public readonly ?string $category = null,
        public readonly ?string $level = null,
        public readonly ?int $duration_weeks = null,
        public readonly ?int $max_students = null,
        public readonly ?int $min_students = null,
        public readonly ?string $requirements = null,
        public readonly ?string $objectives = null,
        public readonly ?string $content_outline = null,
        public readonly ?string $materials_included = null,
        public readonly ?bool $certificate_included = null,
        public readonly ?bool $is_active = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            code: $data['code'] ?? null,
            name: $data['name'] ?? null,
            duration_hours: $data['duration_hours'] ?? null,
            price: $data['price'] ?? null,
            description: $data['description'] ?? null,
            category: $data['category'] ?? null,
            level: $data['level'] ?? null,
            duration_weeks: $data['duration_weeks'] ?? null,
            max_students: $data['max_students'] ?? null,
            min_students: $data['min_students'] ?? null,
            requirements: $data['requirements'] ?? null,
            objectives: $data['objectives'] ?? null,
            content_outline: $data['content_outline'] ?? null,
            materials_included: $data['materials_included'] ?? null,
            certificate_included: $data['certificate_included'] ?? null,
            is_active: $data['is_active'] ?? null
        );
    }

    public function toArray(): array
    {
        return array_filter([
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'category' => $this->category,
            'level' => $this->level,
            'duration_hours' => $this->duration_hours,
            'duration_weeks' => $this->duration_weeks,
            'price' => $this->price,
            'max_students' => $this->max_students,
            'min_students' => $this->min_students,
            'requirements' => $this->requirements,
            'objectives' => $this->objectives,
            'content_outline' => $this->content_outline,
            'materials_included' => $this->materials_included,
            'certificate_included' => $this->certificate_included,
            'is_active' => $this->is_active,
        ], fn($value) => $value !== null);
    }
}