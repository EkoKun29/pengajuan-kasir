<?php

namespace App\Models;

use App\Models\DetailPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NamaBarang extends Model
{
    use HasFactory;
    
    protected $fillable = ['nama_barang'];

    /**
     * Relasi ke tabel detail_pengajuans
     */
    public function detailPengajuans()
    {
        return $this->hasMany(DetailPengajuan::class, 'nama_barang_id', 'id');
    }
}
