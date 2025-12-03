<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaHistoryLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'agenda_id',
        'status_old',
        'status_new',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
