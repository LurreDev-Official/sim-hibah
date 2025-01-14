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
        Schema::create('luarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usulan_id'); // Assuming usulan_id is a foreign key
            $table->unsignedBigInteger('laporankemajuan_id'); // Assuming usulan_id is a foreign key
            $table->unsignedBigInteger('laporanakhir_id'); // Assuming usulan_id is a foreign key
            $table->string('judul'); // Assuming judul is a string
            $table->string('type'); // Assuming type is a string
            $table->enum('jenis_luaran', ['wajib', 'tambahan']);
            $table->string('url')->nullable(); // Assuming url can be nullable
            $table->string('file_loa')->nullable(); // Assuming file_loa can be nullable
            $table->timestamps();
            // If you want to add a foreign key constraint
            $table->foreign('usulan_id')->references('id')->on('usulans')->onDelete('cascade');
            // $table->foreign('laporankemajuan_id')->references('id')->on('laporan_kemajuans')->onDelete('cascade');
            // $table->foreign('laporanakhir_id')->references('id')->on('laporan_akhirs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luarans');
    }
};
