<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PublikasiModel;
use Illuminate\Support\Str;

class PublikasiSeeder extends Seeder
{
   public function run(): void
    {
        PublikasiModel::create([
            'judul' => 'Dampak Penyalahgunaan Narkoba Pada Remaja',
            'slug' => Str::slug('Dampak Penyalahgunaan Narkoba Pada Remaja'),
            'isi' => '<p>Isi artikel lengkap tentang dampak narkoba...</p>',
            'ringkasan' => 'Artikel ini membahas dampak narkoba pada kesehatan fisik dan mental remaja.',
            'kutipan' => 'Narkoba memberikan dampak signifikan terhadap perkembangan remaja.',
            'keyword' => 'narkoba, remaja, pencegahan',
            'kategori' => 'Jurnal',
            'status' => 'Publish',
            'label' => 'Peringatan',
            'thumbnail' => null,
            'user_id' => 2, 
        ]);

        PublikasiModel::create([
            'judul' => 'Strategi Pencegahan Penyalahgunaan Narkoba di Kampus',
            'slug' => Str::slug('Strategi Pencegahan Penyalahgunaan Narkoba di Kampus'),
            'isi' => '<p>Artikel pencegahan penyalahgunaan narkoba di kampus...</p>',
            'ringkasan' => 'Strategi dan program pencegahan di lingkungan kampus.',
            'kutipan' => 'Pencegahan dimulai dari edukasi mahasiswa.',
            'keyword' => 'kampus, satgas, pencegahan',
            'kategori' => 'Artikel',
            'status' => 'Publish',
            'label' => 'Edukasi',
            'thumbnail' => null,
            'user_id' => 2,
        ]);

        PublikasiModel::create([
            'judul' => 'Pemerintah Tingkatkan Program Rehabilitasi Gratis',
            'slug' => Str::slug('Pemerintah Tingkatkan Program Rehabilitasi Gratis'),
            'isi' => '<p>Pemerintah memperluas program rehabilitasi...</p>',
            'ringkasan' => 'Layanan rehabilitasi gratis kini tersedia di lebih banyak daerah.',
            'kutipan' => 'Rehabilitasi adalah langkah penyelamatan jiwa.',
            'keyword' => 'rehabilitasi, pemerintah, narkoba',
            'kategori' => 'Berita',
            'status' => 'Publish',
            'label' => 'Kesehatan',
            'thumbnail' => null,
            'user_id' => 2,
        ]);
    }

}
