<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Enrollment;
use App\Services\CertificateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct(
        private CertificateService $certificateService
    ) {}

    /**
     * Listar certificados
     */
    public function index(Request $request): JsonResponse
    {
        $query = Certificate::with(['student', 'course', 'certificateTemplate']);

        // Filtros
        if ($request->has('student_id')) {
            $query->forStudent($request->student_id);
        }

        if ($request->has('course_id')) {
            $query->forCourse($request->course_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $certificates = $query->latest('issued_at')->paginate(20);

        return response()->json($certificates);
    }

    /**
     * Generar certificado individual
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'template_id' => 'nullable|exists:certificate_templates,id',
        ]);

        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($validated['enrollment_id']);
        
        $template = null;
        if (isset($validated['template_id'])) {
            $template = CertificateTemplate::findOrFail($validated['template_id']);
        }

        try {
            $certificate = $this->certificateService->generateCertificate(
                $enrollment,
                $template,
                auth()->id()
            );

            return response()->json([
                'message' => 'Certificado generado correctamente',
                'certificate' => $certificate->load(['student', 'course', 'certificateTemplate']),
                'download_url' => $certificate->download_url,
                'verification_url' => $certificate->public_verification_url,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al generar certificado: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Generar certificados en lote
     */
    public function bulkGenerate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'template_id' => 'nullable|exists:certificate_templates,id',
            'min_attendance' => 'nullable|numeric|min:0|max:100',
        ]);

        $template = null;
        if (isset($validated['template_id'])) {
            $template = CertificateTemplate::findOrFail($validated['template_id']);
        }

        $results = $this->certificateService->generateBulkCertificates(
            $validated['course_id'],
            $template,
            auth()->id(),
            $validated['min_attendance'] ?? 80.0
        );

        return response()->json([
            'message' => 'Proceso de generación completado',
            'results' => $results,
            'summary' => [
                'generated' => count($results['generated']),
                'skipped' => count($results['skipped']),
                'errors' => count($results['errors']),
            ],
        ]);
    }

    /**
     * Ver certificado
     */
    public function show(int $id): JsonResponse
    {
        $certificate = Certificate::with([
            'student',
            'course',
            'certificateTemplate',
            'enrollment',
            'issuedBy'
        ])->findOrFail($id);

        return response()->json($certificate);
    }

    /**
     * Descargar certificado
     */
    public function download(int $id)
    {
        $certificate = Certificate::findOrFail($id);

        // Verificar permisos (opcional)
        // if (!auth()->user()->can('download', $certificate)) {
        //     abort(403);
        // }

        return $this->certificateService->downloadCertificate($certificate);
    }

    /**
     * Regenerar PDF de certificado
     */
    public function regenerate(int $id): JsonResponse
    {
        $certificate = Certificate::findOrFail($id);

        try {
            $pdfPath = $this->certificateService->regeneratePDF($certificate);

            return response()->json([
                'message' => 'PDF regenerado correctamente',
                'certificate' => $certificate->fresh(),
                'download_url' => $certificate->download_url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al regenerar PDF: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Revocar certificado
     */
    public function revoke(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $certificate = Certificate::findOrFail($id);

        $this->certificateService->revokeCertificate(
            $certificate,
            $validated['reason'],
            auth()->id()
        );

        return response()->json([
            'message' => 'Certificado revocado correctamente',
            'certificate' => $certificate->fresh(),
        ]);
    }

    /**
     * Reactivar certificado
     */
    public function reissue(int $id): JsonResponse
    {
        $certificate = Certificate::findOrFail($id);
        
        $certificate->reissue(auth()->id());

        return response()->json([
            'message' => 'Certificado reactivado correctamente',
            'certificate' => $certificate->fresh(),
        ]);
    }

    /**
     * Verificar certificado público
     */
    public function verify(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'verification_code' => 'required|string',
        ]);

        $result = $this->certificateService->verifyCertificate($validated['verification_code']);

        if (!$result) {
            return response()->json([
                'is_valid' => false,
                'message' => 'Certificado no encontrado o inválido',
            ], 404);
        }

        return response()->json($result);
    }

    /**
     * Obtener certificados de un estudiante
     */
    public function studentCertificates(int $studentId): JsonResponse
    {
        $certificates = $this->certificateService->getStudentCertificates($studentId);

        return response()->json([
            'student_id' => $studentId,
            'certificates' => $certificates,
            'total' => $certificates->count(),
        ]);
    }

    /**
     * Verificar si puede generar certificado
     */
    public function checkEligibility(int $enrollmentId): JsonResponse
    {
        $enrollment = Enrollment::with(['student', 'course'])->findOrFail($enrollmentId);
        
        $result = $this->certificateService->canGenerateCertificate($enrollment);

        return response()->json([
            'enrollment_id' => $enrollmentId,
            'student_name' => $enrollment->student->full_name,
            'course_name' => $enrollment->course->name,
            'can_generate' => $result['can_generate'],
            'reasons' => $result['reasons'],
        ]);
    }

    /**
     * Eliminar certificado
     */
    public function destroy(int $id): JsonResponse
    {
        $certificate = Certificate::findOrFail($id);
        
        // Eliminar archivo físico
        if ($certificate->pdf_path && \Storage::exists($certificate->pdf_path)) {
            \Storage::delete($certificate->pdf_path);
        }

        $certificate->delete();

        return response()->json([
            'message' => 'Certificado eliminado correctamente',
        ]);
    }
}
