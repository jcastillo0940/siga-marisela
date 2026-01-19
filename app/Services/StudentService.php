<?php

namespace App\Services;

use App\DTOs\Student\CreateStudentDTO;
use App\DTOs\Student\UpdateStudentDTO;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function getAllStudents(bool $includeInactive = false): Collection
    {
        $query = Student::query();

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getStudentById(int $id): ?Student
    {
        return Student::with([
            'enrollments.courseOffering.course',
            'enrollments.paymentPlan'
        ])->find($id);
    }

    public function createStudent(CreateStudentDTO $dto): Student
    {
        return DB::transaction(function () use ($dto) {
            return Student::create($dto->toArray());
        });
    }

    public function updateStudent(int $id, UpdateStudentDTO $dto): Student
    {
        return DB::transaction(function () use ($id, $dto) {
            $student = Student::findOrFail($id);
            $student->update($dto->toArray());
            return $student->fresh();
        });
    }

    public function deleteStudent(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $student = Student::findOrFail($id);
            return $student->delete();
        });
    }

    public function toggleStudentStatus(int $id): Student
    {
        return DB::transaction(function () use ($id) {
            $student = Student::findOrFail($id);
            $student->is_active = !$student->is_active;
            $student->save();
            return $student;
        });
    }
}