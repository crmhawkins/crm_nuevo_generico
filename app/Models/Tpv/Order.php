<?php

namespace App\Models\Tpv;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'order';

    protected $fillable = [
        'caja_id',
        'numero',
        'status',
        'total',
        'mesa_id',
        'nombre',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function hasItems()
    {
        return $this->items()->exists();
    }

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }

}

