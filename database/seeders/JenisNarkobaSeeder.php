<?php

namespace Database\Seeders;

use App\Models\JenisNarkoba;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisNarkobaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama' => 'Sabu'],
            ['nama' => 'Ganja'],
            ['nama' => 'Kokain'],
            ['nama' => 'Heroin'],
            ['nama' => 'Methadone'],
            ['nama' => 'Ekstasi'],
            ['nama' => 'Belum diketahui'],
        ];

        foreach ($data as $item) {
            JenisNarkoba::firstOrCreate([
                'nama' => $item['nama']
            ]);
        }
    }
}
