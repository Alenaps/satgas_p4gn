<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'nama' => 'Admin Sistem',
            'jenis_kelamin' => 'Laki-laki',
            'npm_nip' => '190000000000000001',
            'no_telp' => '081234567890',
            'status_sivitas_id' => 1,
            'unit_id' => 1,
            'email' => 'testingappsatgaspgn@gmail.com',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // Konselor
        User::create([
            'nama' => 'Konselor P4GN',
            'jenis_kelamin' => 'Perempuan',
            'npm_nip' => '190000000000000002',
            'no_telp' => '082233445566',
            'status_sivitas_id' => 2,
            'unit_id' => 1,
            'email' => 'konselor@gmail.com',
            'password' => 'password123',
            'role' => 'konselor',
        ]);

        // Konsuli
        User::create([
            'nama' => 'Mahasiswa Konsuli',
            'jenis_kelamin' => 'Laki-laki',
            'npm_nip' => '2300000001',
            'no_telp' => '083344556677',
            'status_sivitas_id' => 3,
            'unit_id' => 1,
            'email' => 'konsuli@gmail.com',
            'password' => 'password123',
            'role' => 'konsuli',
        ]);
    }
}
