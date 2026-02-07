<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\StudentService;
use App\DTOs\Student\CreateStudentDTO;
use App\DTOs\Student\UpdateStudentDTO;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Muestra la lista de estudiantes.
     */
    public function index(Request $request)
    {
        $includeInactive = $request->has('include_inactive');
        $students = $this->studentService->getAllStudents($includeInactive);

        return view('students.index', compact('students', 'includeInactive'));
    }

    /**
     * Formulario para crear nuevo estudiante.
     */
    public function create()
    {
        return view('students.create');
    }

    /**
     * Almacena el estudiante usando el CreateStudentDTO.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email',
            'gender'     => 'required|in:male,female,other',
            'status'     => 'required|string',
            // Agrega aquí el resto de validaciones si lo deseas
        ]);

        // Aseguramos el valor booleano para is_active
        $validated['is_active'] = $request->has('is_active');

        $dto = CreateStudentDTO::fromRequest(array_merge($request->all(), $validated));
        $this->studentService->createStudent($dto);

        return redirect()->route('students.index')
                         ->with('success', 'Estudiante creado exitosamente.');
    }

    /**
     * Muestra los detalles de un estudiante.
     */
    public function show($id)
    {
        $student = $this->studentService->getStudentById($id);

        if (!$student) {
            abort(404);
        }

        return view('students.show', compact('student'));
    }

    /**
     * Formulario para editar.
     */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    /**
     * Actualiza el estudiante usando el UpdateStudentDTO.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:students,email,' . $id,
            'gender'     => 'required|in:male,female,other',
            'status'     => 'required|string',
        ]);

        // Manejo del checkbox
        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        $dto = UpdateStudentDTO::fromRequest($data);
        $this->studentService->updateStudent($id, $dto);

        return redirect()->route('students.show', $id)
                         ->with('success', 'Estudiante actualizado correctamente.');
    }

    /**
     * Elimina el estudiante (Soft Delete).
     */
    public function destroy($id)
    {
        $this->studentService->deleteStudent($id);

        return redirect()->route('students.index')
                         ->with('success', 'Estudiante eliminado correctamente.');
    }

    /**
     * Alterna el estado activo/inactivo.
     */
    public function toggleStatus($id)
    {
        $this->studentService->toggleStudentStatus($id);

        return back()->with('success', 'Estado del estudiante actualizado.');
    }
}