<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('opened_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('opening_amount', 10, 2)->default(0);
            $table->decimal('closing_amount', 10, 2)->nullable();
            $table->decimal('expected_amount', 10, 2)->nullable();
            $table->decimal('difference', 10, 2)->nullable();
            $table->enum('status', ['abierta', 'cerrada'])->default('abierta');
            $table->dateTime('opened_at');
            $table->dateTime('closed_at')->nullable();
            $table->text('opening_notes')->nullable();
            $table->text('closing_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_registers');
    }
};