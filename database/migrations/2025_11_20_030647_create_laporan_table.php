<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();

            // Pelapor
            $table->string('nama_pelapor', 100);
            $table->enum('peran_pelapor',['Mahasiswa', 'Dosen', 'Tendik']);
            $table->string('npm_nip', 18)->nullable();
            $table->string('telp_pelapor', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->enum('jk_pelapor',['Laki-laki', 'Perempuan']);

            // Terlapor
            $table->string('nama_terlapor', 100);
            $table->enum('peran_terlapor', ['Mahasiswa', 'Dosen', 'Tendik']);
            $table->string('telp_terlapor', 15)->nullable();
            $table->enum('jk_terlapor',['Laki-laki', 'Perempuan']);
            $table->string('alamat_terlapor', 255)->nullable();

            // Kejadian
            $table->string('lokasi', 150);
            $table->string('foto_lokasi', 255)->nullable();
            $table->date('tanggal');
            $table->string('jenis_narkoba', 100)->nullable();
            $table->text('kronologi')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
