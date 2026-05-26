<?php
// database/migrations/xxxx_xx_xx_add_unit_and_status_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Relasi ke tabel status_sivitas
            $table->foreignId('status_sivitas_id')
                  ->nullable()
                  ->after('no_telp')
                  ->constrained('status_sivitas')
                  ->nullOnDelete();

            // Relasi ke tabel units
            $table->foreignId('unit_id')
                  ->nullable()
                  ->after('status_sivitas_id')
                  ->constrained('units')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['status_sivitas_id']);
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['status_sivitas_id', 'unit_id']);
        });
    }
};