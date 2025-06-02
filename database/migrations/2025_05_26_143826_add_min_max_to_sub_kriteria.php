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
        Schema::table('sub_kriteria', function (Blueprint $table) {
            $table->integer('nilai_min')->after('kode_kriteria')->nullable();
            $table->integer('nilai_max')->after('nilai_min')->nullable();
            $table->index(['kode_kriteria', 'nilai_min', 'nilai_max']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_kriteria', function (Blueprint $table) {
            $table->dropIndex(['kode_kriteria', 'nilai_min', 'nilai_max']);
            $table->dropColumn(['nilai_min', 'nilai_max']);
        });
    }
};
