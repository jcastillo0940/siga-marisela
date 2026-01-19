<?php

namespace App\DTOs\Course;

class CreateCourseDTO
{
    public function __construct(
        public readonly string $code,
        public readonly string $name,
        public readonly int $duration_hours,
        public readonly float $price,
        public readonly ?string $description = null,
        public readonly string $category = 'cocina',
        public readonly string $level = 'basico',
        public readonly ?int $duration_weeks = null,
        public readonly int $max_students = 20,
        public readonly int $min_students = 5,
        public readonly ?string $requirements = null,
        public readonly ?string $objectives = null,
        public readonly ?string $content_outline = null,
        public readonly ?string $materials_included = null,
        public readonly bool $certificate_included = true,
        public readonly bool $is_active = true
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            code: $data['code'],
            name: $data['name'],
            duration_hours: $data['duration_hours'],
            price: $data['price'],
            description: $data['description'] ?? null,
            category: $data['category'] ?? 'cocina',
            level: $data['level'] ?? 'basico',
            duration_weeks: $data['duration_weeks'] ?? null,
            max_students: $data['max_students'] ?? 20,
            min_students: $data['min_students'] ?? 5,
            requirements: $data['requirements'] ?? null,
            objectives: $data['objectives'] ?? null,
            content_outline: $data['content_outline'] ?? null,
            materials_included: $data['materials_included'] ?? null,
            certificate_included: $data['certificate_included'] ?? true,
            is_active: $data['is_active'] ?? true
        );
    }

    public function toArray(): array
    {
        return [
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
        ];
    }
}