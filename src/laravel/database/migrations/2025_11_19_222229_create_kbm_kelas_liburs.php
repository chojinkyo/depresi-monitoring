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
        Schema::create('kbm_kelas_liburs', function (Blueprint $table) {
            $table->foreignId('id_kelas')->constrained('kelas');
            $table->foreignId('id_kbm')->constrained('sesi_kbms');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kbm_kelas_liburs');
    }
};
