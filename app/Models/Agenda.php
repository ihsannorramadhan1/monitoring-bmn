<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'nomor_agenda',
        'satker_id',
        'jenis_pengelolaan_id',
        'tanggal_masuk',
        'tanggal_target',
        'tanggal_selesai',
        'status',
        'pic_id',
        'file_uploads',
        'notes',
        'created_by',
        'updated_by',
        'durasi_hari',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_target' => 'date',
        'tanggal_selesai' => 'date',
        'file_uploads' => 'array',
        'status' => 'string',
    ];

    public function satker()
    {
        return $this->belongsTo(Satker::class);
    }

    public function jenisPengelolaan()
    {
        return $this->belongsTo(JenisPengelolaan::class);
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function historyLogs()
    {
        return $this->hasMany(AgendaHistoryLog::class);
    }

    public function getIsDoneAttribute()
    {
        return in_array($this->status, ['disetujui', 'ditolak']);
    }

    public function getIsOverdueAttribute()
    {
        return !$this->isDone && now()->gt($this->tanggal_target);
    }
}
