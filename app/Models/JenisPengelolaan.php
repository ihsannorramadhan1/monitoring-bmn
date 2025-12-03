<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPengelolaan extends Model
{
    protected $fillable = [
        'kode',
        'nama_jenis',
        'deskripsi',
        'target_hari',
        'kategori',
        'status',
    ];

    protected $casts = [
        'target_hari' => 'integer',
        'status' => 'string',
        'kategori' => 'string',
    ];
}
