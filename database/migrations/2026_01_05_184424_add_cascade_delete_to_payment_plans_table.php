<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agregar foreign key con CASCADE (no existía antes)
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->foreign('enrollment_id')
                  ->references('id')
                  ->on('enrollments')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payment_plans', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
        });
    }
};