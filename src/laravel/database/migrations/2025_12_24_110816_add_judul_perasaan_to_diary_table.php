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
        Schema::table('diary', function (Blueprint $table) {
            $table->string('judul_perasaan')->nullable()->after('emoji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diary', function (Blueprint $table) {
            $table->dropColumn('judul_perasaan');
        });
    }
};
