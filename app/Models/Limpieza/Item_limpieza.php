<?php

namespace App\Models\Limpieza;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Users\User;

class Item_limpieza extends Model
{
    use SoftDeletes;

    protected $table = 'item_limpieza';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
       protected $fillable = [
        'nombre',
        'frecuencia',
        'descripcion',
    ];

    /**
     * Mutaciones de fecha.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    /**
     * Obtener el usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminUser()
    {
        return $this->belongsTo(User::class,'admin_user_id');
    }


}
