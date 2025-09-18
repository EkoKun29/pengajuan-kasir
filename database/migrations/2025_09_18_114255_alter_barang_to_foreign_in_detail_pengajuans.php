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
            // hapus kolom lama "barang"
            $table->dropColumn('barang');

            // tambahkan kolom relasi ke nama_barangs
            $table->unsignedBigInteger('nama_barang_id')->nullable()->after('id_pengajuan');

            // buat foreign key
            $table->foreign('nama_barang_id')
                  ->references('id')
                  ->on('nama_barangs')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengajuans', function (Blueprint $table) {
            
            $table->dropForeign(['nama_barang_id']);
            $table->dropColumn('nama_barang_id');
            
            $table->string('barang')->nullable()->after('id_pengajuan');
        });
    }
};
