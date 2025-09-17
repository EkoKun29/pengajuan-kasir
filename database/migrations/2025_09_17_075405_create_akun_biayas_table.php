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
        Schema::create('akun_biayas', function (Blueprint $table) {
            $table->id();
            $table->string('sub_plot')->nullable();
            $table->string('keterangan_pajak')->nullable();
            $table->string('akun_keuangan')->nullable();
            $table->string('keperluan_beban')->nullable();
            $table->string('kode')->nullable();
            $table->string('plot_pengeluaran')->nullable();
            $table->string('jenis_transaksi')->nullable();
            $table->string('plot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun_biayas');
    }
};
