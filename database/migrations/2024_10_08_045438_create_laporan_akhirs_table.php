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
            $table->unsignedBigInteger('ketua_dosen_id'); // Relasi ke dosen
            $table->unsignedBigInteger('usulan_id'); // Relasi ke usulan
            $table->string('dokumen_laporan_akhir'); // File dokumen akhir
            $table->enum('jenis', ['Penelitian', 'Pengabdian']);
            $table->string('status');
            $table->timestamps();
            $table->foreign('ketua_dosen_id')->references('id')->on('dosens')->onDelete('cascade');
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
