<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['cocina', 'reposteria', 'panaderia', 'barista', 'otro'])->default('cocina');
            $table->enum('level', ['basico', 'intermedio', 'avanzado', 'especializado'])->default('basico');
            $table->integer('duration_hours');
            $table->integer('duration_weeks')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('max_students')->default(20);
            $table->integer('min_students')->default(5);
            $table->text('requirements')->nullable();
            $table->text('objectives')->nullable();
            $table->text('content_outline')->nullable();
            $table->text('materials_included')->nullable();
            $table->boolean('certificate_included')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};