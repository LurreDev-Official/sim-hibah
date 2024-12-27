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
        Schema::create('usulan_perbaikans', function (Blueprint $table) {
            $table->id();
            $table->string('dokumen_usulan')->nullable();
            $table->unsignedBigInteger('penilaian_id'); // Relasi ke tabel penilaian
            $table->enum('status', ['revisi','sudah diperbaiki','didanai', 'tidak didanai']); // Status usulan perbaikan
            $table->unsignedBigInteger('usulan_id'); // Relasi ke tabel usulan
            $table->timestamps();
            // Menambahkan foreign key
            $table->foreign('penilaian_id')->references('id')->on('penilaian_reviewers')->onDelete('cascade');
            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_perbaikans');
    }
};
