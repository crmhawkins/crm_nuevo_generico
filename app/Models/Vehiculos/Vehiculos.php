<?php

namespace App\Models\Vehiculos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculos extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'vehiculos';

    protected $fillable = [
        'matricula',
        'modelo',
        'fecha_compra',
        'fecha_itv',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

}

