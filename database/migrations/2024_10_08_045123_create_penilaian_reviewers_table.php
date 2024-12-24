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
        Schema::create('penilaian_reviewers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id'); // Relasi ke tabel proposal
            $table->string('status_penilaian'); // Status penilaian
            $table->unsignedBigInteger('reviewer_id'); // Relasi ke tabel reviewer
            $table->string('total_nilai'); // Status penilaian
            $table->timestamps();
            // Menambahkan foreign key
            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('reviewers')->onDelete('cascade');

        });

        Schema::create('form_penilaians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('penilaian_reviewers_id');
            $table->unsignedBigInteger('id_kriteria')->nullable(); // Relasi ke kriteria penilaian (bisa null)
            $table->unsignedBigInteger('id_indikator')->nullable(); // Relasi ke kriteria penilaian (bisa null)
            $table->text('catatan')->nullable(); // Catatan terkait penilaian
            $table->string('sub_total')->nullable(); /// Status penilaian
            $table->string('status'); // Status penilaian
            $table->timestamps();
            // Menambahkan foreign key
            $table->foreign('id_kriteria')->references('id')->on('kriteria_penilaians')->onDelete('cascade');
            $table->foreign('id_indikator')->references('id')->on('kriteria_penilaians')->onDelete('cascade');
            $table->foreign('penilaian_reviewers_id')->references('id')->on('indikator_penilaians')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_reviewers');
        Schema::dropIfExists('form_penilaians');

    }
};
