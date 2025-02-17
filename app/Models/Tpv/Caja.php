<?php

namespace App\Models\Tpv;

use App\Models\Salones\Salon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'tpv_caja';

    protected $fillable = [
        'salon_id',
        'apertura',
        'cierre',
        'previsto',
        'diferencia',
        'cambio',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function salon()
    {
        return $this->belongsTo(Salon::class,'salon_id');
    }

}

