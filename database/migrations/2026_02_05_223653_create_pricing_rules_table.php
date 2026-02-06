<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            // Vinculamos la regla a una oferta de curso específica
            $table->foreignId('course_offering_id')->constrained()->onDelete('cascade');
            
            // Definir el rango de estudiantes para que aplique la regla
            // Ej: min=2, max=2 (Solo parejas exactas)
            // Ej: min=3, max=null (De 3 personas en adelante)
            $table->integer('min_students'); 
            $table->integer('max_students')->nullable(); 
            
            // Tipos de descuento según tu requerimiento:
            // 1. fixed_total_price: El grupo entero paga un monto específico (Ej: $500 por los 2).
            // 2. percentage: Se descuenta un % a cada estudiante (Ej: 5%).
            // 3. fixed_discount: Se descuenta un monto fijo a cada estudiante (Ej: $100 menos c/u).
            $table->enum('type', ['fixed_total_price', 'percentage', 'fixed_discount']);
            
            // El valor numérico ($500, 5, o $100 dependiendo del tipo)
            $table->decimal('value', 10, 2); 
            
            $table->string('name')->nullable(); // Ej: "Promo Parejas", "Grupo Amigos"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Agregamos 'group_code' a la tabla enrollments
        // Esto nos servirá para saber qué alumnos pertenecen al mismo grupo de pago
        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('group_code')->nullable()->index()->after('enrollment_code');
        });
    }

    public function down(): void
    {
        // Al revertir, primero quitamos la columna y luego la tabla
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('group_code');
        });
        
        Schema::dropIfExists('pricing_rules');
    }
};