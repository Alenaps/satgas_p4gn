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
            $table->string('nama_pelapor');
            $table->string('peran_pelapor');
            $table->string('nip')->nullable();
            $table->string('telp_pelapor')->nullable();
            $table->string('email')->nullable();
            $table->string('jk_pelapor');

            // Terlapor
            $table->string('nama_terlapor');
            $table->string('peran_terlapor');
            $table->string('telp_terlapor')->nullable();
            $table->string('jk_terlapor');
            $table->string('alamat_terlapor')->nullable();

            // Kejadian
            $table->string('lokasi');
            $table->string('foto_lokasi')->nullable();
            $table->date('tanggal');
            $table->string('jenis_narkoba')->nullable();
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
