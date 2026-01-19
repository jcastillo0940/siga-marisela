<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('module'); // students, courses, payments, etc.
            $table->string('action'); // view, create, edit, delete
            $table->string('name'); // students.view, payments.create
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['module', 'action']);
            $table->index('module');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};