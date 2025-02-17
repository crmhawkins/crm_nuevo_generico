<?php

namespace App\Models\Salones;

use App\Models\Clients\Client;
use App\Models\Tpv\Caja;
use App\Models\Turnos\Turno;
use App\Models\Salones\Caja as Cabinas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Salon extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'salones';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'direccion',
        'Apertura',
        'Cierre'
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function turnos()
    {
        return $this->hasMany(Turno::class,'salon_id');
    }

    public function cabinas()
    {
        return $this->hasMany(Cabinas::class,'salon_id');
    }

    public function cajas()
    {
       return $this->hasMany(Caja::class,'salon_id');
    }

}
