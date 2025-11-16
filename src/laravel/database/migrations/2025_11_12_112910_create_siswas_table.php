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
            $table->mediumText("alamat");
            $table->date("tanggal_lahir");
            $table->string("avatar_url")->nullable();
            $table->unsignedSmallInteger("tahun_masuk");
            $table->unsignedTinyInteger("tingkat")->default(1);
            $table->foreignId('id_user')->constrained('users');
            $table->enum("status_mental", ['normal', 'sedang', 'tinggi'])->default('normal');
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
