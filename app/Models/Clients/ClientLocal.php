<?php

namespace App\Models\Clients;

use App\Models\Maquinas\Maquina;
use App\Models\Rutas\Ruta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientLocal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'clients_local';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'client_id',
        'local',

    ];

     /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function cliente() {
        return $this->belongsTo(\App\Models\Clients\Client::class,'client_id');
    }

    public function maquina() {
        return $this->belongsTo(Maquina::class,'maquina_id');
    }

    public function rutas() {
        return $this->belongsToMany(Ruta::class, 'rutas_locales','ruta_id','local_id');
    }

}
