<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkunBiaya extends Model
{
    protected $table = 'akun_biayas';
    protected $fillable = [
        'sub_plot',
        'keterangan_pajak',
        'akun_keuangan',
        'keperluan_beban',
        'kode',
        'plot_pengeluaran',
        'jenis_transaksi',
        'plot',
    ];
}
