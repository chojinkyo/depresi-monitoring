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
            $table->string('swafoto_url');
            $table->longText('catatan')->nullable();
            $table->string('lampiran_url')->nullable();
            $table->enum('label', ['senang', 'marah', 'sedih', 'takut', 'jijik']);
            $table->enum('keterangan', ['hadir', 'izin', 'sakit', 'alpa'])->default('alpa');
            $table->foreignId('id_sesi_kbm')->constrained('sesi_kbms')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamp('tercatat_pada');
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
