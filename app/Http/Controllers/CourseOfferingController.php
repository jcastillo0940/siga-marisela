<?php

namespace App\Http\Controllers;

use App\DTOs\CourseOffering\CreateCourseOfferingDTO;
use App\DTOs\CourseOffering\UpdateCourseOfferingDTO;
use App\Http\Requests\CourseOffering\StoreCourseOfferingRequest;
use App\Http\Requests\CourseOffering\UpdateCourseOfferingRequest;
use App\Models\Course;
use App\Services\CourseOfferingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseOfferingController extends Controller
{
    public function __construct(
        private CourseOfferingService $offeringService
    ) {}

    public function index(Request $request)
    {
        $includeInactive = $request->boolean('include_inactive');
        $offerings = $this->offeringService->getAllOfferings($includeInactive);

        return view('course-offerings.index', compact('offerings', 'includeInactive'));
    }

    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        return view('course-offerings.create', compact('courses'));
    }

    public function store(StoreCourseOfferingRequest $request)
    {
        DB::beginTransaction();

        try {
            $dto = CreateCourseOfferingDTO::fromRequest($request->validated());
            $this->offeringService->createOffering($dto);

            DB::commit();

            return redirect()
                ->route('course-offerings.index')
                ->with('success', 'Oferta de curso creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al crear la oferta: ' . $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $offering = $this->offeringService->getOfferingById($id);

        if (!$offering) {
            return redirect()
                ->route('course-offerings.index')
                ->with('error', 'Oferta no encontrada');
        }

        return view('course-offerings.show', compact('offering'));
    }

    public function edit(int $id)
    {
        $offering = $this->offeringService->getOfferingById($id);
        $courses = Course::where('is_active', true)->get();

        if (!$offering) {
            return redirect()
                ->route('course-offerings.index')
                ->with('error', 'Oferta no encontrada');
        }

        return view('course-offerings.edit', compact('offering', 'courses'));
    }

    public function update(UpdateCourseOfferingRequest $request, int $id)
    {
        DB::beginTransaction();

        try {
            $dto = UpdateCourseOfferingDTO::fromRequest($request->validated());
            $this->offeringService->updateOffering($id, $dto);

            DB::commit();

            return redirect()
                ->route('course-offerings.show', $id)
                ->with('success', 'Oferta actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Error al actualizar la oferta: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->offeringService->deleteOffering($id);

            return redirect()
                ->route('course-offerings.index')
                ->with('success', 'Oferta eliminada exitosamente');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al eliminar la oferta: ' . $e->getMessage());
        }
    }

    public function toggleStatus(int $id)
    {
        try {
            $this->offeringService->toggleOfferingStatus($id);

            return back()
                ->with('success', 'Estado de la oferta actualizado');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }
}