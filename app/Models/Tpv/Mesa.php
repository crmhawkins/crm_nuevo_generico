<?php

namespace App\Models\Tpv;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'mesas';

    protected $fillable = [
        'salon_id',
        'nombre',
        'posicion_x',
        'posicion_y',
        'ocupada'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $appends = ['has_open_order'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getHasOpenOrderAttribute()
    {
        return $this->orders()->where('status', 1)->whereHas('items')->exists();
    }

}

