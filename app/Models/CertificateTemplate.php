<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificateTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'description',
        'orientation',
        'size',
        'background_image',
        'html_template',
        'css_styles',
        'min_attendance_percentage',
        'requires_payment_complete',
        'requires_all_sessions',
        'variables',
        'signatures',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'min_attendance_percentage' => 'decimal:2',
        'requires_payment_complete' => 'boolean',
        'requires_all_sessions' => 'boolean',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'variables' => 'array',
        'signatures' => 'array',
    ];

    // Relaciones
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    // Métodos para procesar el template
    public function processTemplate(array $data): string
    {
        $html = $this->html_template;

        // Reemplazar variables en el template
        foreach ($data as $key => $value) {
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }

        return $html;
    }

    public function getAvailableVariables(): array
    {
        return $this->variables ?? [
            'student_name' => 'Nombre completo del estudiante',
            'student_document' => 'Documento de identidad',
            'course_name' => 'Nombre del curso',
            'course_duration' => 'Duración del curso',
            'course_start_date' => 'Fecha de inicio',
            'course_end_date' => 'Fecha de finalización',
            'attendance_percentage' => 'Porcentaje de asistencia',
            'total_hours' => 'Total de horas',
            'issue_date' => 'Fecha de emisión',
            'certificate_number' => 'Número de certificado',
            'verification_code' => 'Código de verificación',
            'final_grade' => 'Calificación final',
        ];
    }

    public function canGenerateCertificate(Enrollment $enrollment): array
    {
        $reasons = [];
        
        // Verificar porcentaje de asistencia
        $attendancePercentage = $enrollment->attendance_percentage;
        if ($attendancePercentage < $this->min_attendance_percentage) {
            $reasons[] = "Asistencia insuficiente: {$attendancePercentage}% (mínimo: {$this->min_attendance_percentage}%)";
        }

        // Verificar pago completo
        if ($this->requires_payment_complete && !$enrollment->is_fully_paid) {
            $reasons[] = "Pago incompleto. Saldo pendiente: $" . number_format($enrollment->balance, 2);
        }

        // Verificar asistencia a todas las sesiones
        if ($this->requires_all_sessions && $enrollment->attended_sessions < $enrollment->total_sessions) {
            $reasons[] = "Debe asistir a todas las sesiones ({$enrollment->attended_sessions}/{$enrollment->total_sessions})";
        }

        return [
            'can_generate' => empty($reasons),
            'reasons' => $reasons,
        ];
    }

    public function getFullHtml(): string
    {
        $css = $this->css_styles ?? '';
        
        return <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: {$this->size} {$this->orientation};
                    margin: 0;
                }
                body {
                    margin: 0;
                    padding: 0;
                    font-family: 'Georgia', serif;
                }
                {$css}
            </style>
        </head>
        <body>
            {$this->html_template}
        </body>
        </html>
        HTML;
    }

    // Plantilla por defecto profesional para PÁRATE BONITO
    public static function getDefaultTemplate(): string
    {
        return <<<'HTML'
<div style="width: 100%; height: 100%; padding: 0; margin: 0; background-color: #ffffff; border: 25px solid #1a1a1a; box-sizing: border-box; position: relative; color: #333;">
    <div style="position: absolute; top: 10px; left: 10px; right: 10px; bottom: 10px; border: 2px solid #e21f26; pointer-events: none;"></div>

    <div style="padding: 60px; text-align: center; height: 100%; box-sizing: border-box; position: relative;">
        
        <div style="margin-bottom: 25px;">
            <p style="font-family: 'Helvetica', sans-serif; font-size: 14px; letter-spacing: 6px; color: #e21f26; margin: 0; text-transform: uppercase; font-weight: bold;">Academia Auténtica</p>
            <h1 style="font-family: 'Times New Roman', serif; font-size: 55px; margin: 15px 0; font-weight: 900; text-transform: uppercase; color: #1a1a1a;">
                PÁRATE <span style="color: #e21f26;">BONITO</span>
            </h1>
            <p style="font-size: 13px; letter-spacing: 4px; color: #666; text-transform: uppercase;">Auto Confianza • Seguridad • Imagen</p>
        </div>

        <div style="margin: 35px 0;">
            <h3 style="font-family: 'Georgia', serif; font-style: italic; font-size: 26px; color: #444; margin-bottom: 10px;">Certificado de Participación</h3>
            <p style="font-size: 16px; margin: 15px 0; color: #777;">Otorgado con distinción a:</p>
            
            <h2 style="font-family: 'Edwardian Script ITC', 'Zapfino', 'Cursive'; font-size: 80px; font-weight: 100; color: #1a1a1a; margin: 10px 0; border-bottom: 2px solid #e21f26; display: inline-block; padding: 0 60px; line-height: 1;">
                {{student_name}}
            </h2>
        </div>

        <div style="max-width: 850px; margin: 0 auto; line-height: 1.7; font-size: 16px; color: #333;">
            <p>
                Por haber completado satisfactoriamente el <strong>Curso Intensivo de {{total_hours}} horas</strong> de capacitación en: <br>
                <span style="font-style: italic; color: #555;">Imagen, colorimetría, psicología del color, estilismo, etiqueta en la mesa y social, refinamiento, comunicación oral, proyección de voz, caminado elegante, bajo el marco de la psicolingüística.</span>
            </p>
            <p style="margin-top: 25px; font-weight: bold; font-size: 15px; color: #1a1a1a;">
                {{issue_date}} | HOTEL SAND DIAMOND | COLÓN, PANAMÁ
            </p>
        </div>

        <div style="margin-top: 60px; display: flex; align-items: flex-end; justify-content: space-around; padding: 0 20px;">
            
            <div style="text-align: center; width: 220px;">
                <div style="border-bottom: 1px solid #1a1a1a; margin-bottom: 10px;"></div>
                <p style="font-size: 14px; font-weight: bold; margin: 0; color: #1a1a1a;">Anyoli Abrego</p>
                <p style="font-size: 12px; color: #e21f26; margin: 0; font-weight: bold;">Capacitadora</p>
            </div>

            <div style="text-align: center; margin: 0 30px;">
                <div style="padding: 8px; border: 1px solid #ddd; background: #fff; display: inline-block; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{verification_code}}" alt="QR de Verificación" style="display: block;">
                </div>
                <p style="font-size: 9px; color: #999; margin-top: 8px; letter-spacing: 1px;">ID: {{certificate_number}}</p>
            </div>

            <div style="text-align: center; width: 220px;">
                <div style="border-bottom: 1px solid #1a1a1a; margin-bottom: 10px;"></div>
                <p style="font-size: 14px; font-weight: bold; margin: 0; color: #1a1a1a;">Marised Morene</p>
                <p style="font-size: 12px; color: #e21f26; margin: 0; font-weight: bold;">Capacitadora</p>
            </div>
        </div>

        <div style="position: absolute; bottom: 50px; left: 0; right: 0; text-align: center;">
            <p style="font-size: 14px; font-style: italic; color: #666; font-family: 'Georgia', serif;">
                "Más que un curso, es una experiencia de crecimiento personal para la vida"
            </p>
        </div>
    </div>
</div>
HTML;
    }
}