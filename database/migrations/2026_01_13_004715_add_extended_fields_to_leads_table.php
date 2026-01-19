<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('leads', function (Blueprint $table) {
            // Campos del Formulario Actual (CSV)
            $table->string('student_photo')->nullable()->after('last_name');
            $table->string('who_fills_form')->nullable()->after('student_photo'); // Alumna o Mamá
            $table->string('age')->nullable()->after('last_name');
            $table->date('birth_date_text')->nullable()->after('age'); // Para guardar la fecha del CSV
            $table->text('address_full')->nullable()->after('phone_secondary');
            $table->string('parent_phone')->nullable()->after('phone');
            $table->string('occupation')->nullable()->after('address_full');
            $table->string('parent_occupation')->nullable()->after('parent_phone');
            $table->boolean('has_previous_experience')->default(false);
            $table->text('previous_experience_detail')->nullable();
            $table->text('motivation')->nullable(); // ¿Qué te llamó la atención?
            $table->string('social_media_handle')->nullable(); // Instagram/Tiktok
            $table->text('medical_notes_lead')->nullable(); // Alergias, etc.

            // Campo para el Pago (Nuevo requerimiento)
            $table->string('payment_receipt_path')->nullable();
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            
            // Relación con el curso que desea tomar
            $table->foreignId('course_offering_id')->nullable()->constrained('course_offerings')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn([
                'student_photo', 'who_fills_form', 'age', 'birth_date_text', 
                'address_full', 'parent_phone', 'occupation', 'parent_occupation',
                'has_previous_experience', 'previous_experience_detail', 'motivation',
                'social_media_handle', 'medical_notes_lead', 'payment_receipt_path',
                'payment_status', 'course_offering_id'
            ]);
        });
    }
};