<?php

namespace App\Models\Documentos;

use App\Models\Salones\Salon;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'documentos';

    protected $fillable = [
        'admin_user_id',
        'salon_id',
        'nombre',
        'fecha',
        'archivo',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];


    public function usuario() {
        return $this->belongsTo(User::class,'admin_user_id');
    }
    public function salon() {
        return $this->belongsTo(Salon::class,'salon_id');
    }
}
