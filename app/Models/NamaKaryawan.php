<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaKaryawan extends Model
{
    protected $table = 'nama_karyawans';

    protected $fillable = [
        'nama_karyawan',
        'divisi',
    ];
}
