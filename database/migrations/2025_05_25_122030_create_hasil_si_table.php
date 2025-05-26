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
        Schema::create('hasil_si', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->decimal('total_c1', 5, 2);
            $table->decimal('total_c2', 5, 2);
            $table->decimal('total_c3', 5, 2);
            $table->decimal('total_c4', 5, 2);
            $table->decimal('total_si', 6, 2);
            $table->decimal('bobot_roc_iq', 4, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_si');
    }
};
