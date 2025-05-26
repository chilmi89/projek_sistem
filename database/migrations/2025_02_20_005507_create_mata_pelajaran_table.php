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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id(); // Secara default id sudah menjadi bigIncrements
            $table->string('nama_mapel');
            $table->foreignId('kriteria_id')  // Relasi ke tabel Kriteria
                ->constrained('kriteria')   // Kriteria adalah tabel yang di-refer
                ->onDelete('cascade'); 
            $table->string('kode_kriteria')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
