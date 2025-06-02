<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hasil_perhitungan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hasil_bobot_id'); // Relasi ke siswa/alternatif
            $table->string('kode_kriteria');              // C1, C2, dll
            $table->double('nilai_asli', 8, 4);
            $table->double('bobot_roc', 8, 4);
            $table->double('nilai_terbobot', 12, 6);
            $table->double('hasil_s', 12, 6)->nullable();
            $table->timestamps();

            $table->foreign('hasil_bobot_id')->references('id')->on('hasil_bobot')->onDelete('cascade');
            $table->foreign('kode_kriteria')->references('kode')->on('kriteria')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_perhitungan');
    }
};
