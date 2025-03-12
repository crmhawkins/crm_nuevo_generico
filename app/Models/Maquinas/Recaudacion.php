<?php

namespace App\Models\Maquinas;

use App\Models\Clients\Client;
use App\Models\Tpv\Caja;
use App\Models\Turnos\Turno;
use App\Models\Salones\Caja as Cabinas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recaudacion extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'maquina_recaudacion';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'maquina_id',
        'billetes_500',
        'billetes_200',
        'billetes_100',
        'billetes_50',
        'billetes_20',
        'billetes_10',
        'billetes_5',
        'monedas_200',
        'monedas_100',
        'monedas_50',
        'monedas_20',
        'monedas_10',
        'monedas_5',
        'monedas_2',
        'monedas_1',
        'recaudado',
        'fecha_recaudacion',
        'cliente_id',
        'monto',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function maquina()
    {
        return $this->belongsTo(Maquina::class,'maquina_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Client::class,'cliente_id');
    }

}
