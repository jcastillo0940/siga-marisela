<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Enrollment;
use App\Models\Student;
use App\Services\CertificateService;
use Illuminate\Http\Request;
class CertificateWebController extends Controller
{
    public function __construct(
        private CertificateService $certificateService
    ) {}
    public function index()
    {
        $certificates = Certificate::with(['student', 'course', 'certificateTemplate'])
            ->latest('issued_at')
            ->paginate(20);
        $templates = CertificateTemplate::active()->get();
        return view('certificates.index', compact('certificates', 'templates'));
    }
    public function showTemplate($templateId)
    {
        $template = CertificateTemplate::findOrFail($templateId);
        
        return view('certificates.template-show', compact('template'));
    }
    public function studentCertificates($studentId)
    {
        $student = Student::findOrFail($studentId);
        $certificates = $this->certificateService->getStudentCertificates($studentId);
        return view('certificates.student', compact('student', 'certificates'));
    }
    public function generate(Request $request, $enrollmentId)
    {
        $enrollment = Enrollment::with(['student', 'courseOffering.course'])->findOrFail($enrollmentId);
        try {
            $certificate = $this->certificateService->generateCertificate(
                $enrollment,
                null,
                auth()->id()
            );
            return redirect()
                ->route('certificates.index')
                ->with('success', 'Certificado generado correctamente para ' . $enrollment->student->full_name);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error al generar certificado: ' . $e->getMessage());
        }
    }
    public function download($certificateId)
    {
        $certificate = Certificate::findOrFail($certificateId);
        return $this->certificateService->downloadCertificate($certificate);
    }
    
    public function verify($number, $code)
    {
        $certificate = Certificate::where('certificate_number', $number)
            ->where('verification_code', $code)
            ->with(['student', 'course'])
            ->first();
        
        if (!$certificate) {
            return view('certificates.verify', [
                'valid' => false,
                'message' => 'Certificado no encontrado o código inválido'
            ]);
        }
        
        return view('certificates.verify', [
            'valid' => true,
            'certificate' => $certificate
        ]);
    }
}