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
        Schema::create('anggota_dosens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id'); // Relasi ke proposal
            $table->unsignedBigInteger('dosen_id'); // Relasi ke dosen
            $table->enum('jenis_skema', ['penelitian', 'pengabdian']); // Status anggota dosen
            $table->enum('status_anggota', ['ketua', 'anggota']); // Status anggota dosen
            $table->enum('status', ['terima', 'tolak','belum disetujui']); // Status anggota dosen
            $table->timestamps();
            // Menambahkan foreign key
            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
            $table->foreign('dosen_id')->references('id')->on('dosens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_dosens');
    }
};
