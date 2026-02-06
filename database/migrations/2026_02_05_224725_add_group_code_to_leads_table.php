<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('group_code')->nullable()->index()->after('source');
            // Campo para guardar el precio pactado especial (ej: 300.00 en lugar de 350.00)
            $table->decimal('agreed_price', 10, 2)->nullable()->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['group_code', 'agreed_price']);
        });
    }
};