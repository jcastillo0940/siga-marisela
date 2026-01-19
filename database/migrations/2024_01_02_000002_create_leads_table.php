<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('phone_secondary')->nullable();
            $table->enum('source', ['web', 'referido', 'redes_sociales', 'llamada', 'evento', 'otro'])->default('web');
            $table->string('source_detail')->nullable();
            $table->enum('status', ['nuevo', 'contactado', 'interesado', 'negociacion', 'inscrito', 'perdido'])->default('nuevo');
            $table->text('notes')->nullable();
            $table->text('interests')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('converted_to_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};