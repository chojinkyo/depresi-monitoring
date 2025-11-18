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
        Schema::create('sesi_liburs', function (Blueprint $table) {
            $table->id();
            $table->boolean('status', ['libur', 'kbm', 'daring', 'masuk']);
            $table->foreignId('id_kelas')->constrained('kelas');
            $table->foreignId('id_kalender')->constrained('kalender_akademiks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sesi_liburs');
    }
};
