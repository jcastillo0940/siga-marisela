<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meal_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_offering_id')->constrained()->onDelete('cascade');
            $table->date('meal_date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->text('menu_description');
            $table->string('menu_image')->nullable();
            $table->integer('max_selections')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('meal_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meal_menu_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_vegetarian')->default(false);
            $table->boolean('is_vegan')->default(false);
            $table->boolean('is_gluten_free')->default(false);
            $table->integer('available_quantity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('meal_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->foreignId('meal_menu_id')->constrained()->onDelete('cascade');
            $table->foreignId('meal_option_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['enrollment_id', 'meal_menu_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_selections');
        Schema::dropIfExists('meal_options');
        Schema::dropIfExists('meal_menus');
    }
};
