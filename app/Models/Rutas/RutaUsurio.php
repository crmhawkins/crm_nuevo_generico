<?php

namespace App\Models\Rutas;

use App\Models\Clients\Client;
use App\Models\Clients\ClientLocal;
use App\Models\Tpv\Caja;
use App\Models\Turnos\Turno;
use App\Models\Salones\Caja as Cabinas;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RutaUsuaio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rutas_usuario';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'ruta_id',
        'usuario_id',
        'dia'
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }

    public function usuarios()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
