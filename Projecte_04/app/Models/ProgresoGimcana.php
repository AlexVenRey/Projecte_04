<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgresoGimcana extends Model
{
    protected $table = 'progreso_gimcana';

    protected $fillable = [
        'user_id',
        'gimcana_id',
        'acertijo_actual_id',
        'acertijo_resuelto',
        'pista_revelada',
        'lugar_encontrado'
    ];

    protected $casts = [
        'acertijo_resuelto' => 'boolean',
        'pista_revelada' => 'boolean',
        'lugar_encontrado' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gimcana()
    {
        return $this->belongsTo(Gimcana::class);
    }

    public function acertijo_actual()
    {
        return $this->belongsTo(Acertijo::class, 'acertijo_actual_id');
    }
}
