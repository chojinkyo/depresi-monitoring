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
        Schema::create('log_harians', function (Blueprint $table) {
            $table->id();
            $table->string('id_siswa');
            $table->string('swafoto_url')->nullable();
            $table->string('lampiran_url')->nullable();
            $table->longText('catatan')->nullable();
            $table->enum('label', ['senang', 'marah', 'sedih', 'takut', 'jijik'])->default('senang');
            $table->enum('keterangan', ['hadir', 'izin', 'sakit', 'alpa'])->default('alpa');
            $table->foreignId('id_sesi_kbm')
            ->constrained('sesi_kbms')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreign('id_siswa')
            ->references('nisn')
            ->on('siswas');
            $table->timestamp('tercatat_pada')
            ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_harians');
    }
};
