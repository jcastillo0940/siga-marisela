<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modificar el enum para incluir 'multiple' como opción
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('efectivo', 'transferencia', 'tarjeta_credito', 'tarjeta_debito', 'yappy', 'otro', 'multiple') DEFAULT 'efectivo'");
    }

    public function down(): void
    {
        // Revertir el cambio
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('efectivo', 'transferencia', 'tarjeta_credito', 'tarjeta_debito', 'yappy', 'otro') DEFAULT 'efectivo'");
    }
};
