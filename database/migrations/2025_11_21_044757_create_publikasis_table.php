<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 191);
            $table->string('slug')->unique();
            $table->longText('isi');
            $table->text('ringkasan');
            $table->text('kutipan')->nullable();
            $table->text('keyword')->nullable();
            
            $table->enum('kategori', ['Artikel', 'Jurnal', 'Berita']); 
            $table->enum('status', ['Draft', 'Publish']);
            $table->string('label', 50)->nullable();
            $table->string('thumbnail')->nullable(); 
            $table->timestamps();
            $table->foreignId('user_id');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publikasis');
    }
};
