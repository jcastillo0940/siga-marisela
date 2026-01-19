<?php

namespace App\Services;

use App\DTOs\CourseOffering\CreateCourseOfferingDTO;
use App\DTOs\CourseOffering\UpdateCourseOfferingDTO;
use App\Models\CourseOffering;
use App\Models\CourseOfferingDate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CourseOfferingService
{
    public function getAllOfferings(bool $includeInactive = false): Collection
    {
        $query = CourseOffering::with(['course', 'dates'])->withCount('enrollments');

        if (!$includeInactive) {
            $query->where('is_active', true);
        }

        return $query->orderBy('start_date', 'desc')->get();
    }

    public function getOfferingById(int $id): ?CourseOffering
    {
        return CourseOffering::with(['course', 'dates', 'enrollments.student'])->find($id);
    }

    public function createOffering(CreateCourseOfferingDTO $dto): CourseOffering
    {
        return DB::transaction(function () use ($dto) {
            $offering = CourseOffering::create($dto->toArray());

            // Crear las fechas de clase
            if (!empty($dto->class_dates)) {
                foreach ($dto->class_dates as $dateData) {
                    CourseOfferingDate::create([
                        'course_offering_id' => $offering->id,
                        'class_date' => $dateData['date'],
                        'start_time' => $dateData['start_time'] ?? null,
                        'end_time' => $dateData['end_time'] ?? null,
                        'notes' => $dateData['notes'] ?? null,
                    ]);
                }
            }

            return $offering->fresh(['dates']);
        });
    }

    public function updateOffering(int $id, UpdateCourseOfferingDTO $dto): CourseOffering
    {
        return DB::transaction(function () use ($id, $dto) {
            $offering = CourseOffering::findOrFail($id);
            $offering->update($dto->toArray());

            // Actualizar fechas si se proporcionan
            if ($dto->class_dates !== null) {
                // Eliminar fechas existentes
                $offering->dates()->delete();

                // Crear nuevas fechas
                foreach ($dto->class_dates as $dateData) {
                    CourseOfferingDate::create([
                        'course_offering_id' => $offering->id,
                        'class_date' => $dateData['date'],
                        'start_time' => $dateData['start_time'] ?? null,
                        'end_time' => $dateData['end_time'] ?? null,
                        'notes' => $dateData['notes'] ?? null,
                    ]);
                }
            }

            return $offering->fresh(['dates']);
        });
    }

    public function deleteOffering(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $offering = CourseOffering::findOrFail($id);
            return $offering->delete();
        });
    }

    public function toggleOfferingStatus(int $id): CourseOffering
    {
        return DB::transaction(function () use ($id) {
            $offering = CourseOffering::findOrFail($id);
            $offering->is_active = !$offering->is_active;
            $offering->save();
            return $offering;
        });
    }
}