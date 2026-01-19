<?php

namespace App\Http\Controllers;

use App\DTOs\Student\CreateStudentDTO;
use App\DTOs\Student\UpdateStudentDTO;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function __construct(
        private StudentService $studentService
    ) {}

    public function index(Request $request)
    {
        $includeInactive = $request->boolean('include_inactive');
        $students = $this->studentService->getAllStudents($includeInactive);

        return view('students.index', compact('students', 'includeInactive'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = CreateStudentDTO::fromRequest($request->validated());
            $this->studentService->createStudent($dto);

            DB::commit();

            return redirect()
                ->route('students.index')
                ->with('success', 'Estudiante creado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al crear el estudiante: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $student = $this->studentService->getStudentById($id);

        if (!$student) {
            return redirect()
                ->route('students.index')
                ->with('error', 'Estudiante no encontrado');
        }

        return view('students.show', compact('student'));
    }

    public function edit(int $id)
    {
        $student = $this->studentService->getStudentById($id);

        if (!$student) {
            return redirect()
                ->route('students.index')
                ->with('error', 'Estudiante no encontrado');
        }

        return view('students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, int $id)
    {
        DB::beginTransaction();

        try {
            $dto = UpdateStudentDTO::fromRequest($request->validated());
            $this->studentService->updateStudent($id, $dto);

            DB::commit();

            return redirect()
                ->route('students.show', $id)
                ->with('success', 'Estudiante actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al actualizar el estudiante: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->studentService->deleteStudent($id);

            return redirect()
                ->route('students.index')
                ->with('success', 'Estudiante eliminado exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $this->studentService->toggleStudentStatus($id);

            return back()
                ->with('success', 'Estado del estudiante actualizado');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }
}