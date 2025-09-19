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
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['nama_barang_id']);
        });
        
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            // ubah nama kolom dari nama_barang_id menjadi nama_barang
            $table->renameColumn('nama_barang_id', 'nama_barang');
        });

        Schema::table('detail_pengajuans', function (Blueprint $table) {
            // ubah tipe datanya dari unsignedBigInteger menjadi string
            $table->string('nama_barang')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            // ubah tipe datanya kembali ke unsignedBigInteger
            $table->unsignedBigInteger('nama_barang')->change();
            
            // ubah nama kolom kembali ke nama_barang_id
            $table->renameColumn('nama_barang', 'nama_barang_id');
            
            // Buat kembali foreign key constraint
            $table->foreign('nama_barang_id')
                  ->references('id')
                  ->on('nama_barangs')
                  ->onDelete('cascade');
        });
    }
};
