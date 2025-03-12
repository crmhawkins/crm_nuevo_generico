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

class Ruta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rutas';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function locales()
    {
        return $this->belongsToMany(ClientLocal::class, 'rutas_locales','local_id','ruta_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'rutas_usuario','usuario_id','ruta_id');
    }
}
