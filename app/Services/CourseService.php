<?php

namespace App\Services;

use App\DTOs\Course\CreateCourseDTO;
use App\DTOs\Course\UpdateCourseDTO;
use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CourseService
{
    public function getAllCourses(bool $includeInactive = false): Collection
    {
        $query = Course::query();

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getCourseById(int $id): ?Course
    {
        return Course::with(['offerings.enrollments.student'])->find($id);
    }

    public function createCourse(CreateCourseDTO $dto): Course
    {
        return DB::transaction(function () use ($dto) {
            return Course::create($dto->toArray());
        });
    }

    public function updateCourse(int $id, UpdateCourseDTO $dto): Course
    {
        return DB::transaction(function () use ($id, $dto) {
            $course = Course::findOrFail($id);
            $course->update($dto->toArray());
            return $course->fresh();
        });
    }

    public function deleteCourse(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $course = Course::findOrFail($id);
            return $course->delete();
        });
    }

    public function toggleCourseStatus(int $id): Course
    {
        return DB::transaction(function () use ($id) {
            $course = Course::findOrFail($id);
            $course->is_active = !$course->is_active;
            $course->save();
            return $course;
        });
    }
}