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
        Schema::create('hasil_weight_product', function (Blueprint $table) {
            $table->id();
            $table->string('nama_siswa');
            $table->float('c1');
            $table->float('c2');
            $table->float('c3');
            $table->float('c4');
            $table->float('c5');

            $table->float('c1_pow');
            $table->float('c2_pow');
            $table->float('c3_pow');
            $table->float('c4_pow');
            $table->float('c5_pow');

            $table->float('nilai_s');

            $table->float('c1_bagi');
            $table->float('c2_bagi');
            $table->float('c3_bagi');
            $table->float('c4_bagi');
            $table->float('c5_bagi');

            $table->string('rekomendasi_kriteria');
            $table->float('nilai_bagi_tertinggi');

            $table->string('alokasi_kelas')->nullable(); // Contoh: C1-1 / Kelompok 1 / Kelas 1

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_weight_products');
    }
};
