<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('units')->insert([
            [
                'nama_unit' => 'Fakultas Teknik',
                'kategori_unit' => 'Akademik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_unit' => 'Fakultas Ekonomi dan Bisnis',
                'kategori_unit' => 'Akademik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_unit' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'kategori_unit' => 'Akademik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_unit' => 'Biro Administrasi Akademik',
                'kategori_unit' => 'Administrasi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_unit' => 'Biro Keuangan',
                'kategori_unit' => 'Administrasi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nama_unit' => 'UPT Teknologi Informasi dan Komunikasi',
                'kategori_unit' => 'Administrasi',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}