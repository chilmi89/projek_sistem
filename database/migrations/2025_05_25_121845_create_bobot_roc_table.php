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
        Schema::create('bobot_roc', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('bobot'); // dari 1 - 5
            $table->decimal('nilai_roc', 4, 2); // Misal: 0.46
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_roc');
    }
};
