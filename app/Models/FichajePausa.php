<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichajePausa extends Model
{
    use HasFactory;

    protected $table = 'fichaje_pausas';

    protected $fillable = [
        'fichaje_id',
        'inicio',
        'fin',
    ];

    protected $casts = [
        'inicio' => 'datetime:H:i:s',
        'fin' => 'datetime:H:i:s',
    ];

    public function fichaje()
    {
        return $this->belongsTo(\App\Models\Fichaje::class, 'fichaje_id');
    }
}


