<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konseling_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konseli_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('konselor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'active', 'completed', 'rejected'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->text('catatan_konselor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konseling_sessions');
    }
};