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
        Schema::create('laporan_akhirs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id'); // Relasi ke usulan
            $table->string('dokumen_laporan_akhir'); // File dokumen akhir
            $table->string('dokumen_luaran_id'); // Relasi ke dokumen luaran
            $table->string('status');
            $table->timestamps();

            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_akhirs');
    }
};
