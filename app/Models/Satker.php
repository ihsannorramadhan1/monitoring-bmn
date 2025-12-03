<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satker extends Model
{
    protected $fillable = [
        'kode_satker',
        'nama_satker',
        'instansi_induk',
        'alamat',
        'pic_nama',
        'pic_kontak',
        'email',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
