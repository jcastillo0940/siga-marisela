<?php

namespace App\Http\Controllers;

use App\Models\CertificateTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateTemplateController extends Controller
{
    public function index()
    {
        $templates = CertificateTemplate::orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('certificate-templates.index', compact('templates'));
    }

    public function create()
    {
        $availableVariables = (new CertificateTemplate())->getAvailableVariables();
        return view('certificate-templates.create', compact('availableVariables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:course,workshop,seminar,other',
            'description' => 'nullable|string',
            'orientation' => 'required|in:portrait,landscape',
            'size' => 'required|in:A4,Letter',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'html_template' => 'required|string',
            'css_styles' => 'nullable|string',
            'min_attendance_percentage' => 'required|numeric|min:0|max:100',
            'requires_payment_complete' => 'boolean',
            'requires_all_sessions' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // Handle checkboxes
        $validated['requires_payment_complete'] = $request->has('requires_payment_complete');
        $validated['requires_all_sessions'] = $request->has('requires_all_sessions');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            $path = $request->file('background_image')->store('certificates/backgrounds', 'public');
            $validated['background_image'] = $path;
        }

        // If this is set as default, unset all other defaults
        if ($validated['is_default']) {
            CertificateTemplate::where('is_default', true)->update(['is_default' => false]);
        }

        $template = CertificateTemplate::create($validated);

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Plantilla creada exitosamente');
    }

    public function show(CertificateTemplate $certificateTemplate)
    {
        return view('certificate-templates.show', compact('certificateTemplate'));
    }

    public function edit(CertificateTemplate $certificateTemplate)
    {
        $availableVariables = $certificateTemplate->getAvailableVariables();
        return view('certificate-templates.edit', compact('certificateTemplate', 'availableVariables'));
    }

    public function update(Request $request, CertificateTemplate $certificateTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:course,workshop,seminar,other',
            'description' => 'nullable|string',
            'orientation' => 'required|in:portrait,landscape',
            'size' => 'required|in:A4,Letter',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'html_template' => 'required|string',
            'css_styles' => 'nullable|string',
            'min_attendance_percentage' => 'required|numeric|min:0|max:100',
            'requires_payment_complete' => 'boolean',
            'requires_all_sessions' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        // Handle checkboxes
        $validated['requires_payment_complete'] = $request->has('requires_payment_complete');
        $validated['requires_all_sessions'] = $request->has('requires_all_sessions');
        $validated['is_active'] = $request->has('is_active');
        $validated['is_default'] = $request->has('is_default');

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($certificateTemplate->background_image) {
                Storage::disk('public')->delete($certificateTemplate->background_image);
            }
            $path = $request->file('background_image')->store('certificates/backgrounds', 'public');
            $validated['background_image'] = $path;
        }

        // If this is set as default, unset all other defaults
        if ($validated['is_default'] && !$certificateTemplate->is_default) {
            CertificateTemplate::where('is_default', true)
                ->where('id', '!=', $certificateTemplate->id)
                ->update(['is_default' => false]);
        }

        $certificateTemplate->update($validated);

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Plantilla actualizada exitosamente');
    }

    public function destroy(CertificateTemplate $certificateTemplate)
    {
        // Check if template has issued certificates
        if ($certificateTemplate->certificates()->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar una plantilla que tiene certificados emitidos');
        }

        // Delete background image if exists
        if ($certificateTemplate->background_image) {
            Storage::disk('public')->delete($certificateTemplate->background_image);
        }

        $certificateTemplate->delete();

        return redirect()->route('certificate-templates.index')
            ->with('success', 'Plantilla eliminada exitosamente');
    }

    public function preview(Request $request, CertificateTemplate $certificateTemplate)
    {
        // Generate sample data for preview
        $sampleData = [
            'student_name' => 'María José Rodríguez García',
            'student_document' => '8-123-4567',
            'course_name' => 'Curso de Ejemplo Completo',
            'course_duration' => '40 horas',
            'course_start_date' => now()->subDays(30)->format('d/m/Y'),
            'course_end_date' => now()->format('d/m/Y'),
            'attendance_percentage' => '95%',
            'total_hours' => '40',
            'issue_date' => now()->format('d/m/Y'),
            'certificate_number' => 'CERT-' . strtoupper(uniqid()),
            'verification_code' => 'https://academia.com/verify/12345',
            'final_grade' => '9.5',
            'instructor_name' => 'Anyoli Abrego',
        ];

        $html = $certificateTemplate->processTemplate($sampleData);
        $fullHtml = str_replace('{$this->html_template}', $html, $certificateTemplate->getFullHtml());

        return response($fullHtml);
    }

    public function duplicate(CertificateTemplate $certificateTemplate)
    {
        $newTemplate = $certificateTemplate->replicate();
        $newTemplate->name = $certificateTemplate->name . ' (Copia)';
        $newTemplate->is_default = false;
        $newTemplate->is_active = false;

        // Copy background image if exists
        if ($certificateTemplate->background_image) {
            $oldPath = $certificateTemplate->background_image;
            $extension = pathinfo($oldPath, PATHINFO_EXTENSION);
            $newPath = 'certificates/backgrounds/' . uniqid() . '.' . $extension;
            Storage::disk('public')->copy($oldPath, $newPath);
            $newTemplate->background_image = $newPath;
        }

        $newTemplate->save();

        return redirect()->route('certificate-templates.edit', $newTemplate)
            ->with('success', 'Plantilla duplicada exitosamente. Edita y activa cuando esté lista.');
    }
}
