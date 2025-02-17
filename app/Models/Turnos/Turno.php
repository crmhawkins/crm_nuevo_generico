<?php

namespace App\Models\Turnos;

use App\Models\Salones\Salon;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpseclib3\Crypt\EC\Formats\Keys\libsodium;

class Turno extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'turnos';

    protected $fillable = [
        'salon_id',
        'user_id',
        'fecha',
        'horario',
        'libre',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

