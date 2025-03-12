<?php

namespace App\Models\Maquinas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaquinaCategoria extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'maquinas_categorias';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function maquinas()
    {
        return $this->hasMany(Maquina::class,'categoria_id');
    }

}
