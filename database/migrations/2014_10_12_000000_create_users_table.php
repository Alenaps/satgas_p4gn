<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 60);
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('npm_nip', 18)->unique();
            $table->string('no_telp', 15)->nullable();
            $table->enum('status_sivitas', ['Mahasiswa', 'Dosen', 'Tendik'])->nullable();
            $table->string('email', 40)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');    
            $table->enum('role', ['admin', 'konselor', 'konsuli'])->default('konsuli');
            $table->string('foto', 255)->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
