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
        Schema::table('siswa', function (Blueprint $table) {
            $table->integer('nilai_mtk')->nullable(); // Mathematics score
            $table->integer('nilai_ipa')->nullable(); // Natural Sciences score
            $table->integer('nilai_ips')->nullable(); // Social Sciences score
            $table->integer('nilai_bing')->nullable(); // English score
            $table->integer('nilai_tes_iq')->nullable(); // IQ Test score
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['nilai_mtk', 'nilai_ipa', 'nilai_ips', 'nilai_bing', 'nilai_tes_iq']);
        });
    }
};
