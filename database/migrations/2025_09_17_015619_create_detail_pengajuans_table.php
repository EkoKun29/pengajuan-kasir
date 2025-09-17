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
        Schema::create('detail_pengajuans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pengajuan')->nullable();
            $table->string('barang')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('harga')->nullable();
            $table->integer('total')->nullable();
            $table->string('berpajak')->nullable();
            $table->string('keterangan_pajak')->nullable();
            $table->string('status_persetujuan')->nullable();
            $table->string('keterangan_revisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengajuans');
    }
};
