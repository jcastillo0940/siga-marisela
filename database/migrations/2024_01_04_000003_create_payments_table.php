<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('payment_plan_id')->constrained('payment_plans')->cascadeOnDelete();
            $table->foreignId('payment_schedule_id')->nullable()->constrained('payment_schedules')->nullOnDelete();
            $table->string('payment_code')->unique();
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['efectivo', 'transferencia', 'tarjeta_credito', 'tarjeta_debito', 'yappy', 'otro'])->default('efectivo');
            $table->string('reference_number')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['completado', 'pendiente', 'rechazado', 'reembolsado'])->default('completado');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};