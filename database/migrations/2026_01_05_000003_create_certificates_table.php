<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique(); // AA-2026-001234
            $table->string('verification_code')->unique(); // código para verificación pública
            
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade');
            $table->foreignId('certificate_template_id')->constrained('certificate_templates');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('course_id')->constrained('courses');
            
            // Datos al momento de generación
            $table->string('student_full_name');
            $table->string('student_document');
            $table->string('course_name');
            $table->date('course_start_date');
            $table->date('course_end_date');
            $table->integer('total_sessions');
            $table->integer('attended_sessions');
            $table->decimal('attendance_percentage', 5, 2);
            $table->decimal('final_grade', 5, 2)->nullable();
            
            // Archivo generado
            $table->string('pdf_path');
            $table->string('pdf_filename');
            $table->integer('file_size')->nullable();
            
            // Metadata
            $table->timestamp('issued_at');
            $table->timestamp('downloaded_at')->nullable();
            $table->integer('download_count')->default(0);
            $table->foreignId('issued_by')->nullable()->constrained('users');
            $table->enum('status', ['draft', 'issued', 'revoked'])->default('issued');
            $table->text('revocation_reason')->nullable();
            $table->timestamp('revoked_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->index('certificate_number');
            $table->index('verification_code');
            $table->index(['student_id', 'course_id']);
            $table->index('status');
            $table->index('issued_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
