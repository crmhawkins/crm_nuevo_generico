<?php

namespace App\Models\Neveras;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Neveras extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'control_temperatura';

    protected $fillable = [
        'nombre',
        'temperatura_actual',
        'temperatura_maxima',
        'temperatura_minima',
        'fecha',
        'hora',
        'observaciones',
        'admin_user_id',
        'salon_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
