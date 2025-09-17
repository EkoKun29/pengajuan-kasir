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
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->string('tgl_pengajuan')->nullable();
            $table->string('no_surat')->nullable();
            $table->string('nama_karyawan')->nullable();
            $table->string('divisi')->nullable();
            $table->string('plot')->nullable();
            $table->string('keperluan_beban')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuans');
    }
};
