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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama_lengkap');
            $table->date('tanggal_lahir');
            $table->mediumText('alamat');
            $table->boolean('gender');
            $table->boolean('status')->default(true);
            $table->foreignId('id_user')
            ->nullable()
            ->constrained('users')
            ->onDelete('set null')
            ->onUpdate('cascade');
            $table->foreignId('id_thak_masuk')
            ->constrained('tahun_akademik')
            ->onDelete('restrict')
            ->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
