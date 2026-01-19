<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_offering_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_offering_id')->constrained('course_offerings')->cascadeOnDelete();
            $table->date('class_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_cancelled')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_offering_dates');
    }
};