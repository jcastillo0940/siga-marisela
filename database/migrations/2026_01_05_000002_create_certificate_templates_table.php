<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Certificado de Asistencia", "Diploma de Graduación"
            $table->enum('type', ['certificate', 'diploma', 'recognition'])->default('certificate');
            $table->text('description')->nullable();
            
            // Diseño
            $table->string('orientation')->default('landscape'); // 'landscape', 'portrait'
            $table->string('size')->default('letter'); // 'letter', 'a4'
            $table->string('background_image')->nullable(); // ruta a imagen de fondo
            
            // Contenido HTML/CSS
            $table->longText('html_template'); // HTML con variables: {{student_name}}, {{course_name}}, etc.
            $table->longText('css_styles')->nullable();
            
            // Requisitos
            $table->decimal('min_attendance_percentage', 5, 2)->default(80.00); // 80% mínimo
            $table->boolean('requires_payment_complete')->default(true);
            $table->boolean('requires_all_sessions')->default(false);
            
            // Configuración
            $table->json('variables')->nullable(); // variables disponibles y sus valores por defecto
            $table->json('signatures')->nullable(); // firmas digitales configuradas
            
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
