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
        Schema::create('sinta_scores', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sintaid'); // Perbaiki 'biginteger' menjadi 'bigInteger'
            $table->string('nidn');     
            $table->string('sintascorev3');
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sinta_scores');
    }
};
