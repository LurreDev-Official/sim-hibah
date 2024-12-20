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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Relasi ke tabel users
            $table->string('nidn');
            $table->integer('kuota_proposal');
            $table->integer('jumlah_proposal');
            $table->string('fakultas');
            $table->string('prodi');
            $table->enum('status', [
                'anggota', 
                'ketua']);
            $table->integer('score_sinta')->nullable(); // Skor Sinta, bisa null
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
