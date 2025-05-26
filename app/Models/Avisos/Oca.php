<?php

namespace App\Models\Avisos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Users\User;
use App\Models\Salones\Salon;


class Oca extends Model
{
    use SoftDeletes;

    protected $table = 'oca';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'alta',
        'caducidad',
        'descripcion',
        'alerta_id',
        'admin_user_id',
        'salon_id'

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
    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }

}
