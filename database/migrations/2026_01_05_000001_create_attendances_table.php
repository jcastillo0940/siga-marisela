<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->onDelete('cascade');
            $table->foreignId('course_session_id')->constrained('course_offering_dates')->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('checked_out_at')->nullable();
            $table->string('check_in_method')->nullable(); // 'manual', 'qr', 'admin', 'auto'
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users'); // quien registró
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->unique(['enrollment_id', 'course_session_id']);
            $table->index('status');
            $table->index('checked_in_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
