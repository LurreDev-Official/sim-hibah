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
        Schema::create('anggota_mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id'); // Relasi ke proposal
            $table->string('nim'); // Nomor Induk Mahasiswa
            $table->string('nama_lengkap'); // Nama lengkap mahasiswa
            $table->string('fakultas'); // Fakultas
            $table->string('prodi'); // Program Studi
            $table->timestamps();
            // Menambahkan foreign key
            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_mahasiswas');
    }
};
