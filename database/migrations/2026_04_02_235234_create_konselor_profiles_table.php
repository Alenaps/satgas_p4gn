<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('konselor_profiles', function (Blueprint $table) {
            $table->id();
            // Foreign key yang terhubung ke tabel users
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Field khusus konselor
            $table->text('deskripsi_biografi')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('konselor_profiles');
    }
};