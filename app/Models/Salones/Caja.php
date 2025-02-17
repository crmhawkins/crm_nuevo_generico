<?php

namespace App\Models\Salones;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'caja_salon';

    /**
     * Atributos asignados en masa.
     *
     * @var array
     */
    protected $fillable = [
        'salon_id',
        'fecha',
        'monto',
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
        'admin_user_id',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'admin_user_id');
    }


}
