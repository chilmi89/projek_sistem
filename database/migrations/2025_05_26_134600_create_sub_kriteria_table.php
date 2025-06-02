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
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kriteria'); // Mengacu pada 'kode' di tabel kriteria (C1, C2, dst)
            $table->string('sub_kriteria'); // Rentang nilai seperti '85 - 100', '75 - 85', dll.
            $table->integer('nilai'); // Nilai yang sesuai (5, 4, 3, 2)
            $table->timestamps();

            // Foreign key untuk menghubungkan dengan tabel kriteria
            $table->foreign('kode_kriteria')->references('kode')->on('kriteria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kriteria');
    }
};
