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
        Schema::create('dash21s', function (Blueprint $table) {
            $table->id();
            $table->string('kuisioner_url');
            $table->boolean('depressed')->default(false);
            $table->string('id_siswa');
            $table->foreign('id_siswa')->references('nisn')->on('siswas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dash21s');
    }
};
