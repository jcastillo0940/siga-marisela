<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Primero eliminar foreign keys que dependen del índice
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
        });

        // Ahora eliminar el índice unique
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique(['student_id', 'course_offering_id']);
        });

        // Recrear las foreign keys
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->cascadeOnDelete();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('enrollment_id')->references('id')->on('enrollments')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unique(['student_id', 'course_offering_id']);
        });
    }
};