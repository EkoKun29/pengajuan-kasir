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
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            // ubah nama kolom
            $table->renameColumn('nama_barang_id', 'nama_barang');
        });

        Schema::table('detail_pengajuans', function (Blueprint $table) {
            // ubah tipe datanya
            $table->string('nama_barang')->change();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            $table->unsignedBigInteger('nama_barang')->change();
            $table->renameColumn('nama_barang', 'nama_barang_id');
        });
    }
};
