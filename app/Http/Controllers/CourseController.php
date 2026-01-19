<?php

namespace App\Http\Controllers;

use App\DTOs\Course\CreateCourseDTO;
use App\DTOs\Course\UpdateCourseDTO;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $courseService
    ) {}

    public function index(Request $request)
    {
        $includeInactive = $request->boolean('include_inactive');
        $courses = $this->courseService->getAllCourses($includeInactive);

        return view('courses.index', compact('courses', 'includeInactive'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(StoreCourseRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = CreateCourseDTO::fromRequest($request->validated());
            $this->courseService->createCourse($dto);

            DB::commit();

            return redirect()
                ->route('courses.index')
                ->with('success', 'Curso creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al crear el curso: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $course = $this->courseService->getCourseById($id);

        if (!$course) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Curso no encontrado');
        }

        return view('courses.show', compact('course'));
    }

    public function edit(int $id)
    {
        $course = $this->courseService->getCourseById($id);

        if (!$course) {
            return redirect()
                ->route('courses.index')
                ->with('error', 'Curso no encontrado');
        }

        return view('courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, int $id)
    {
        DB::beginTransaction();

        try {
            $dto = UpdateCourseDTO::fromRequest($request->validated());
            $this->courseService->updateCourse($id, $dto);

            DB::commit();

            return redirect()
                ->route('courses.show', $id)
                ->with('success', 'Curso actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el curso: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->courseService->deleteCourse($id);

            return redirect()
                ->route('courses.index')
                ->with('success', 'Curso eliminado exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el curso: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $this->courseService->toggleCourseStatus($id);

            return back()
                ->with('success', 'Estado del curso actualizado');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }
}