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
        Schema::create('usulans', function (Blueprint $table) {
            $table->id();
            $table->string('judul_usulan');
            $table->string('jenis_skema'); // Pengabdian atau penelitian
            $table->year('tahun_pelaksanaan');
            $table->unsignedBigInteger('ketua_dosen_id'); // Relasi ke dosen
            $table->foreign('ketua_dosen_id')->references('id')->on('dosens')->onDelete('cascade');

            $table->string('dokumen_usulan'); // File dokumen usulan
            $table->enum('status', [
                'draft',        // Dokumen masih dalam tahap penyusunan oleh mahasiswa
                'submitted',    // Dokumen telah diajukan oleh mahasiswa
                'review',       // Dokumen sedang dalam tahap review oleh pembimbing/dosen
                'revision',     // Dosen meminta revisi terhadap dokumen yang diajukan
                'waiting approved',     // Dokumen telah disetujui/direkomendasikan oleh pembimbing
                'approved',     // Dokumen telah disetujui/direkomendasikan oleh pembimbing
                'rejected',     // Dokumen ditolak oleh pembimbing atau pihak berwenang
            ]);
            $table->string('rumpun_ilmu');
            $table->string('bidang_fokus');
            $table->string('tema_penelitian');
            $table->string('lokasi_penelitian');
            $table->string('topik_penelitian');
            $table->string('lama_kegiatan');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulans');
    }
};
