<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('course_offering_id')->constrained('course_offerings')->cascadeOnDelete();
            $table->string('enrollment_code')->unique();
            $table->date('enrollment_date');
            $table->enum('status', ['inscrito', 'en_curso', 'completado', 'retirado', 'suspendido'])->default('inscrito');
            $table->decimal('price_paid', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('certificate_issued')->default(false);
            $table->date('certificate_issued_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['student_id', 'course_offering_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};