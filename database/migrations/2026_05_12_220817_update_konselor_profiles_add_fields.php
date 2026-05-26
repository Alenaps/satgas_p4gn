<?php
// database/migrations/xxxx_update_konselor_profiles_add_fields.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konselor_profiles', function (Blueprint $table) {
            // Hapus kolom lama deskripsi_biografi dulu (akan diganti bio_singkat)
            $table->dropColumn('deskripsi_biografi');

            // Tambah semua field dari ERD
            $table->string('nomor_lisensi', 100)->nullable()->after('user_id');
            $table->string('spesialisasi', 150)->nullable()->after('nomor_lisensi');
            $table->integer('pengalaman_kerja')->nullable()->after('spesialisasi')
                  ->comment('Dalam satuan tahun');
            $table->string('pendidikan_terakhir', 100)->nullable()->after('pengalaman_kerja');
            $table->boolean('sertifikasi_P4GN')->default(false)->after('pendidikan_terakhir');
            $table->text('bio_singkat')->nullable()->after('sertifikasi_P4GN');

            // Foreign key ke tabel instansi
            $table->foreignId('id_instansi')
                  ->nullable()
                  ->after('bio_singkat')
                  ->constrained('instansi')
                  ->nullOnDelete();

            // Foreign key ke tabel jabatan
            $table->foreignId('id_jabatan')
                  ->nullable()
                  ->after('id_instansi')
                  ->constrained('jabatan')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('konselor_profiles', function (Blueprint $table) {
            $table->dropForeign(['id_instansi']);
            $table->dropForeign(['id_jabatan']);
            $table->dropColumn([
                'nomor_lisensi',
                'spesialisasi',
                'pengalaman_kerja',
                'pendidikan_terakhir',
                'sertifikasi_P4GN',
                'bio_singkat',
                'id_instansi',
                'id_jabatan',
            ]);
            // Kembalikan kolom lama
            $table->text('deskripsi_biografi')->nullable();
        });
    }
};