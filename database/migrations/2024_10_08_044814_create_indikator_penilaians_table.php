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
        Schema::create('indikator_penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kriteria_id'); // Relasi ke kriteria
            $table->string('nama_indikator'); // Nama indikator
            $table->integer('jumlah_bobot'); // Jumlah bobot
            $table->timestamps();
            // Menambahkan foreign key
            $table->foreign('kriteria_id')->references('id')->on('kriteria_penilaians')->onDelete('cascade');
   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indikator_penilaians');
    }
};
