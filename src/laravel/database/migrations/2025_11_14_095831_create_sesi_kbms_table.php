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
        Schema::create('sesi_kbms', function (Blueprint $table) {
            $table->id();
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_akhir');
            $table->dateTime('batas_input_mulai');
            $table->dateTime('batas_input_akhir');
            $table->unsignedTinyInteger("tingkat");
            $table->mediumText('catatan')->nullable();
            $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajarans');
            $table->enum('status', ['ditunda', 'dibuka', 'ditutup', 'dibatalkan']);
            $table->enum('pemakaian', ['all', 'some']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_kbms');
    }
};
