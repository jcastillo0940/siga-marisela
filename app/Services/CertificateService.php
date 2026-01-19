<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Mpdf\Mpdf;

class CertificateService
{
    public function generateCertificate(
        Enrollment $enrollment,
        ?CertificateTemplate $template = null,
        ?int $userId = null
    ): Certificate {
        if (!$template) {
            $template = CertificateTemplate::active()->default()->first();
            if (!$template) {
                throw new \Exception('No hay plantilla de certificado configurada');
            }
        }

        $validation = $template->canGenerateCertificate($enrollment);
        if (!$validation['can_generate']) {
            throw new \Exception('No cumple requisitos: ' . implode(', ', $validation['reasons']));
        }

        $attendanceService = new AttendanceService();
        $stats = $attendanceService->getStudentAttendanceStats($enrollment);

        $offering = $enrollment->courseOffering;
        $course = $offering->course;
        $endDate = Carbon::parse($offering->end_date)->locale('es');

        $dateText = ucfirst($endDate->isoFormat('dddd, D [de] MMMM [de] YYYY'));
        
        $location = $this->prepareLocation($offering);
        $courseContent = $this->prepareCourseContent($course);

        $certificate = Certificate::create([
            'certificate_number' => Certificate::generateCertificateNumber(),
            'verification_code' => Certificate::generateVerificationCode(),
            'enrollment_id' => $enrollment->id,
            'certificate_template_id' => $template->id,
            'student_id' => $enrollment->student_id,
            'course_id' => $course->id,
            'student_full_name' => $enrollment->student->full_name,
            'student_document' => $enrollment->student->identification,
            'course_name' => $course->name,
            'total_hours' => $course->duration_hours ?? 8,
            'course_content' => $courseContent,
            'location' => $location,
            'issue_date_text' => $dateText,
            'course_start_date' => $offering->start_date,
            'course_end_date' => $offering->end_date,
            'total_sessions' => $stats['total_sessions'],
            'attended_sessions' => $stats['attended'],
            'attendance_percentage' => $stats['percentage'],
            'final_grade' => $enrollment->final_grade,
            'issued_at' => now(),
            'issued_by' => $userId,
            'status' => 'issued',
            'pdf_path' => '',
            'pdf_filename' => '',
        ]);

        $pdfPath = $this->generatePDF($certificate, $template);

        $certificate->update([
            'pdf_path' => $pdfPath,
            'pdf_filename' => basename($pdfPath),
            'file_size' => Storage::disk('local')->size($pdfPath),
        ]);

        return $certificate->fresh();
    }

    private function prepareCourseContent($course): string
    {
        if (!empty($course->certificate_description)) {
            return $course->certificate_description;
        }

        if (!empty($course->content_outline)) {
            return $course->content_outline;
        }

        $defaultContent = [
            'Imagen',
            'colorimetría',
            'psicología del color',
            'estilismo',
            'etiqueta en la mesa y social',
            'refinamiento',
            'comunicación oral',
            'proyección de voz',
            'caminado elegante',
            'bajo el marco de la psicolingüística'
        ];

        return implode(', ', $defaultContent) . '.';
    }

    private function prepareLocation($offering): string
    {
        $parts = array_filter([
            $offering->location ?? null,
            $offering->city ?? null,
            $offering->country ?? null
        ]);

        return !empty($parts) 
            ? implode(' - ', $parts)
            : 'Panamá - Ciudad de Colón';
    }

    public function generatePDF(Certificate $certificate, ?CertificateTemplate $template = null): string
    {
        if (!$template) {
            $template = $certificate->certificateTemplate;
        }

        $verificationUrl = route('certificates.verify', [
            'number' => $certificate->certificate_number,
            'code' => $certificate->verification_code
        ]);

        $qrCode = QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate($verificationUrl);

        $qrDataUri = 'data:image/png;base64,' . base64_encode($qrCode);

        $data = [
            'student_name' => strtoupper($certificate->student_full_name),
            'total_hours' => $certificate->total_hours,
            'course_content' => $certificate->course_content,
            'location' => $certificate->location,
            'course_end_date_full' => $certificate->issue_date_text,
            'certificate_number' => $certificate->certificate_number,
            'verification_code' => $certificate->verification_code,
            'qr' => $qrDataUri,
        ];

        $html = $template->processTemplate($data);
        $css = $template->css_styles;

        $fullHtml = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <style>{$css}</style>
</head>
<body>
    {$html}
</body>
</html>";

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [279.4, 215.9],
            'orientation' => 'L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_header' => 0,
            'margin_footer' => 0,
        ]);

        $mpdf->WriteHTML($fullHtml);

        $filename = 'CERT-' . $certificate->certificate_number . '.pdf';
        $path = 'certificates/' . $certificate->student_id . '/' . $filename;

        $directory = dirname(storage_path('app/' . $path));
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $mpdf->Output(storage_path('app/' . $path), 'F');

        return $path;
    }

    public function regeneratePDF(Certificate $certificate): string
    {
        if ($certificate->pdf_path && Storage::disk('local')->exists($certificate->pdf_path)) {
            Storage::disk('local')->delete($certificate->pdf_path);
        }

        $pdfPath = $this->generatePDF($certificate);

        $certificate->update([
            'pdf_path' => $pdfPath,
            'pdf_filename' => basename($pdfPath),
            'file_size' => Storage::disk('local')->size($pdfPath),
        ]);

        return $pdfPath;
    }

    public function downloadCertificate(Certificate $certificate): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        if (!Storage::disk('local')->exists($certificate->pdf_path)) {
            $this->regeneratePDF($certificate);
        }

        return Storage::disk('local')->download($certificate->pdf_path, $certificate->pdf_filename);
    }

    public function getStudentCertificates(int $studentId): \Illuminate\Database\Eloquent\Collection
    {
        return Certificate::where('student_id', $studentId)
            ->where('status', 'issued')
            ->with(['certificateTemplate'])
            ->orderBy('issued_at', 'desc')
            ->get();
    }
}