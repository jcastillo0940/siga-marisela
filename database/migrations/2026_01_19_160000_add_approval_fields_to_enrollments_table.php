<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->boolean('requires_approval')->default(false)->after('status')
                ->comment('Si true, la inscripción requiere aprobación de dirección');

            $table->boolean('management_approved')->nullable()->after('requires_approval')
                ->comment('true = aprobada, false = rechazada, null = pendiente');

            $table->foreignId('approved_by')->nullable()->after('management_approved')
                ->constrained('users')->nullOnDelete()
                ->comment('Usuario que aprobó/rechazó la inscripción');

            $table->timestamp('approved_at')->nullable()->after('approved_by')
                ->comment('Fecha y hora de la aprobación/rechazo');

            $table->text('approval_notes')->nullable()->after('approved_at')
                ->comment('Notas o razón de la aprobación/rechazo');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'requires_approval',
                'management_approved',
                'approved_by',
                'approved_at',
                'approval_notes',
            ]);
        });
    }
};
