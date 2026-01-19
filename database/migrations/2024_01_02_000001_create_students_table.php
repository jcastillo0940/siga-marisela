<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('phone_secondary')->nullable();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('birth_date')->nullable();
            $table->string('identification')->unique()->nullable();
            $table->enum('identification_type', ['cedula', 'passport', 'otro'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Panamá');
            
            // Contacto de emergencia
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Información académica/emocional
            $table->text('medical_notes')->nullable();
            $table->text('emotional_notes')->nullable();
            $table->text('goals')->nullable();
            
            // Status
            $table->enum('status', ['prospecto', 'activo', 'inactivo', 'graduado', 'retirado'])->default('prospecto');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};