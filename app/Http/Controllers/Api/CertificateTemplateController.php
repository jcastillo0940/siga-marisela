<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use App\Services\CertificateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    public function __construct(
        private CertificateService $certificateService
    ) {}

    /**
     * Listar plantillas de certificados
     */
    public function index(Request $request): JsonResponse
    {
        $query = CertificateTemplate::query();

        // Filtros
        if ($request->has('type')) {
            $query->ofType($request->type);
        }

        if ($request->boolean('active_only')) {
            $query->active();
        }

        $templates = $query->latest()->get();

        return response()->json($templates);
    }

    /**
     * Crear plantilla
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:certificate,diploma,recognition',
            'description' => 'nullable|string',
            'orientation' => 'required|in:landscape,portrait',
            'size' => 'required|in:letter,a4',
            'background_image' => 'nullable|string',
            'html_template' => 'required|string',
            'css_styles' => 'nullable|string',
            'min_attendance_percentage' => 'required|numeric|min:0|max:100',
            'requires_payment_complete' => 'boolean',
            'requires_all_sessions' => 'boolean',
            'variables' => 'nullable|array',
            'signatures' => 'nullable|array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // Si se marca como default, desmarcar otros
        if ($validated['is_default'] ?? false) {
            CertificateTemplate::where('is_default', true)->update(['is_default' => false]);
        }

        $template = CertificateTemplate::create($validated);

        return response()->json([
            'message' => 'Plantilla creada correctamente',
            'template' => $template,
        ], 201);
    }

    /**
     * Ver plantilla
     */
    public function show(int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);

        return response()->json($template);
    }

    /**
     * Actualizar plantilla
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|in:certificate,diploma,recognition',
            'description' => 'nullable|string',
            'orientation' => 'sometimes|in:landscape,portrait',
            'size' => 'sometimes|in:letter,a4',
            'background_image' => 'nullable|string',
            'html_template' => 'sometimes|string',
            'css_styles' => 'nullable|string',
            'min_attendance_percentage' => 'sometimes|numeric|min:0|max:100',
            'requires_payment_complete' => 'boolean',
            'requires_all_sessions' => 'boolean',
            'variables' => 'nullable|array',
            'signatures' => 'nullable|array',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // Si se marca como default, desmarcar otros
        if (isset($validated['is_default']) && $validated['is_default']) {
            CertificateTemplate::where('id', '!=', $id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        $template->update($validated);

        return response()->json([
            'message' => 'Plantilla actualizada correctamente',
            'template' => $template->fresh(),
        ]);
    }

    /**
     * Activar/desactivar plantilla
     */
    public function toggleActive(int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);
        
        $template->update(['is_active' => !$template->is_active]);

        return response()->json([
            'message' => 'Estado de plantilla actualizado',
            'template' => $template->fresh(),
        ]);
    }

    /**
     * Establecer como plantilla por defecto
     */
    public function setDefault(int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);

        // Desmarcar otros defaults
        CertificateTemplate::where('is_default', true)->update(['is_default' => false]);
        
        // Marcar este como default
        $template->update(['is_default' => true, 'is_active' => true]);

        return response()->json([
            'message' => 'Plantilla establecida como predeterminada',
            'template' => $template->fresh(),
        ]);
    }

    /**
     * Vista previa del HTML
     */
    public function preview(Request $request, int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);

        // Datos de ejemplo para la vista previa
        $sampleData = [
            'student_name' => 'Juan Pérez González',
            'student_document' => '8-123-456',
            'course_name' => 'Curso de Desarrollo Personal',
            'course_duration' => '60 días',
            'course_start_date' => '01/10/2025',
            'course_end_date' => '30/11/2025',
            'attendance_percentage' => '95.5',
            'total_hours' => '120',
            'total_sessions' => '30',
            'attended_sessions' => '29',
            'issue_date' => now()->format('d/m/Y'),
            'certificate_number' => 'AA-2026-000123',
            'verification_code' => 'ABC12345',
            'final_grade' => '9.5',
        ];

        $html = $template->processTemplate($sampleData);
        $fullHtml = str_replace($template->html_template, $html, $template->getFullHtml());

        return response()->json([
            'html' => $fullHtml,
            'sample_data' => $sampleData,
        ]);
    }

    /**
     * Obtener variables disponibles
     */
    public function variables(int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);

        return response()->json([
            'variables' => $template->getAvailableVariables(),
        ]);
    }

    /**
     * Duplicar plantilla
     */
    public function duplicate(int $id): JsonResponse
    {
        $original = CertificateTemplate::findOrFail($id);
        
        $duplicate = $original->replicate();
        $duplicate->name = $original->name . ' (Copia)';
        $duplicate->is_default = false;
        $duplicate->is_active = false;
        $duplicate->save();

        return response()->json([
            'message' => 'Plantilla duplicada correctamente',
            'template' => $duplicate,
        ], 201);
    }

    /**
     * Crear plantilla por defecto del sistema
     */
    public function createDefault(): JsonResponse
    {
        // Verificar si ya existe una plantilla por defecto
        $existing = CertificateTemplate::default()->first();
        
        if ($existing) {
            return response()->json([
                'message' => 'Ya existe una plantilla por defecto',
                'template' => $existing,
            ], 200);
        }

        $template = $this->certificateService->createDefaultTemplate();

        return response()->json([
            'message' => 'Plantilla por defecto creada correctamente',
            'template' => $template,
        ], 201);
    }

    /**
     * Eliminar plantilla
     */
    public function destroy(int $id): JsonResponse
    {
        $template = CertificateTemplate::findOrFail($id);

        // No permitir eliminar si es la plantilla por defecto
        if ($template->is_default) {
            return response()->json([
                'message' => 'No se puede eliminar la plantilla por defecto',
            ], 400);
        }

        // No permitir eliminar si tiene certificados generados
        if ($template->certificates()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar una plantilla que tiene certificados generados',
            ], 400);
        }

        $template->delete();

        return response()->json([
            'message' => 'Plantilla eliminada correctamente',
        ]);
    }
}
