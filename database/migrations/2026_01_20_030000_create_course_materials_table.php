<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_offering_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['pdf', 'video', 'link', 'image', 'other']);
            $table->string('file_path')->nullable(); // Para archivos subidos
            $table->string('external_url')->nullable(); // Para links externos
            $table->integer('file_size')->nullable(); // En bytes
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_materials');
    }
};
