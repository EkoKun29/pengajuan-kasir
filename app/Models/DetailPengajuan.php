<?php

namespace App\Models;

use App\Models\Pengajuan;
use App\Models\NamaBarang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailPengajuan extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuans';
    
    protected $fillable = [
        'id_pengajuan',
        'nama_barang',
        'qty',
        'harga',
        'total',
        'berpajak',
        'keterangan_pajak',
        'status_persetujuan',
        'keterangan_revisi',
        'keperluan_beban'
    ];

    /**
     * Boot function untuk model
     */
    public static function boot()
    {
        parent::boot();
        
        // Menghitung otomatis total berdasarkan qty * harga sebelum menyimpan
        static::saving(function ($detail) {
            if ($detail->qty && $detail->harga) {
                $detail->total = $detail->qty * $detail->harga;
            }
        });
    }

    /**
     * Get the pengajuan that owns the detail_pengajuan.
     */
    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class, 'id_pengajuan', 'id');
    }

    /**
     * Relasi ke tabel nama_barangs
     */
    public function namaBarang()
    {
        return $this->belongsTo(NamaBarang::class, 'nama_barang', 'id');
    }
}
