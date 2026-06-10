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
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->foreign('konselor_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('tindak_lanjuts', function (Blueprint $table) {
            $table->dropForeign(['konselor_id']);
        });
    }
};
