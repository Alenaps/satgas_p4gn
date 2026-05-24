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
        Schema::table('laporans', function (Blueprint $table) {
            $table->enum('jenis_kasus', ['Pengguna', 'Pengedar', 'Kurir', 'Bandar'])->after('id');
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('laporans', function (Blueprint $table) {
            $table->dropColumn('jenis_kasus');
        });
    }

};
