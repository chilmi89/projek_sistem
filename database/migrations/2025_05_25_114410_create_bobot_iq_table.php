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
        Schema::create('bobot_iq', function (Blueprint $table) {
            $table->id();
            $table->integer('nilai_min');
            $table->integer('nilai_max');
            $table->tinyInteger('bobot'); // 1 - 5
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_iq');
    }
};
