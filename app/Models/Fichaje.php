<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users\User;

class Fichaje extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'hora_pausa_inicio',
        'hora_pausa_fin',
        'tiempo_trabajado',
        'tiempo_pausa',
        'estado',
        'observaciones'
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'hora_entrada' => 'datetime',
        'hora_salida' => 'datetime',
        'hora_pausa_inicio' => 'datetime',
        'hora_pausa_fin' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getTiempoTrabajadoFormateadoAttribute()
    {
        $horas = floor($this->tiempo_trabajado / 60);
        $minutos = $this->tiempo_trabajado % 60;
        return sprintf('%02d:%02d', $horas, $minutos);
    }
}
