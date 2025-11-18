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
        Schema::create('tahun_ajarans', function (Blueprint $table) {
            $table->id();
            $table->year('tahun_mulai');
            $table->year('tahun_akhir');
            $table->boolean('is_aktif')->default(false);
            $table->enum('semester', ['ganjil', 'genap'])->default('ganjil');
            $table->enum('status', ['aktif', 'nonaktif', 'ditutup', 'arsip'])->default('nonaktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajarans');
    }
};
