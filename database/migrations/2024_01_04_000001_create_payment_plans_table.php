<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->enum('payment_type', ['contado', 'cuotas'])->default('contado');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->decimal('balance', 10, 2);
            $table->integer('number_of_installments')->default(1);
            $table->enum('periodicity', ['semanal', 'quincenal', 'mensual'])->nullable();
            $table->date('first_payment_date');
            $table->date('last_payment_date');
            $table->enum('status', ['pendiente', 'en_proceso', 'completado', 'vencido'])->default('pendiente');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};