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
        Schema::create('siswas', function (Blueprint $table) {
            $table->string("nisn")->primary();
            $table->string("nama");
            $table->string("email")->unique();
            $table->string("no_telp")->unique();
            $table->string("avatar_url")->nullable();
            $table->date("tanggal_lahir");
            $table->date("tanggal_masuk");
            $table->enum('semester_masuk', ['genap', 'ganjil']);
            $table->enum("status_mental", ['normal', 'sedang', 'tinggi'])->default('normal');
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_kelas')->constrained('kelas');
            $table->foreignId('id_angkatan')->constrained('angkatans');
            $table->boolean('gender');
            $table->boolean('perlu_survey')->default(false);
            $table->boolean('kelulusan')->default(false);
            $table->mediumText("alamat");
            $table->unsignedTinyInteger("tingkat")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
