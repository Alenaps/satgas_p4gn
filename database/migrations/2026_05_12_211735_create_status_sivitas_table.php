<?php
// database/migrations/xxxx_xx_xx_create_status_sivitas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_sivitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique(); // Mahasiswa, Dosen, Tendik
            $table->timestamps();
        });

        // Isi data awal otomatis saat migrasi dijalankan
        DB::table('status_sivitas')->insert([
            ['nama' => 'Mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Dosen',     'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Tendik',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('status_sivitas');
    }
};