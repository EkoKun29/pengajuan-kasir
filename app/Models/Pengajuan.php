<?php

namespace App\Models;

use App\Models\DetailPengajuan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuans';
    
    protected $fillable = [
        'tgl_pengajuan',
        'no_surat',
        'nama_karyawan',
        'divisi',
        'plot',
        'keperluan_beban',
        'total'
    ];

    /**
     * Get the detail pengajuan associated with the pengajuan.
     */
    public function detailPengajuans()
    {
        return $this->hasMany(DetailPengajuan::class, 'id_pengajuan', 'id');
    }

    /**
     * Menghitung total dari semua detail pengajuan
     */
    public function getTotalAttribute()
    {
        return $this->detailPengajuans->sum('total');
    }

    /**
     * Menghitung total item dari semua detail pengajuan
     */
    public function getTotalItemAttribute()
    {
        return $this->detailPengajuans->count();
    }

    /**
     * Mengecek status persetujuan dari detail pengajuan
     * Return: approved, rejected, partial, pending
     */
    public function getStatusAttribute()
    {
        $details = $this->detailPengajuans;
        
        if ($details->isEmpty()) {
            return 'pending';
        }

        $approved = $details->where('status_persetujuan', 'approved')->count();
        $rejected = $details->where('status_persetujuan', 'rejected')->count();
        $pending = $details->filter(function($item) {
            return $item->status_persetujuan === null || $item->status_persetujuan === 'pending';
        })->count();

        if ($approved > 0 && $rejected > 0) {
            return 'partial';
        }
        
        if ($pending > 0) {
            return 'pending';
        }
        
        if ($approved === $details->count()) {
            return 'approved';
        }
        
        if ($rejected === $details->count()) {
            return 'rejected';
        }
        
        return 'pending';
    }
}
