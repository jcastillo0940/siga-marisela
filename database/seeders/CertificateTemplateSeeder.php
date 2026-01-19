<?php

namespace Database\Seeders;

use App\Models\CertificateTemplate;
use Illuminate\Database\Seeder;

class CertificateTemplateSeeder extends Seeder
{
    public function run(): void
    {
        CertificateTemplate::where('name', 'Certificado Academia Auténtica')->delete();

        CertificateTemplate::create([
            'name' => 'Certificado Academia Auténtica',
            'type' => 'certificate',
            'description' => 'Diseño profesional optimizado para una sola página tamaño carta',
            'orientation' => 'landscape',
            'size' => 'letter',
            'html_template' => $this->getTemplate(),
            'css_styles' => $this->getStyles(),
            'is_active' => true,
            'is_default' => true,
            'variables' => [
                'student_name',
                'total_hours',
                'course_content',
                'location',
                'course_end_date_full',
                'certificate_number',
                'verification_code',
                'qr',
            ],
        ]);
    }

    private function getTemplate(): string
    {
        return <<<'HTML'
<div class="certificate-page">
    <div class="outer-border">
        <div class="inner-border">
            
            <div class="header">
                <h1 class="main-title">CERTIFICADO DE PARTICIPACIÓN</h1>
                <p class="subtitle">AUTOCONFIANZA - SEGURIDAD - IMAGEN</p>
            </div>

            <div class="student-section">
                <h2 class="student-name">{{student_name}}</h2>
            </div>

            <div class="course-info">
                <p class="production">Una Producción de <strong>ACADEMIA AUTÉNTICA</strong></p>
                <p class="course-title">Curso intensivo de <strong>{{total_hours}} horas</strong> de capacitación de:</p>
                <p class="course-content">{{course_content}}</p>
            </div>

            <table class="signatures">
                <tr>
                    <td class="sig-left">
                        <div class="sig-line"></div>
                        <p class="sig-label">Capacitadora</p>
                        <p class="sig-name">Anyoli Abrego</p>
                    </td>
                    <td class="qr-center">
                        <img src="{{qr}}" class="qr">
                    </td>
                    <td class="sig-right">
                        <div class="sig-line"></div>
                        <p class="sig-label">Capacitadora</p>
                        <p class="sig-name">Marisela Moreno</p>
                    </td>
                </tr>
            </table>

            <div class="date-location">
                <p>{{course_end_date_full}}</p>
                <p>{{location}}</p>
            </div>

            <div class="footer">
                <p class="quote">Más que un curso, es una experiencia de crecimiento<br>personal para la vida</p>
            </div>

        </div>
    </div>
</div>
HTML;
    }

    private function getStyles(): string
    {
        return <<<'CSS'
@page {
    margin: 0;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
}

.certificate-page {
    width: 279.4mm;
    height: 215.9mm;
    padding: 10mm;
    position: relative;
}

.outer-border {
    width: 100%;
    height: 100%;
    border: 4mm solid #000000;
    padding: 3mm;
}

.inner-border {
    width: 100%;
    height: 100%;
    border: 1mm solid #8B0000;
    padding: 12mm 15mm;
    position: relative;
}

.header {
    text-align: center;
    margin-bottom: 10mm;
}

.main-title {
    font-size: 28pt;
    font-weight: bold;
    letter-spacing: 3pt;
    color: #000000;
    margin-bottom: 3mm;
}

.subtitle {
    font-size: 11pt;
    letter-spacing: 4pt;
    color: #333333;
}

.student-section {
    text-align: center;
    margin: 15mm 0;
}

.student-name {
    font-size: 42pt;
    font-weight: bold;
    color: #000000;
    letter-spacing: 2pt;
    text-transform: uppercase;
}

.course-info {
    text-align: center;
    margin: 10mm 0;
}

.production {
    font-size: 11pt;
    margin-bottom: 4mm;
    color: #000000;
}

.course-title {
    font-size: 12pt;
    margin-bottom: 5mm;
    color: #000000;
}

.course-content {
    font-size: 11pt;
    line-height: 1.6;
    color: #000000;
    margin: 0 20mm;
    text-align: center;
}

.signatures {
    width: 100%;
    margin: 12mm 0 8mm 0;
    border-collapse: collapse;
}

.sig-left,
.sig-right {
    width: 35%;
    vertical-align: bottom;
    text-align: center;
    padding: 0 8mm;
}

.qr-center {
    width: 30%;
    vertical-align: bottom;
    text-align: center;
}

.qr {
    width: 28mm;
    height: 28mm;
}

.sig-line {
    border-bottom: 0.8mm solid #000000;
    margin: 0 auto 2mm auto;
    width: 75%;
}

.sig-label {
    font-size: 9pt;
    color: #666666;
    margin-bottom: 1mm;
}

.sig-name {
    font-size: 13pt;
    font-weight: bold;
    color: #000000;
}

.date-location {
    text-align: center;
    margin: 8mm 0;
}

.date-location p {
    font-size: 11pt;
    font-weight: bold;
    color: #000000;
    margin: 1.5mm 0;
}

.footer {
    text-align: center;
    margin-top: 10mm;
}

.quote {
    font-size: 10pt;
    font-style: italic;
    color: #666666;
    line-height: 1.5;
}
CSS;
    }
}