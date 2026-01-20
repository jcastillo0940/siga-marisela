<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['product', 'service'])->default('product');
            $table->decimal('price', 10, 2);
            $table->integer('stock')->default(0)->comment('Inventario disponible (0 para servicios)');
            $table->integer('min_stock')->default(0)->comment('Stock mínimo antes de alerta');
            $table->boolean('track_inventory')->default(false)->comment('Si false, no controla inventario');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
