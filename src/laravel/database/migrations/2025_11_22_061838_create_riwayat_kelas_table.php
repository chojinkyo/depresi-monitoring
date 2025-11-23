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
        Schema::create('riwayat_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kelas')
            ->constrained('kelas')
            ->onDelete('restrict')
            ->onUpdate('cascade');
            $table->foreignId('id_siswa')
            ->constrained('siswa')
            ->onDelete('restrict')
            ->onUpdate('cascade');
            $table->foreignId('id_thak')
            ->constrained('tahun_akademik')
            ->onDelete('restrict')
            ->onUpdate('cascade');
            $table->enum('status', ['MM', 'MK', 'NW', 'LL', 'Sl', 'CL'])->default('NW');
            $table->boolean('active')->default(false);
            $table->timestamp('waktu')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelas');
    }
};
