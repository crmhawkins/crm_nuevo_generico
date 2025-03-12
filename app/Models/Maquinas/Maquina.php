<?php

namespace App\Models\Maquinas;

use App\Models\Almacenes\Almacen;
use App\Models\Clients\ClientLocal;
use App\Models\Salones\Salon;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maquina extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'maquinas';

    protected $fillable = [
        'nombre',
        'n_serie',
        'categoria_id',
        'otros',
        'almacen_id',
        'salon_id',
        'local_id',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function categoria()
    {
        return $this->belongsTo(MaquinaCategoria::class, 'categoria_id');
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_id');
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id');
    }

    public function local()
    {
        return $this->belongsTo(ClientLocal::class, 'local_id');
    }

    public function recaudacion()
    {
        return $this->hasMany(Recaudacion::class, 'maquina_id');
    }

    protected static function boot()
    {
        parent::boot();

        // Antes de guardar, se ejecuta esta lógica.
        static::saving(function ($maquina) {
            $maquina->adjustLocation();
        });
    }

    /**
     * Ajusta los campos de ubicación para asegurar que solo uno esté asignado.
     */
    private function adjustLocation()
    {
        if ($this->almacen_id !== null) {
            $this->salon_id = null;
            $this->local_id = null;
        } elseif ($this->salon_id !== null) {
            $this->almacen_id = null;
            $this->local_id = null;
        } elseif ($this->local_id !== null) {
            $this->almacen_id = null;
            $this->salon_id = null;
        }
    }

    // Función para obtener el lugar asignado a la máquina
    public function getLugar()
    {
        if ($this->almacen_id) {
            return $this->belongsTo(Almacen::class, 'almacen_id')->first()->nombre;
        } elseif ($this->salon_id) {
            return $this->belongsTo(Salon::class, 'salon_id')->first()->nombre;
        } elseif ($this->local_id) {
            return $this->belongsTo(ClientLocal::class, 'local_id')->first()->local;
        } else {
            return 'no asignado';
        }
    }

}
