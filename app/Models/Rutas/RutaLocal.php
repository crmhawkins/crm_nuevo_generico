<?php

namespace App\Models\Rutas;

use App\Models\Clients\ClientLocal;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RutaLocal extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'rutas_locales';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'ruta_id',
        'local_id',
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function local()
    {
        return $this->belongsTo(ClientLocal::class, 'local_id');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id');
    }
}
