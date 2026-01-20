<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('sale_code')->unique();
            $table->date('sale_date');
            $table->string('customer_name')->nullable()->comment('Nombre del cliente (opcional)');
            $table->string('customer_document')->nullable()->comment('Cédula/RUC del cliente');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('payment_method', ['efectivo', 'transferencia', 'tarjeta_credito', 'tarjeta_debito', 'yappy', 'otro', 'multiple'])->default('efectivo');
            $table->string('reference_number')->nullable();
            $table->foreignId('cash_register_id')->nullable()->constrained('cash_registers')->nullOnDelete();
            $table->foreignId('sold_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['completado', 'pendiente', 'cancelado', 'reembolsado'])->default('completado');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_name')->comment('Nombre del producto/servicio (guardado por si se elimina producto)');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->comment('quantity * unit_price - discount');
            $table->timestamps();
        });

        Schema::create('sale_payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->enum('method', ['efectivo', 'transferencia', 'tarjeta_credito', 'tarjeta_debito', 'yappy', 'otro']);
            $table->decimal('amount', 10, 2);
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_payment_methods');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
