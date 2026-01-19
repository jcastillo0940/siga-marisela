<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedBigInteger('kommo_id')->nullable()->unique()->after('id');
            $table->unsignedBigInteger('kommo_contact_id')->nullable()->after('kommo_id');
            $table->timestamp('last_synced_at')->nullable()->after('updated_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('kommo_user_id')->nullable()->after('id');
        });
    }

    public function down(): void {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['kommo_id', 'kommo_contact_id', 'last_synced_at']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kommo_user_id');
        });
    }
};