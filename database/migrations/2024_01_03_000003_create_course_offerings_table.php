<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('code')->unique();
            $table->boolean('is_generation')->default(false);
            $table->string('generation_name')->nullable();
            $table->string('location');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->integer('duration_hours');
            $table->integer('min_students')->default(5);
            $table->integer('max_students')->default(20);
            $table->boolean('certificate_included')->default(true);
            $table->enum('status', ['programado', 'en_curso', 'completado', 'cancelado'])->default('programado');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_offerings');
    }
};