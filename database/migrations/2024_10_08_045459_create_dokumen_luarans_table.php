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
        Schema::create('dokumen_luarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laporan_akhirs_id'); // Nama dokumen luaran
            $table->string('nama'); // Nama dokumen luaran
            $table->string('jenis_luaran'); // Jenis luaran
            $table->string('dokumen_pdf'); // Nama file PDF dokumen
            $table->string('link'); // Link terkait dokumen
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_luarans');
    }
};
