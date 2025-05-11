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
            $table->text('deskripsi')->nullable();
            $table->decimal('bobot', 5, 2); // Menggunakan decimal agar lebih akurat
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
