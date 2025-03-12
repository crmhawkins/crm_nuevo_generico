<?php

namespace App\Models\Almacenes;

use App\Models\Clients\Client;
use App\Models\Maquinas\Maquina;
use App\Models\Tpv\Caja;
use App\Models\Turnos\Turno;
use App\Models\Salones\Caja as Cabinas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Almacen extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'almacenes';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'direccion',

    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function maquinas()
    {
        return $this->hasMany(Maquina::class,'almacen_id');
    }

}
